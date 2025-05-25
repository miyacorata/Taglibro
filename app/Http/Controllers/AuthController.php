<?php

namespace App\Http\Controllers;

use App\Models\CognitoUser;
use App\Models\User;
use Firebase\JWT\JWK;
use Firebase\JWT\JWT;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;

class AuthController extends Controller
{
    public function login()
    {
        $provider = app('oauth2.cognito');
        $authUrl = $provider->getAuthorizationUrl();
        Session::put('oauth2state', $provider->getState());
        return redirect()->away($authUrl);
    }

    public function callback(Request $request)
    {
        /** @var AbstractProvider $provider */
        $provider = app('oauth2.cognito');

        // 状態のチェック
        if (empty($request->state) || ($request->state !== Session::get('oauth2state'))) {
            Session::forget('oauth2state');
            return redirect('/')->withErrors('パラメーターに誤りがあります');
        }

        try {
            // アクセストークンを取得
            $token = $provider->getAccessToken('authorization_code', [
                'code' => $request->code
            ]);

            // ユーザー情報を取得
            $user = $provider->getResourceOwner($token);
            $userData = $user->toArray();

            // JWTトークンの確認・デコード
            $jwt = $token->getToken();
            try {
                $decoded = JWT::decode($jwt, JWK::parseKeySet($this->getJwks()));
                $userData['group'] = $decoded->{'cognito:groups'} ?? [];
            } catch (\Exception $e) {
                \Illuminate\Log\log()->error($e->getMessage(), [$e->getCode(), $e->getLine(), $e->getFile(), $e->getTraceAsString()]);
                return redirect('/')->withErrors('認証情報のデコードに失敗しました');
            }

            // セッションにユーザー情報を保存
            Session::put('user', $userData);
            Session::put('access_token', $jwt);

            // Laravel 認証システムにログイン
            Auth::guard('cognito')->login(new CognitoUser($userData));

            // アプリ内部ユーザーデータを更新もしくは追加
            /** @var User $appUser */
            $appUser = User::whereSub($userData['sub'])->first() ?? new User();
            $appUser->sub = $userData['sub'];
            $appUser->preferred_username = $userData['preferred_username'];
            $appUser->name = $userData['name'];
            $appUser->email = $userData['email'];
            $appUser->save();

            return redirect('/dashboard');
        } catch (IdentityProviderException $e) {
            \Illuminate\Log\log()->error($e->getMessage(), [$e->getCode(), $e->getLine(), $e->getFile(), $e->getTraceAsString()]);
            return redirect('/')->withErrors($e->getMessage());
        } catch (GuzzleException $e) {
            \Illuminate\Log\log()->error($e->getMessage(), [$e->getCode(), $e->getLine(), $e->getFile(), $e->getTraceAsString()]);
            return redirect('/')->withErrors($e->getMessage());
        }
    }

    public function logout()
    {
        // Laravel 認証からログアウト
        Auth::guard('cognito')->logout();

        $cognitoLogout = config('services.cognito.domain')
            .'/logout?client_id='.config('services.cognito.client_id')
            .'&logout_uri='.urlencode(url('/'));

        Session::forget('user');
        Session::forget('access_token');
        return redirect()->away($cognitoLogout);
    }

    private function getJwks(): array
    {
        if (Cache::has('jwks')) {
            $jwks = Cache::get('jwks');
        } else {
            $url = 'https://cognito-idp.'.config('services.cognito.region').'.amazonaws.com'
                .'/'.config('services.cognito.user_pool_id').'/.well-known/jwks.json';
            $jwks = Http::get($url)->json();
            Cache::put('jwks', $jwks, now()->addMinutes(5));
        }
        return $jwks;
    }
}

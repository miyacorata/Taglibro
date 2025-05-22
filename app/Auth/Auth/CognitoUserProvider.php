<?php

namespace App\Auth\Auth;

use App\Models\CognitoUser;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Support\Facades\Session;

class CognitoUserProvider implements UserProvider
{
    public function retrieveById($identifier)
    {
        // セッションからユーザー情報を取得
        $userData = Session::get('user');

        if (!$userData) {
            return null;
        }

        // CognitoUser モデルのインスタンスを作成して返す
        return new CognitoUser($userData);
    }

    public function retrieveByToken($identifier, $token)
    {
        // この実装では使用しない
        return null;
    }

    public function updateRememberToken(Authenticatable $user, $token)
    {
        // この実装では使用しない
    }

    public function retrieveByCredentials(array $credentials)
    {
        // この実装では使用しない
        return null;
    }

    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        // この実装では使用しない
        return false;
    }

    public function rehashPasswordIfRequired(Authenticatable $user, #[\SensitiveParameter] array $credentials, bool $force = false)
    {
        // この実装では使用しない
    }
}

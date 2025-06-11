<?php

namespace App\Http\Controllers;

use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

final class UserDataController extends Controller
{
    public function index()
    {
        $appUser = User::whereSub(Auth::user()->getAuthIdentifier())->firstOrFail();
        return view('admin.dashboard', [
            'user' => Session::get('user'),
            'appUser' => $appUser,
            'token' => Session::get('access_token'),
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'user_sub' => ['required', 'exists:users,sub'],
            'icon_url' => ['nullable', 'url'],
            'biography' => ['nullable', 'string', 'max:1000'],
        ]);

        if ($request->post('user_sub') !== Auth::user()->getAuthIdentifier()) {
            abort(403, '他のユーザーの情報を編集することはできません');
        }

        $userData = User::whereSub(Auth::user()->getAuthIdentifier())->firstOrFail();
        $userData->icon_url = $request->post('icon_url');
        $userData->biography = $request->post('biography');
        $userData->save();

        return redirect(route('dashboard'))->with('message', 'ユーザー情報を更新しました');
    }
}

<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;

class CognitoUser implements Authenticatable
{
    protected $attributes;

    public function __construct(array $attributes)
    {
        $this->attributes = $attributes;
    }

    public function getAuthIdentifierName()
    {
        return 'sub'; // Cognito の一意のユーザーID（subject）
    }

    public function getAuthIdentifier()
    {
        return $this->attributes['sub'] ?? null;
    }

    public function getAuthPassword()
    {
        return null; // パスワードは Cognito で管理されるため不要
    }

    public function getRememberToken()
    {
        return null; // この実装では使用しない
    }

    public function setRememberToken($value)
    {
        // この実装では使用しない
    }

    public function getRememberTokenName()
    {
        return null; // この実装では使用しない
    }

    // ユーザーの属性にアクセスするためのメソッド
    public function getAttribute($key)
    {
        return $this->attributes[$key] ?? null;
    }

    // すべての属性を取得
    public function getAttributes()
    {
        return $this->attributes;
    }

    public function getAuthPasswordName()
    {
        // この実装では使用しない
    }
}

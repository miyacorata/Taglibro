<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;

final class CognitoUser implements Authenticatable
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
         // パスワードは Cognito で管理されるため不要
    }

    public function getRememberToken()
    {
         // この実装では使用しない
    }

    public function setRememberToken($value)
    {
        // この実装では使用しない
    }

    public function getRememberTokenName()
    {
         // この実装では使用しない
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

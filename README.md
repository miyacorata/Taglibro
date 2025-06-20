# Taglibro

ゆるふわブログシステム

## 必要なもの

- PHP8.3系が動くなんらかの鯖
- Composer
- Cognitoユーザープール
- MySQL等のDB
  - たぶんSQLiteとかでも動きます

## 構築方法

### Cognitoの準備

Cognitoユーザープールでアプリケーションクライアントを作成します

作成にあたっては「従来のウェブアプリケーション」を選択し、「リターンURL」(コールバックURL)は `{設置しようとするURL}/callback` にして作成します (例: `https://blog.example.net/callback` )

サインアウトURLは `{設置しようとするURL}` にします (例: `https://blog.example.net` )

OAuth付与タイプは「認証コード付与」にします

OpenID Connect のスコープは以下の通りに設定します

- email
- openid
- profile

マネージドログインのスタイルをお好みで設定しておきます (設定しないと動きません)

### Taglibroの準備

gitで引っ張ってきて次の通りコンソールを叩きます

```bash
composer install
cp .env.example .env
nano .env
```

`.env` を編集します

#### 変更すべき主な `.env` の項目

| 項目                      | 内容                                           |
|:------------------------|:---------------------------------------------|
| `APP_NAME`              | ブログ(サイト)名                                    |
| `APP_DESCRIPTION`       | サブタイトルみたいなやつ はてなブログでいう「ひとこと説明」               |
| `DB_` で始まる項目            | データベース周りを設定します<br>SQLite以外のなんかを使ったほうが良いと思います |
| `COGNITO_` で始まる項目       | Cognitoのいろいろを設定します                           |
| `COGNITO_ARROWED_GROUP` | ログインを許可するユーザーグループをコンマ区切りで設定します               |

終わったら引き続きコンソールを叩きます

```bash
php artisan key:generate
php artisan migrate
```

ここまでやると動けるようになります

ユーザーはCognito経由でログインすると勝手に生えます あとはノリで

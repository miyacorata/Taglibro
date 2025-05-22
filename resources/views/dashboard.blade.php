@extends('layout')

@section('title', 'Dashboard')

@section('main')
    <section class="hero is-light">
        <div class="hero-body">
            <p class="title mb-2">Dashboard</p>
            <p class="subtitle">ダッシュボード</p>
        </div>
    </section>
    <section class="section">
        <div class="container content">
            <h2>ユーザー情報</h2>
            <div class="subtitle is-5">ようこそ、{{ $user['name'] ?? $user['preferred_username'] }} さん。</div>
            <table class="table is-fullwidth">
                <tr>
                    <th>ユーザー名</th>
                    <td>{{ $user['preferred_username'] ?? '-' }}</td>
                </tr>
                <tr>
                    <th>名前</th>
                    <td>{{ $user['name'] ?? '(未設定)' }}</td>
                </tr>
                <tr>
                    <th>メールアドレス</th>
                    <td>{{ $user['email'] ?? '(未設定)' }}</td>
                </tr>
                <tr>
                    <th>所属グループ</th>
                    <td>
                        @foreach($user['group'] ?? [] as $group)
                            <span class="tag is-info is-light">{{ $group }}</span>
                        @endforeach
                    </td>
                </tr>
            </table>
            <h3>Raw</h3>
            <pre>{{ json_encode($user, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
        </div>
    </section>
@endsection

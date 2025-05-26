@extends('layout')

@section('title', 'Dashboard')

@php
/**
 * @var $appUser \App\Models\User
 */
@endphp

@section('main')
    <section class="hero is-light is-small">
        <div class="hero-body container px-0">
            <p class="title mb-1 is-4">管理画面</p>
            <p class="subtitle is-6">Administra ekrano</p>
        </div>
    </section>
    <section class="section">
        <div class="container content">
            <h2>ユーザー情報</h2>
            <div class="subtitle is-5">ようこそ、{{ $user['name'] ?? $user['preferred_username'] }} さん。</div>
            @if(!empty($message))
                <div class="message is-info">
                    <div class="message-body">{{ $message }}</div>
                </div>
            @endif
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
                    <th>アイコンURL</th>
                    <td>
                        @if(!empty($appUser->icon_url))
                            {{ $appUser->icon_url }}
                        @else
                            <span class="tag is-warning is-light">未設定</span>
                        @endif
                    </td>
                </tr>
            </table>
            <button class="button" id="open-form">ユーザー情報を変更する</button>
            <div class="card" style="display: none;" id="form">
                <header class="card-header">
                    <p class="card-header-title">ユーザー情報を変更する</p>
                </header>
                <div class="card-content">
                    <article class="message is-light is-small">
                        <div class="message-body">
                            ここで変更できない情報は認証基盤上で変更する必要があります
                        </div>
                    </article>
                    <form action="{{ route('user.update') }}" method="post">
                        <div class="field">
                            <label class="label">アイコンURL</label>
                            <div class="control">
                                <input class="input" type="text" placeholder="https://">
                            </div>
                            <p class="help"><code>https://</code> で始まるURLを入力します</p>
                        </div>
                        <div class="field">
                            <label class="label">プロフィール</label>
                            <div class="control">
                                <textarea class="textarea" rows="5"></textarea>
                            </div>
                            <p class="help">いい感じの自己紹介を入れましょう Markdown記法でリンクを掲載できます</p>
                        </div>
                        <div class="field is-grouped is-justify-content-space-between">
                            <div class="control">
                                <button type="button" class="button is-warning" id="close-form">キャンセル</button>
                            </div>
                            <div class="control">
                                <button type="submit" class="button is-primary">保存する</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('head')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const of = document.getElementById('open-form');
            const cf = document.getElementById('close-form');
            const form = document.getElementById('form');
            of.addEventListener('click', () => {
                form.classList.add('is-active');
            });
        });
    </script>
@endsection

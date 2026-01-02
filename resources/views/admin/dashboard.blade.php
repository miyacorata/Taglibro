@extends('layout')

@section('title', 'Dashboard')

@php
/**
 * @var $appUser \App\Models\User
 */
@endphp

@section('main')
    @include('admin.admin-navbar')
    <section class="section">
        <div class="container content">
            <h2>ユーザー情報</h2>
            <div class="subtitle is-5">ようこそ、{{ $user['name'] ?? $user['preferred_username'] }} さん。</div>
            @if(session("message"))
                <div class="message is-info">
                    <div class="message-body">
                        <i class="fa-solid fa-circle-info"></i>
                        {{ session("message") }}
                    </div>
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
            <button class="button" id="open-form">
                <span class="icon"><i class="fa-solid fa-user-pen"></i></span>
                <span>ユーザー情報を変更する</span>
            </button>
            <div class="card is-display-none" id="form">
                <header class="card-header">
                    <p class="card-header-title">
                        <span class="icon"><i class="fa-solid fa-user-pen"></i></span>
                        <span>ユーザー情報を変更する</span>
                    </p>
                </header>
                <div class="card-content">
                    <article class="message is-light is-small">
                        <div class="message-body">
                            ここで変更できない情報は認証基盤上で変更する必要があります
                        </div>
                    </article>
                    <form action="{{ route('user.update') }}" method="post">
                        @csrf
                        <input type="hidden" name="user_sub" value="{{ Auth::user()->getAuthIdentifier() }}">
                        <div class="field">
                            <label class="label">アイコンURL</label>
                            <div class="control">
                                <input class="input" type="text" name="icon_url" placeholder="https://" value="{{ old('icon_url', $appUser->icon_url) }}">
                            </div>
                            <p class="help"><code>https://</code> で始まるURLを入力します</p>
                        </div>
                        <div class="field">
                            <label class="label">プロフィール</label>
                            <div class="control">
                                <textarea class="textarea" name="biography" rows="5">{{ old('biography', $appUser->biography) }}</textarea>
                            </div>
                            <p class="help">いい感じの自己紹介を入れましょう Markdown記法でリンクを掲載できます</p>
                        </div>
                        <div class="field is-grouped is-justify-content-space-between">
                            <div class="control">
                                <button type="button" class="button is-warning" id="close-form">
                                    <span class="icon"><i class="fa-solid fa-xmark"></i></span>
                                    <span>キャンセル</span>
                                </button>
                            </div>
                            <div class="control">
                                <button type="submit" class="button is-primary">
                                    <span class="icon"><i class="fa-solid fa-floppy-disk"></i></span>
                                    <span>保存する</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <h2>DBキャッシュクリア</h2>
            <p>記事本文・プロフィール等のキャッシュを消去することができます。</p>
            <form action="{{ route('cacheFlush') }}" method="post">
                @csrf
                <button class="button is-warning">
                    <span class="icon"><i class="fa-solid fa-broom"></i></span>
                    <span>キャッシュクリア</span>
                </button>
            </form>
        </div>
    </section>
@endsection

@section('head')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const of = document.getElementById('open-form');
            const cf = document.getElementById('close-form');
            const form = document.getElementById('form');
            console.dir(form);
            of.addEventListener('click', () => {
                form.classList.remove('is-display-none');
                of.classList.add('is-display-none');
            });
            cf.addEventListener('click', () => {
                form.classList.add('is-display-none');
                of.classList.remove('is-display-none');
            });
        });
    </script>
@endsection

@extends('layout')

@section('title', '記事新規作成')

@section('main')
    <section class="hero is-light is-small section py-0">
        <div class="hero-body container px-0">
            <p class="title mb-1 is-4">記事の新規作成</p>
            <p class="subtitle is-6">Krei novan artikolon</p>
        </div>
    </section>
    <section class="section pt-5">
        <div class="container">
            <form action="{{ route('article.store') }}" method="post">
                @csrf
                @method('put')
                <div class="field">
                    <label class="label">記事タイトル</label>
                    <div class="control">
                        <input class="input is-medium has-text-weight-bold" type="text" name="title" value="{{ old('title') }}" placeholder="いい感じのタイトル" required>
                    </div>
                </div>
                <div class="field">
                    <label class="label">サムネイル画像URL</label>
                    <div class="control">
                        <input class="input" type="text" name="top_image_url" value="{{ old('top_image_url') }}" placeholder="https://">
                    </div>
                </div>
                <div class="field">
                    <label class="label">スラッグ</label>
                    <div class="field has-addons mb-0">
                        <p class="control">
                            <a class="button is-static">
                                {{ url('/').'/article/{year}/{month}/' }}
                            </a>
                        </p>
                        <p class="control is-expanded">
                            <input class="input" type="text" name="slug" placeholder="some-iikanji-title" value="{{ old('slug', Str::orderedUuid()) }}">
                        </p>
                    </div>
                    <p class="help">
                        記事のURLに使う文字列です<br>
                        URI用に予約された記号類は使えません マルチバイト文字は避けるといいかもしれません<br>
                        空欄にした場合時間ベースUUIDを用います
                    </p>
                </div>
                <div class="field">
                    <label class="label">本文</label>
                    <div class="control">
                        <textarea class="textarea is-family-monospace" name="content" rows="15" required>{{ old('content') }}</textarea>
                    </div>
                    <p class="help">
                        Markdown記法(GitHub-Flavored Markdown)で入力できます
                        HTMLの入力は出力時にエスケープされます<br>
                        たぶんVSCodeあたりで書いてコピペしたほうがいいですよ
                    </p>
                </div>
            </form>
        </div>
    </section>
@endsection

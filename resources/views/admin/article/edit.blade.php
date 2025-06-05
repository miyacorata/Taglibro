@extends('layout')

@section('title', '記事編集')

@php
/**
 * @var $article \App\Models\Article
 */
@endphp

@section('main')
    @include('admin.admin-navbar', ['title' => '記事編集', 'title_eo' => 'Redakti artikolon'])
    <section class="section pt-5">
        <div class="container">
            <form action="{{ route('article.update', ['article' => $article]) }}" method="post" id="article-form">
                @csrf
                @method('patch')
                <input type="hidden" name="post" value="{{ $article->slug }}">
                <input type="hidden" name="published" value="0" id="is-published">
                <div class="field">
                    <label class="label">記事タイトル</label>
                    <div class="control">
                        <input class="input is-medium has-text-weight-bold" type="text" name="title" value="{{ old('title', $article->title) }}" placeholder="いい感じのタイトル" required>
                    </div>
                </div>
                <div class="field">
                    <label class="label">簡単な説明とか</label>
                    <div class="control">
                        <input class="input" type="text" name="description" value="{{ old('description', $article->description) }}" placeholder="どんな話？">
                    </div>
                    <p class="help">記事の一覧ページ等でタイトルの下に表示されます</p>
                </div>
                <div class="field">
                    <label class="label">サムネイル画像URL</label>
                    <div class="control">
                        <input class="input" type="text" name="top_image_url" value="{{ old('top_image_url', $article->top_image_url) }}" placeholder="https://">
                    </div>
                </div>
                <div class="field">
                    <label class="label">スラッグ</label>
                    <div class="field has-addons mb-0">
                        <p class="control">
                            <a class="button is-static is-display-none-touch">
                                {{ url('/').'/article/{year}/{month}/' }}
                            </a>
                        </p>
                        <p class="control is-expanded">
                            <input class="input" type="text" name="slug" placeholder="some-iikanji-title" value="{{ old('slug', $article->slug) }}" readonly>
                        </p>
                    </div>
                    <p class="help">
                        記事のURLに使う文字列です よほどのことがない限り変えないほうがいいですよ<br>
                        URI用に予約された記号類は使えません マルチバイト文字は避けるといいかもしれません
                    </p>
                </div>
                <div class="field">
                    <label class="label">本文</label>
                    <div class="control">
                        <textarea class="textarea is-family-monospace" name="content" rows="15" required>{{ old('content', $article->content) }}</textarea>
                    </div>
                    <p class="help">
                        Markdown記法(GitHub-Flavored Markdown)で入力できます
                        HTMLの入力は出力時にエスケープされます<br>
                        たぶんVSCodeあたりで書いてコピペしたほうがいいですよ
                    </p>
                </div>
                <div class="is-display-flex is-justify-content-space-between">
                    <div class="field">
                        <p class="control">
                            <button type="button" class="button is-light" id="delete" style="background-color: #BA6EA5">
                                <span class="icon"><i class="fa-solid fa-delete-left"></i></span>
                                <span>削除する</span>
                            </button>
                        </p>
                    </div>
                    <div class="field is-grouped is-justify-content-end">
                        <p class="control">
                            <button type="submit" class="button is-info" id="draft">
                                <span class="icon"><i class="fa-solid fa-floppy-disk"></i></span>
                                <span>下書き保存する</span>
                            </button>
                        </p>
                        <p class="control">
                            <button type="submit" class="button is-primary" id="publish">
                                <span class="icon"><i class="fa-solid fa-paper-plane"></i></span>
                                <span>保存して公開する</span>
                            </button>
                        </p>
                        <script>
                            document.addEventListener('DOMContentLoaded', () => {
                                /** @type {HTMLFormElement} */
                                const form = document.getElementById('article-form');
                                document.getElementById('publish').addEventListener('click', (event) => {
                                    event.preventDefault();
                                    if (form.reportValidity() && confirm('記事を公開します。よろしいですか？')) {
                                        document.getElementById('is-published').setAttribute('value', '1');
                                        form.submit();
                                    }
                                });
                                /** @type {HTMLFormElement} */
                                const deleteForm = document.getElementById('article-delete');
                                document.getElementById('delete').addEventListener('click', () => {
                                    if (confirm('記事を削除します。よろしいですか？')) {
                                        deleteForm.submit();
                                    }
                                });
                            });
                        </script>
                    </div>
                </div>
            </form>
            <form action="{{ route('article.destroy', ['article' => $article]) }}" method="post" id="article-delete">
                @csrf
                @method('delete')
            </form>
        </div>
    </section>
@endsection

@extends('layout')

@section('title', '記事編集')

@php
/**
 * @var $article \App\Models\Article
 * @var $tags \Illuminate\Database\Eloquent\Collection
 */
@endphp

@section('head')
    <script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify@4.35.1/dist/tagify.min.js" integrity="sha512-Mm9/6ECyUYqvWMR15XVcY984nqM2E0YI8MJmNV6sbxFgEbj8arr4KZKc3U2P9aRjJpxDv+gjDceFh/zNofsEVg==" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@yaireo/tagify@4.35.1/dist/tagify.min.css" integrity="sha512-PViFRBg+E3S/uDWbIm6exrJi+NrUFMj8PUsQ//L0j+I6STmDi0bMVuPnn8v0TqXUXZOM9Gqyo9oDY7KWrzjGUw==" crossorigin="anonymous">
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const tagInput = document.getElementById("tag-input");
            const whiteList = "{{ $tags->implode('tag', ',') }}";
            new Tagify(tagInput, {
                whitelist: whiteList.split(','),
                originalInputValueFormat: valuesArr => valuesArr.map(item => item.value).join(','),
                dropdown: {
                    classname: "color-blue",
                    enabled: 0,
                    maxItems: 5,
                    position: "text",
                    closeOnSelect : false,
                    highlightFirst: true,
                },
            });
        });
    </script>
    <style>
        .tagify {
            /* Tagify Customize */
            --tag-pad: 2px 6px;
        }
    </style>
@endsection

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
                                {{ url('/').'/article/' }}
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
                    <label class="label">タグ</label>
                    <div class="control">
                        <input class="input" type="text" name="tags" value="{{ old('tags', $article->tags->implode('tag', ',')) }}" id="tag-input">
                    </div>
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
                                <span>非公開に切り替えて保存する</span>
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

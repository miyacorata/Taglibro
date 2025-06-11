@extends('layout')

@section('title', '記事一覧')

@php
/**
 * @var $articles \Illuminate\Database\Eloquent\Collection
 * @var $article \App\Models\Article
 */
@endphp

@section('head')
    <style>
        table.table > tbody > tr > td {
            vertical-align: middle;
        }
        table.table > tbody > tr > td:not(:last-child),
        table.table > thead > tr > th:not(:last-child) {
            text-align: center;
        }
    </style>
@endsection

@section('main')
    @include('admin.admin-navbar', ['title' => '記事一覧', 'title_eo' => 'Listo de artikoloj'])
    <section class="section pt-5">
        <div class="container">
            @if(session("message"))
                <div class="message is-info">
                    <div class="message-body">
                        <i class="fa-solid fa-circle-info"></i>
                        {{ session("message") }}
                    </div>
                </div>
            @endif
            <div class="is-display-flex is-justify-content-space-between">
                <div>{{ $articles->count() }}件の記事</div>
                <div>
                    <a href="{{ route('article.create') }}" class="button is-link">
                        <span class="icon"><i class="fa-solid fa-file"></i></span>
                        <span>新規作成</span>
                    </a>
                </div>
            </div>
            <table class="table is-fullwidth">
                <thead>
                <tr>
                    <th style="width: 160px">操作</th>
                    <th style="width: 180px">作成 / 更新</th>
                    <th style="width: 110px">状態</th>
                    <th>記事</th>
                </tr>
                </thead>
                <tbody>
                @forelse($articles as $article)
                    <tr>
                        <td>
                            <a href="{{ route('article.edit', ['article' => $article->slug]) }}" class="button is-small">
                                <span class="icon"><i class="fa-solid fa-pencil"></i></span>
                                <span>編集</span>
                            </a>
                            <a href="{{ route('article.edit', ['article' => $article->slug]) }}" class="button is-small">
                                <span class="icon"><i class="fa-solid fa-arrow-up-right-from-square"></i></span>
                                <span>開く</span>
                            </a>
                        </td>
                        <td>
                            {{ $article->created_at->format('Y/m/d') }} <span class="is-size-7">{{ $article->created_at->format('H:i') }}</span><br>
                            {{ $article->updated_at->format('Y/m/d') }} <span class="is-size-7">{{ $article->updated_at->format('H:i') }}</span>
                        </td>
                        <td>
                            <span class="tag {{ $article->published ? 'is-primary' : 'is-info'}} mr-1">
                                @if($article->published)
                                    <span class="icon"><i class="fa-solid fa-paper-plane"></i></span>
                                    <span>公開済</span>
                                @else
                                    <span class="icon"><i class="fa-solid fa-floppy-disk"></i></span>
                                    <span>下書き</span>
                                @endif
                            </span>
                        </td>
                        <td>
                            <strong>{{ $article->title }}</strong><br>
                            <span class="is-size-7">
                                {{ $article->description ?? Str::limit($article->content, 30, ' ...') }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">
                            <article class="message is-warning">
                                <div class="message-body">
                                    <i class="fa-solid fa-circle-info"></i> 記事がありません
                                </div>
                            </article>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>

        </div>
    </section>
@endsection

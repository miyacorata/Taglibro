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
            <table class="table is-fullwidth">
                <thead>
                <tr>
                    <th style="width: 160px">操作</th>
                    <th style="width: 140px">投稿日時</th>
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
                        <td>{{ $article->created_at->format('Y/m/d') }}</td>
                        <td>
                            <strong>{{ $article->title }}</strong><br>
                            <span class="is-size-7">
                                {{ $article->description ?? Str::limit($article->content, 30, ' ...') }}
                            </span>
                        </td>
                    </tr>
                @empty
                @endforelse
                </tbody>
            </table>

        </div>
    </section>
@endsection

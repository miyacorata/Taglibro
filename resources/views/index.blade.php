@extends('layout')

@php
/**
 * @var $articles \Illuminate\Contracts\Pagination\LengthAwarePaginator
 * @var $article \App\Models\Article
 */
@endphp

@section('head')
    <style>
        .thumbnail > div.placeholder {
            height: 100%;
            background-color: lightgray;
            text-align: center;
            color: gray;
            display: flex;
            justify-content: center;
            align-items: center;
            font-weight: lighter;
        }
    </style>
@endsection

@section('main')
    <div class="container">
        <section class="section pb-4">
            <h1 class="title mb-1">最近の記事</h1>
            <div class="subtitle is-6">Lastatempaj Artikoloj</div>
        </section>
        <section class="section pt-0">
            @if($articles->count())
                <div class="mb-4 has-text-centered has-text-weight-light is-size-7">
                    {{ $articles->currentPage().' / '.$articles->lastPage().' ページ' }}
                </div>
                {{ $articles->links('vendor.pagination.bulma') }}
            @else
                <article class="message is-warning">
                    <div class="message-header">
                        <span><i class="fa-solid fa-circle-exclamation mr-2"></i>このページにはまだ記事がありません</span>
                    </div>
                    <div class="message-body">
                        記事がある最後のページは
                        <a href="{{ $articles->url($articles->lastPage()) }}" class="has-text-weight-bold">こちら</a>
                        です
                    </div>
                </article>
            @endif
            @foreach($articles->items() as $article)
                <div class="box">
                    <a class="media has-text-black" href="{{ route('viewArticle', ['slug' => $article->slug]) }}">
                        <div class="media-left">
                            <figure class="image is-16by9 thumbnail" style="width: 200px">
                                @if(!empty($article->top_image_url) && filter_var($article->top_image_url, FILTER_VALIDATE_URL))
                                    <img src="{{ $article->top_image_url }}" alt="{{ $article->title }}">
                                @else
                                    <div class="placeholder">No Image</div>
                                @endif
                            </figure>
                        </div>
                        <div class="media-content">
                            <p class="title is-4 mb-3">{{ $article->title }}</p>
                            <p class="content">{{ $article->description }}</p>
                            <div class="tags">
                                <span class="tag is-light mr-2">
                                    <i class="fa-solid fa-file-lines mr-1"></i>{{ $article->created_at->format('Y/m/d') }}
                                </span>
                                @foreach($article->tags as $tag)
                                    <span class="tag is-link is-light">{{ $tag->tag }}</span>
                                @endforeach
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </section>
    </div>
@endsection

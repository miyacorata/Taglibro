@extends('layout')

@php
/**
 * @var $article \App\Models\Article
 */
@endphp

@section('title', $article->title)

@section('head')
    <meta property="og:title" content="{{ $article->title.' - '.config('app.name') }}">
    <meta property="og:type" content="article">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ $article->top_image_url ?? config('app.main_image') }}">
    <meta property="og:site_name" content="{{ config('app.name') }}">
    <meta property="og:description" content="{{ $article->description.' '.Str::limit($article->content, 50, ' ...') }}">
    <meta property="og:locale" content="ja_JP">
    <meta name="description" content="{{ $article->description.' '.Str::limit($article->content, 50, ' ...') }}">
    <meta name="author" content="{{ $article->user->name }}">
    <meta name="keywords" content="{{ $article->tags->implode('tag', ',') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.11.1/styles/github-dark-dimmed.min.css" integrity="sha512-zcatBMvxa7rT7dDklfjauWsfiSFParF+hRfCdf4Zr40/MmA1gkFcBRbop0zMpvYF3FmznYFgcL8wlcuO/GwHoA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.11.1/highlight.min.js"></script>
    <script>
        hljs.highlightAll();
        document.addEventListener('DOMContentLoaded', () => {
            const misskeyShareButton = document.getElementById('misskey-share')
            misskeyShareButton.addEventListener('click', (event) => {
                event.preventDefault();
                window.open(misskeyShareButton.getAttribute('href'), '_blank', 'width=800,height=800');
            });
            const blueskyShareButton = document.getElementById('bluesky-share')
            blueskyShareButton.addEventListener('click', (event) => {
                event.preventDefault();
                window.open(blueskyShareButton.getAttribute('href'), '_blank', 'width=800,height=800');
            });
        });
    </script>
    <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
    <style>
        .user-icon {
            border-radius: 10%;
            box-shadow: 0 0 5px lightgray;
        }
        .user-icon-placeholder {
            height: 100%;
            background-color: lightgray;
            text-align: center;
            color: gray;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 1.5em;
        }
        .embed-content {
            margin-bottom: var(--bulma-content-block-margin-bottom);
        }
        .embed-content > iframe {
            max-width: 100%;
        }
    </style>
@endsection

@section('main')
    <div class="container">
        <section class="section">
            <h1 class="title is-2 mb-2">{{ $article->title }}</h1>
            <div class="subtitle is-7 has-text-black-20">
                <i class="fa-solid fa-file-lines mr-1"></i>{{ $article->created_at->format('Y/m/d H:i:s') }}
                @if($article->updated_at->notEqualTo($article->created_at))
                    <i class="fa-solid fa-rotate-right mr-1 ml-3"></i>{{ $article->updated_at->format('Y/m/d H:i:s') }}
                @endif
            </div>
            @if($article->tags->count())
                <div class="tags">
                    @foreach($article->tags as $tag)
                        <span class="tag is-link is-light">{{ $tag->tag }}</span>
                    @endforeach
                </div>
            @endif
        </section>
        <section class="section columns pt-0">
            <div class="column">
                <article class="content" id="article-content">
                    @if(!empty($article->top_image_url))
                        <div id="top_image" class="image mb-5">
                            <img src="{{ $article->top_image_url }}" alt="{{ $article->title }}">
                        </div>
                    @endif
                    {!! $converted_article_content !!}
                </article>
                <hr>
                <div id="share">
                    <div>
                        <a href="https://twitter.com/share?ref_src=twsrc%5Etfw" class="twitter-share-button" data-show-count="false">Tweet</a><script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
                        <a href="https://misskey-hub.net/ja/share/?text={{ urlencode($article->title.' - '.config('app.name').PHP_EOL.url()->current()) }}" target="_blank" class="button is-small is-dark py-1" id="misskey-share">
                            <span class="icon is-medium"><img src="{{ asset('images/misskey.png') }}" alt="Misskey"></span>
                            <span>ノート</span>
                        </a>
                        <a href="https://bsky.app/intent/compose?text={{ urlencode($article->title.' - '.config('app.name').PHP_EOL.url()->current()) }}" target="_blank" class="button is-small is-dark py-1" id="bluesky-share" style="background: #1285fe; color: white;">
                            <span class="icon is-medium"><i class="fa-brands fa-bluesky"></i></span>
                            <span>シェア</span>
                        </a>
                    </div>
                    <p class="mt-4 is-size-7">
                        <a href="https://misskey-hub.net/ja/brand-assets/" target="_blank">Misskeyのロゴ</a> は
                        <a href="https://creativecommons.org/licenses/by-sa/4.0/deed.ja" target="_blank" title="クリエイティブ・コモンズ・ライセンス 表示-継承 4.0 国際">CC BY-SA 4.0</a>
                        で提供されています。
                    </p>
                </div>
                <div id="pager" class="columns mt-3">
                    <div class="column">
                        @if(!empty($article->next()))
                            <a href="{{ route('viewArticle', ['slug' => $article->next()->slug]) }}" class="box column">
                                <div class="is-size-7 mb-2 has-text-grey-dark">
                                    <i class="fa-solid fa-chevron-left"></i>
                                    次の記事
                                </div>
                                <p class="title is-5 has-text-weight-medium">{{ $article->next()->title }}</p>
                            </a>
                        @endif
                    </div>
                    <div class="column">
                        @if(!empty($article->previous()))
                            <a href="{{ route('viewArticle', ['slug' => $article->previous()->slug]) }}" class="box column has-text-right">
                                <div class="is-size-7 mb-2 has-text-grey-dark">
                                    前の記事
                                    <i class="fa-solid fa-chevron-right"></i>
                                </div>
                                <p class="title is-5 has-text-weight-medium">{{ $article->previous()->title }}</p>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
            <aside class="column is-one-quarter">
                <div class="is-display-flex mb-4">
                    <div class="image is-48x48 mr-3">
                        @if(!empty($article->user->icon_url))
                            <img src="{{ $article->user->icon_url }}" alt="{{ $article->user->preferred_username }}" class="user-icon">
                        @else
                            <div class="user-icon user-icon-placeholder"><i class="fa-solid fa-user"></i></div>
                        @endif
                    </div>
                    <div>
                        <h2 class="title is-4">{{ $article->user->name }}</h2>
                        <div class="subtitle is-6">{{ '@'.$article->user->preferred_username }}</div>
                    </div>
                </div>
                <div>{!! nl2br($converted_profile_biography) !!}</div>
                @auth
                    <div class="mt-3">
                        <hr>
                        <a href="{{ route('article.edit', ['article' => $article->slug]) }}" class="button is-small">
                            <span class="icon"><i class="fa-solid fa-file-pen"></i></span>
                            <span>記事を編集する</span>
                        </a>
                    </div>
                @endauth
            </aside>
        </section>
    </div>
@endsection

@extends('layout')

@php
/**
 * @var $article \App\Models\Article
 */
@endphp

@section('title', $article->title)

@section('main')
    <div class="container">
        <section class="section">
            <h1 class="title is-2 mb-2">{{ $article->title }}</h1>
            <div class="subtitle is-7 has-text-black-20">
                Kreita je {{ $article->created_at->format('Y/m/d H:i:s') }}
                @if($article->updated_at->notEqualTo($article->created_at))
                    , Ĝisdatigita je {{ $article->updated_at->format('Y/m/d H:i:s') }}
                @endif
            </div>
        </section>
        <section class="section columns pt-0" style="flex-direction: row-reverse">
            <div class="column is-one-quarter">
                1/4
            </div>
            <div class="column">
                <div class="content" id="article-content">
                    {!! Str::markdown($article->content, ['html_input' => 'escape', 'arrow_unsafe_links' => false]) !!}
                </div>
            </div>
        </section>
    </div>
@endsection

@extends('layout')

@section('title', '記事一覧')

@php
    /**
     * @var $articles \Illuminate\Database\Eloquent\Collection
     */
@endphp

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
            @dump($articles)
        </div>
    </section>
@endsection

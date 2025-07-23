@php use Illuminate\Support\Facades\View; @endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@if(View::hasSection('title')) @yield('title') - @endif{{ config('app.name') }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@1.0.4/css/bulma.min.css" integrity="sha512-yh2RE0wZCVZeysGiqTwDTO/dKelCbS9bP2L94UvOFtl/FKXcNAje3Y2oBg/ZMZ3LS1sicYk4dYVGtDex75fvvA==" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="{{ url('/css/style.css') }}?rev={{ exec('git rev-parse --short HEAD') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;700&family=IBM+Plex+Sans+JP:wght@300;400;500;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@twemoji/api@latest/dist/twemoji.min.js" crossorigin="anonymous"></script>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            twemoji.parse(document.body);
        });
    </script>

    @yield('head')

</head>
<body>

<section class="hero is-primary">
    <div class="hero-body py-5">
        <p class="title mb-2 has-text-weight-medium"><a href="{{ url('/') }}" class="has-text-black">{{ config('app.name') }}</a></p>
        <p class="subtitle is-6">{{ config('app.description') }}</p>
    </div>
</section>

@if($errors->count())

    <div class="notification is-danger is-light">
        <button class="delete"></button>
        <p><b>ERROR!</b></p>
        @foreach($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const clb = document.querySelector('.notification > button.delete');
                clb.addEventListener('click', () => {
                    clb.parentNode.parentNode.removeChild(clb.parentNode);
                });
            });

        </script>
    </div>

@endif

@yield('main')

<footer class="footer has-background-inherit">
    <div class="has-text-centered">
        <p>{{ config('app.name') }} - {{ $_SERVER['HTTP_HOST'] }}</p>
        <nav class="breadcrumb has-bullet-separator has-text-weight-medium is-centered mt-2">
            <ul>
                <li><a href="{{ url('/') }}" class="pl-3">トップページ</a></li>
                <li><a href="https://github.com/miyacorata/taglibro" target="_blank">GitHub</a></li>
                <li><a href="{{ route('dashboard') }}">管理画面</a></li>
            </ul>
        </nav>
        <p class="is-size-7">
            <a href="https://bulma.io" target="_blank">
                <img
                    src="https://bulma.io/assets/images/made-with-bulma--semiblack.png"
                    alt="Made with Bulma"
                    width="170"
                    height="32">
            </a><br>
            Emoji : <a href="https://github.com/jdecked/twemoji" target="_blank" class="has-text-weight-bold">jdecked/twemoji</a>,
            <a href="https://creativecommons.org/licenses/by/4.0/deed.ja" target="_blank" title="クリエイティブ・コモンズ・ライセンス 表示 4.0 国際">CC BY 4.0</a>
        </p>
    </div>
</footer>

</body>

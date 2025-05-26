@php use Illuminate\Support\Facades\View; @endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@if(View::hasSection('title')) @yield('title') - @endif{{ config('app.name') }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@1.0.4/css/bulma.min.css" crossorigin="anonymous">
    <style>
        :root {
            --bulma-primary-h: 87deg;
            --bulma-primary-s: 58%;
            --bulma-primary-l: 71%;
            --bulma-link-h: 190deg;
            --bulma-link-s: 38%;
            --bulma-link-l: 61%;
            --bulma-success-h: 87deg;
            --bulma-success-s: 58%;
            --bulma-success-l: 71%;
            --bulma-warning-h: 43deg;
            --bulma-warning-l: 70%;
            --bulma-danger-h: 352deg;
            --bulma-danger-s: 64%;
            --bulma-danger-l: 65%;
            --bulma-family-primary: 'IBM Plex Sans JP', sans-serif;
            --bulma-family-code: 'IBM Plex Mono',monospace;
        }

        body {
            min-height: 100vh;
        }
    </style>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;700&family=IBM+Plex+Sans+JP:wght@300;400;500;700&display=swap" rel="stylesheet">

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
    </div>
@endif

@yield('main')

<footer class="footer has-background-inherit">
    <div class="has-text-centered">
        <p>{{ config('app.name') }} - {{ $_SERVER['HTTP_HOST'] }}</p>
        <nav class="breadcrumb has-bullet-separator has-text-weight-medium is-centered mt-2">
            <ul>
                <li><a href="{{ url('/') }}" class="pl-3">Home</a></li>
                <li><a href="https://github.com/miyacorata/taglibro" target="_blank">GitHub</a></li>
            </ul>
        </nav>
        <p>
            <a href="https://bulma.io" target="_blank">
                <img
                    src="https://bulma.io/assets/images/made-with-bulma--semiblack.png"
                    alt="Made with Bulma"
                    width="170"
                    height="32">
            </a>
        </p>
    </div>
</footer>

</body>

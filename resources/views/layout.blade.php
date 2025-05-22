@php use Illuminate\Support\Facades\View; @endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@if(View::hasSection('title')) @yield('title') - @endif{{ config('app.name') }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@1.0.3/css/bulma.min.css"
          integrity="sha256-RwYNyYLkMTjyYn8FRzVzQFtHXuHg9dpfkPCuf6j2XDM=" crossorigin="anonymous">
    <style>
        :root {
            --bulma-primary-h: 200deg;
            --bulma-primary-l: 60%;
            --bulma-link-h: 212deg;
            --bulma-link-l: 50%;
            --bulma-info-h: 185deg;
            --bulma-info-l: 74%;
            --bulma-danger-h: 360deg;
            --bulma-family-primary: 'IBM Plex Sans JP', Segoe UI, Roboto, Ubuntu, Helvetica Neue, Helvetica, Meiryo, sans-serif;
        }

        body {
            min-height: 100vh;
        }
    </style>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+JP:wght@400;700&display=swap" rel="stylesheet">
    <script>
        document.addEventListener('DOMContentLoaded', () => {

            // Get all "navbar-burger" elements
            const $navbarBurgers = Array.prototype.slice.call(document.querySelectorAll('.navbar-burger'), 0);

            // Add a click event on each of them
            $navbarBurgers.forEach(el => {
                el.addEventListener('click', () => {

                    // Get the target from the "data-target" attribute
                    const target = el.dataset.target;
                    const $target = document.getElementById(target);

                    // Toggle the "is-active" class on both the "navbar-burger" and the "navbar-menu"
                    el.classList.toggle('is-active');
                    $target.classList.toggle('is-active');

                });
            });

        });
    </script>

    @yield('head')

</head>
<body class="has-navbar-fixed-top-desktop">

<nav class="navbar has-shadow is-fixed-top-desktop" role="navigation" aria-label="main navigation">
    <div class="navbar-brand">
        <a class="navbar-item" href="{{ url('/') }}">
            <img src="{{ asset('/images/MRapid-ID.png') }}" alt="MiyanoKokeshiStore">
        </a>

        <a role="button" class="navbar-burger" aria-label="menu" aria-expanded="false" data-target="navbarBasicExample">
            <span aria-hidden="true"></span>
            <span aria-hidden="true"></span>
            <span aria-hidden="true"></span>
            <span aria-hidden="true"></span>
        </a>
    </div>

    <div id="navbarBasicExample" class="navbar-menu">
        <div class="navbar-start">
            <!--<a href="{{ url('/') }}" class="navbar-item">
                Home
            </a>-->

        </div>

        <div class="navbar-end">
            <div class="navbar-item">
                <div class="buttons">
                    @auth
                        <a href="{{ route('dashboard') }}" class="button">
                            Dashboard
                        </a>
                        <a href="{{ route('logout') }}" class="button">
                            Logout
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="button">
                            Login
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</nav>

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
    <div class="content has-text-centered ">
        <p>{{ config('app.name') }} - {{ $_SERVER['HTTP_HOST'] }}</p>
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

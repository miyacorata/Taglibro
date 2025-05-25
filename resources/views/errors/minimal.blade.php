@extends('layout')

@section('main')
    <section class="hero is-medium">
        <div class="hero-body has-text-centered">
            <p class="title mb-2 has-text-danger">@yield('code') @yield('title', 'Error')</p>
            <p class="subtitle">@yield('message')</p>
            <a href="{{ url('/') }}" class="button">Return to Home</a>
        </div>
    </section>
@endsection

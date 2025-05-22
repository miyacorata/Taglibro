@extends('layout')

@section('main')
    <section class="hero is-medium is-danger">
        <div class="hero-body">
            <p class="title mb-2">@yield('code') @yield('title', 'Error')</p>
            <p class="subtitle">@yield('message')</p>
            <a href="{{ url('/') }}" class="button">Return to Home</a>
        </div>
    </section>
@endsection

@extends('layout')

@section('main')
    <section class="hero is-large is-light">
        <div class="hero-body">
            <p class="title mb-2">{{ config('app.name') }}</p>
            <p class="subtitle">{{ config('app.description', 'Cognito-SP Sample App') }}</p>
            @auth
                <a href="{{ route('dashboard') }}" class="button">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="button">Login</a>
            @endauth
        </div>
    </section>
@endsection

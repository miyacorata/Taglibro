@extends('layout')

@section('main')
    <section class="hero is-info is-small">
        <div class="hero-body px-6">
            <p class="title mb-2"><a href="{{ url('/') }}" class="has-text-black">{{ config('app.name') }}</a></p>
            <p class="subtitle is-6">{{ config('app.description', 'Cognito-SP Sample App') }}</p>
        </div>
    </section>
@endsection

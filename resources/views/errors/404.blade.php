@extends('layouts.app')

@section('title', 'Page Not Found - Toolexa')
@section('description', 'The page you are looking for could not be found. Browse free online tools on Toolexa.')

@section('content')
    <section class="tool-hero error-hero">
        <span class="eyebrow">404</span>
        <h1>Page Not Found</h1>
        <p>The page may have moved, or the URL may be incorrect. Search tools or go back home.</p>
        <div class="hero-actions">
            <a class="btn btn-primary" href="{{ url('/') }}">Go Home</a>
            <a class="btn btn-success" href="{{ route('search') }}">Search Tools</a>
        </div>
    </section>
@endsection

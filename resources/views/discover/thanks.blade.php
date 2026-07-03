@extends('layouts.app')

@section('title', 'Response Submitted - Toolexa Discover')
@section('description', 'Your anonymous Discover response was submitted.')

@section('content')
    <section class="result-box text-center">
        <span class="eyebrow">Submitted</span>
        <span class="result-value">Thanks</span>
        <p>Your anonymous response for {{ $entry['name'] }} has been saved.</p>
        <div class="result-actions" style="justify-content:center">
            <a class="btn btn-primary" href="{{ route('discover.feature.create', $feature->slug) }}">Create Your Own Link</a>
        </div>
    </section>
@endsection

@extends('layouts.app')

@section('title', 'Response Submitted - Toolexa Discover')
@section('description', 'Your anonymous Discover response was submitted.')

@section('content')
    <section class="result-box text-center">
        <span class="eyebrow">Submitted</span>
        <span class="result-value">Thanks</span>
        <p>Your anonymous response for {{ $entry['name'] }} has been saved.</p>
        <div class="result-actions" style="justify-content:center">
            @if($resultsUrl)
                <a class="btn btn-primary" href="{{ $resultsUrl }}">View Results Dashboard</a>
                <button class="btn" type="button" data-copy-text="{{ $resultsUrl }}">Copy Results Link</button>
            @endif
            <a class="btn btn-primary" href="{{ route('discover.feature.create', $feature->slug) }}">Create Your Own Link</a>
        </div>
        @unless($resultsUrl)
            <p class="tool-note">Results dashboard owner private link thi access thay che.</p>
        @endunless
    </section>
@endsection

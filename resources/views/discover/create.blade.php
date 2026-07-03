@extends('layouts.app')

@section('title', 'How People See You - Create Your Anonymous Friends Link')
@section('description', 'Create a private Discover link and ask friends to anonymously choose three words that describe you.')
@section('keywords', 'how people see you, anonymous friends words, social share link, discover toolexa')

@section('content')
    <section class="tool-hero discover-hero">
        <span class="eyebrow">Discover</span>
        <h1>{{ $feature->name }}</h1>
        <p>Create a private share link, send it to friends, and see the words people choose for you in a beautiful dashboard.</p>
        <div class="hero-actions">
            <a class="btn btn-primary" href="#create-discover" data-ga-event="create_link_click" data-ga-label="{{ $feature->slug }}">Generate My Link</a>
        </div>
    </section>

    <section class="tool-layout" id="create-discover">
        <div class="form-panel discover-panel">
            <form method="POST" action="{{ route('discover.store', $feature->slug) }}" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label for="discover-name">Your Name</label>
                    <input id="discover-name" class="form-control" type="text" name="name" maxlength="40" required value="{{ old('name') }}" placeholder="Rahul">
                </div>

                <div class="mb-3">
                    <label for="discover-photo">Profile Photo <span class="text-muted">(optional)</span></label>
                    <input id="discover-photo" class="form-control" type="file" name="photo" accept="image/jpeg,image/png,image/webp">
                </div>

                <div class="mb-3">
                    <label for="discover-theme">Theme</label>
                    <select id="discover-theme" class="form-control" name="theme" required>
                        <option value="light" @selected(old('theme', 'light') === 'light')>Light</option>
                        <option value="dark" @selected(old('theme') === 'dark')>Dark</option>
                        <option value="colorful" @selected(old('theme') === 'colorful')>Colorful</option>
                    </select>
                </div>

                <label class="checkbox-row" for="public-results">
                    <input id="public-results" type="checkbox" name="public_results" value="1" @checked(old('public_results'))>
                    <span>Allow friends to view results after submitting</span>
                </label>

                <button class="btn btn-primary w-100" type="submit" data-ga-event="create_link_click" data-ga-label="{{ $feature->slug }}">Generate My Link</button>
            </form>
        </div>

        <div class="result-box discover-preview">
            <span class="eyebrow">How it works</span>
            <div class="discover-step-grid">
                <div>
                    <strong>1</strong>
                    <span>Create your link</span>
                </div>
                <div>
                    <strong>2</strong>
                    <span>Friends choose three words</span>
                </div>
                <div>
                    <strong>3</strong>
                    <span>Open your results dashboard</span>
                </div>
            </div>
        </div>
    </section>

    <section class="info-panel home-section">
        <span class="eyebrow">Privacy</span>
        <h2>Private by design</h2>
        <p>Responses are stored in private JSON files inside Laravel storage and are never exposed directly. Friends do not need to log in.</p>
    </section>
@endsection

@extends('layouts.app')

@section('title', 'Discover Results for '.$entry['name'].' - '.$feature->name)
@section('description', 'Private Toolexa Discover results dashboard.')

@section('content')
    <section class="tool-hero discover-hero discover-theme-{{ $entry['theme'] ?? 'light' }}">
        <span class="eyebrow">Results dashboard</span>
        <h1>{{ $entry['name'] }}, this is how people see you</h1>
        <p>Anonymous responses from your share link, grouped into words, emojis, and messages.</p>
    </section>

    <section class="discover-dashboard" data-counter-scope>
        <div class="discover-stat-grid">
            <div class="discover-stat">
                <span>Total Responses</span>
                <strong data-counter="{{ $analytics['total_responses'] }}">0</strong>
            </div>
            <div class="discover-stat">
                <span>Average / Day</span>
                <strong data-counter="{{ $analytics['average_per_day'] }}">0</strong>
            </div>
            <div class="discover-stat">
                <span>Top Emoji</span>
                <strong>{{ $analytics['top_emoji'] ?: '-' }}</strong>
            </div>
        </div>

        <div class="discover-card-grid">
            <article class="form-panel discover-panel">
                <span class="eyebrow">Top Words</span>
                @forelse($analytics['top_words'] as $word => $count)
                    <div class="discover-word-rank">
                        <span>{{ $word }}</span>
                        <strong>{{ $count }}</strong>
                    </div>
                @empty
                    <p class="empty-state">No responses yet. Share your link to collect words.</p>
                @endforelse
            </article>

            <article class="form-panel discover-panel">
                <span class="eyebrow">Word Frequency</span>
                <div class="discover-bars">
                    @forelse($analytics['word_counts'] as $word => $count)
                        <div class="discover-bar-row">
                            <span>{{ $word }}</span>
                            <div><i style="width: {{ max(8, ($count / $analytics['max_word_count']) * 100) }}%"></i></div>
                            <strong>{{ $count }}</strong>
                        </div>
                    @empty
                        <p class="empty-state">Word frequency will appear after responses arrive.</p>
                    @endforelse
                </div>
            </article>

            <article class="form-panel discover-panel">
                <span class="eyebrow">Emoji Mood</span>
                <div class="discover-emoji-stats">
                    @forelse($analytics['emoji_counts'] as $emoji => $count)
                        <div>
                            <span>{{ $emoji }}</span>
                            <strong>{{ $count }}</strong>
                        </div>
                    @empty
                        <p class="empty-state">No emojis yet.</p>
                    @endforelse
                </div>
            </article>

            <article class="form-panel discover-panel">
                <span class="eyebrow">Latest Messages</span>
                <div class="discover-message-list">
                    @forelse($analytics['latest_messages'] as $message)
                        <blockquote>
                            @if($message['emoji'])
                                <span>{{ $message['emoji'] }}</span>
                            @endif
                            {{ $message['message'] }}
                        </blockquote>
                    @empty
                        <p class="empty-state">Anonymous messages will appear here.</p>
                    @endforelse
                </div>
            </article>
        </div>

        <div class="tool-actions">
            <input id="discover-results-share-url" class="copy-source" type="text" readonly value="{{ $shareUrl }}">
            <button class="btn btn-primary" type="button" data-copy-target="discover-results-share-url">Copy Link</button>
            <button class="btn" type="button" data-native-share data-share-url="{{ $shareUrl }}" data-share-text="{{ $feature->promptFor($entry['name']) }}">Share Again</button>
            <span class="copy-status" data-copy-status></span>
        </div>
    </section>
@endsection

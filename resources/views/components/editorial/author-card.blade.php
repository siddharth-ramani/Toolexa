@props(['author'])

<section class="info-panel editorial-author-card" aria-labelledby="author-card-heading">
    <img src="{{ asset($author['photo']) }}" alt="{{ $author['name'] }}" width="88" height="88" loading="lazy" decoding="async">
    <div>
        <span class="eyebrow">About the author</span>
        <h2 id="author-card-heading">{{ $author['name'] }}</h2>
        <strong>{{ $author['role'] }}</strong>
        <p>{{ $author['bio'] }}</p>
        @if(!empty($author['expertise']))
            <p class="editorial-author-expertise"><b>Areas of Expertise:</b> {{ collect($author['expertise'])->take(4)->join(', ') }}</p>
        @endif
        <a class="btn btn-sm" href="{{ route('authors.show', $author['slug']) }}">View Profile</a>
    </div>
</section>

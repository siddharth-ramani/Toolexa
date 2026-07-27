@props(['metadata', 'type' => 'article', 'category' => null])

<div class="editorial-metadata" aria-label="Content details">
    @if($type === 'tool')
        <span>Verified by <a href="{{ route('authors.show', $metadata['author']['slug']) }}">{{ $metadata['author']['name'] }}</a></span>
    @else
        <span>Written by <a href="{{ route('authors.show', $metadata['author']['slug']) }}">{{ $metadata['author']['name'] }}</a></span>
        <span>Reviewed by <strong>{{ $metadata['reviewer']['name'] }}</strong></span>
    @endif
    @if(!empty($metadata['published_at']))
        <span>Published <time datetime="{{ $metadata['published_at'] }}">{{ \Carbon\Carbon::parse($metadata['published_at'])->format('F d, Y') }}</time></span>
    @endif
    <x-editorial.last-updated :date="$metadata['updated_at']" />
    @if($category)<span>{{ $category }}</span>@endif
    <x-editorial.reading-time :minutes="$metadata['reading_time']" />
</div>

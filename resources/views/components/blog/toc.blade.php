@props(['items'])

<aside class="blog-toc info-panel" aria-label="Table of contents">
    <details open>
        <summary><span class="eyebrow">Table of Contents</span><span aria-hidden="true">⌄</span></summary>
        <ol>
            @foreach($items as $item)
                <li><a href="#{{ $item['id'] }}">{{ $item['title'] }}</a></li>
            @endforeach
        </ol>
    </details>
</aside>

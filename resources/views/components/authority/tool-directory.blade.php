@props(['title', 'tools', 'action', 'query' => '', 'sort' => 'featured', 'filter' => 'all'])

<section id="authority-tools" class="info-panel category-tools-panel" aria-labelledby="authority-tools-heading">
    <div class="section-head category-tools-head">
        <div><span class="eyebrow">Complete directory</span><h2 id="authority-tools-heading">All {{ $title }}</h2></div>
    </div>
    <form class="authority-tool-controls" method="GET" action="{{ $action }}" role="search" aria-label="Search, filter and sort tools">
        <label><span>Search</span><input class="form-control" type="search" name="q" value="{{ $query }}" placeholder="Search within {{ strtolower($title) }}"></label>
        <label><span>Sort</span><select class="form-control" name="sort"><option value="featured" @selected($sort === 'featured')>Featured order</option><option value="name" @selected($sort === 'name')>Name A–Z</option><option value="recent" @selected($sort === 'recent')>Recently added</option></select></label>
        <label><span>Filter</span><select class="form-control" name="filter"><option value="all" @selected($filter === 'all')>All tools</option><option value="featured" @selected($filter === 'featured')>Featured only</option></select></label>
        <button class="btn btn-primary" type="submit">Apply</button>
        @if($query !== '' || $sort !== 'featured' || $filter !== 'all')<a class="btn" href="{{ $action }}">Reset</a>@endif
    </form>
    @if($tools->count())
        <div class="category-tool-grid">@foreach($tools as $tool)<x-category-tool-card :tool="$tool" />@endforeach</div>
        {{ $tools->links('partials.pagination') }}
    @else
        <div class="category-empty-state"><h3>No tools found</h3><p>Adjust the search or filter to see more tools.</p><a class="btn btn-primary" href="{{ $action }}">View all tools</a></div>
    @endif
</section>

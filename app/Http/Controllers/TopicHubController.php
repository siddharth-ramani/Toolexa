<?php

namespace App\Http\Controllers;

use App\Services\TopicHubService;
use App\Services\EditorialService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class TopicHubController extends Controller
{
    public function index(TopicHubService $hubs)
    {
        $topics = $hubs->all();
        $url = route('hub.index');

        return view('hubs.index', [
            'hubs' => $topics,
            'breadcrumbs' => [['name' => 'Home', 'url' => url('/')], ['name' => 'Topic Hubs', 'url' => route('hub.index')]],
            'canonicalUrl' => route('hub.index'),
            'seoTitle' => 'Toolexa Topic Hubs: Tools, Guides and Comparisons',
            'seoDescription' => 'Explore comprehensive Toolexa topic hubs with free tools, beginner guides, comparisons, articles, best practices and glossaries.',
            'seoKeywords' => 'Toolexa topic hubs, free tools guides, tool collections',
            'schemaJsonLd' => [
                ['@context' => 'https://schema.org', '@type' => 'CollectionPage', 'name' => 'Toolexa Topic Hubs', 'description' => 'Authority guides connecting free tools, articles, comparisons and practical education.', 'url' => $url],
                ['@context' => 'https://schema.org', '@type' => 'ItemList', 'name' => 'Toolexa Topic Hubs', 'numberOfItems' => count($topics), 'itemListElement' => collect($topics)->values()->map(fn (array $topic, int $index) => ['@type' => 'ListItem', 'position' => $index + 1, 'name' => $topic['title'], 'url' => route('hub.show', $topic['slug'])])->all()],
                ['@context' => 'https://schema.org', '@type' => 'BreadcrumbList', 'itemListElement' => [
                    ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => url('/')],
                    ['@type' => 'ListItem', 'position' => 2, 'name' => 'Topic Hubs', 'item' => $url],
                ]],
                ['@context' => 'https://schema.org', '@type' => 'Organization', 'name' => 'Toolexa', 'url' => url('/')],
            ],
        ]);
    }

    public function show(Request $request, string $hub, TopicHubService $hubs, EditorialService $editorial)
    {
        $topic = $hubs->find($hub);
        abort_unless($topic, 404);
        $page = max(1, (int) $request->query('page', 1));
        $query = trim((string) $request->query('q', ''));
        $sort = (string) $request->query('sort', 'featured');
        $filter = (string) $request->query('filter', 'all');
        $toolCollection = collect($topic['tools']);
        if ($query !== '') {
            $needle = mb_strtolower($query);
            $toolCollection = $toolCollection->filter(fn (array $tool) => str_contains(mb_strtolower($tool['name'].' '.$tool['desc'].' '.($tool['keywords'] ?? '')), $needle));
        }
        if ($filter === 'featured') {
            $featuredSlugs = array_column($topic['featured_tools'], 'slug');
            $toolCollection = $toolCollection->whereIn('slug', $featuredSlugs);
        }
        $toolCollection = match ($sort) {
            'name' => $toolCollection->sortBy('name'),
            'recent' => $toolCollection->reverse(),
            default => $toolCollection,
        };
        $perPage = 18;
        $filteredTools = $toolCollection->values()->all();
        $tools = new LengthAwarePaginator(array_slice($filteredTools, ($page - 1) * $perPage, $perPage), count($filteredTools), $perPage, $page, [
            'path' => $request->url(), 'query' => $request->query(), 'pageName' => 'page', 'fragment' => 'all-topic-tools',
        ]);
        $canonicalUrl = route('hub.show', $hub).($page > 1 ? '?page='.$page : '');
        $topic['reading_time'] = $topic['authority']['reading_time'];
        $topic['updated_at'] = $topic['authority']['last_updated'];
        $editorialMeta = $editorial->metadata('topics', $topic);

        return view('hubs.show', [
            'topic' => $topic,
            'tools' => $tools,
            'query' => $query,
            'sort' => $sort,
            'filter' => $filter,
            'editorialMeta' => $editorialMeta,
            'breadcrumbs' => [
                ['name' => 'Home', 'url' => url('/')], ['name' => 'Topic Hubs', 'url' => route('hub.index')], ['name' => $topic['title'], 'url' => route('hub.show', $hub)],
            ],
            'canonicalUrl' => $canonicalUrl,
            'seoTitle' => $topic['meta_title'],
            'seoDescription' => $topic['meta_description'],
            'seoKeywords' => strtolower($topic['title']).', free tools, beginner guide, comparisons, best practices',
            'robotsMeta' => $query !== '' || $sort !== 'featured' || $filter !== 'all' ? 'noindex, follow' : 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1',
            'schemaJsonLd' => $topic['schema'],
        ]);
    }
}

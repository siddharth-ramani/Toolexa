<?php

namespace App\Http\Controllers;

use App\Services\TopicHubService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class TopicHubController extends Controller
{
    public function index(TopicHubService $hubs)
    {
        return view('hubs.index', [
            'hubs' => $hubs->all(),
            'breadcrumbs' => [['name' => 'Home', 'url' => url('/')], ['name' => 'Topic Hubs', 'url' => route('hub.index')]],
            'canonicalUrl' => route('hub.index'),
            'seoTitle' => 'Toolexa Topic Hubs: Tools, Guides and Comparisons',
            'seoDescription' => 'Explore comprehensive Toolexa topic hubs with free tools, beginner guides, comparisons, articles, best practices and glossaries.',
            'seoKeywords' => 'Toolexa topic hubs, free tools guides, tool collections',
        ]);
    }

    public function show(Request $request, string $hub, TopicHubService $hubs)
    {
        $topic = $hubs->find($hub);
        abort_unless($topic, 404);
        $page = max(1, (int) $request->query('page', 1));
        $perPage = 18;
        $tools = new LengthAwarePaginator(array_slice($topic['tools'], ($page - 1) * $perPage, $perPage), count($topic['tools']), $perPage, $page, [
            'path' => $request->url(), 'query' => $request->query(), 'pageName' => 'page', 'fragment' => 'all-topic-tools',
        ]);
        $canonicalUrl = route('hub.show', $hub).($page > 1 ? '?page='.$page : '');

        return view('hubs.show', [
            'topic' => $topic,
            'tools' => $tools,
            'breadcrumbs' => [
                ['name' => 'Home', 'url' => url('/')], ['name' => 'Topic Hubs', 'url' => route('hub.index')], ['name' => $topic['title'], 'url' => route('hub.show', $hub)],
            ],
            'canonicalUrl' => $canonicalUrl,
            'seoTitle' => $topic['meta_title'],
            'seoDescription' => $topic['meta_description'],
            'seoKeywords' => strtolower($topic['title']).', free tools, beginner guide, comparisons, best practices',
            'schemaJsonLd' => $topic['schema'],
        ]);
    }
}

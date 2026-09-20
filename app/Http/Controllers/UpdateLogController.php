<?php

namespace App\Http\Controllers;

class UpdateLogController extends Controller
{
    public function index()
    {
        $updates = collect(config('updates.entries', []))
            ->sortByDesc('date')
            ->values();
        $canonicalUrl = route('updates.index');
        $breadcrumbs = [
            ['name' => 'Home', 'url' => url('/')],
            ['name' => 'Site Updates', 'url' => $canonicalUrl],
        ];

        return view('updates.index', [
            'updates' => $updates,
            'canonicalUrl' => $canonicalUrl,
            'seoTitle' => 'Toolexa Site Updates | Product and Content Change Log',
            'seoDescription' => 'See dated Toolexa tool, content, quality and maintenance updates, including what changed and why it helps users.',
            'seoKeywords' => 'Toolexa updates, tool updates, content review, website change log',
            'breadcrumbs' => $breadcrumbs,
            'schemaJsonLd' => [
                [
                    '@context' => 'https://schema.org',
                    '@type' => 'CollectionPage',
                    'name' => 'Toolexa Site Updates',
                    'description' => 'A dated record of meaningful Toolexa product, content and quality updates.',
                    'url' => $canonicalUrl,
                    'dateModified' => $updates->first()['date'] ?? now()->toDateString(),
                    'mainEntity' => [
                        '@type' => 'ItemList',
                        'itemListElement' => $updates->map(fn (array $update, int $index) => [
                            '@type' => 'ListItem',
                            'position' => $index + 1,
                            'name' => $update['title'],
                            'description' => $update['summary'],
                        ])->all(),
                    ],
                ],
                [
                    '@context' => 'https://schema.org',
                    '@type' => 'BreadcrumbList',
                    'itemListElement' => collect($breadcrumbs)->map(fn (array $item, int $index) => [
                        '@type' => 'ListItem',
                        'position' => $index + 1,
                        'name' => $item['name'],
                        'item' => $item['url'],
                    ])->all(),
                ],
            ],
        ]);
    }
}

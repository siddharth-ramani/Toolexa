<?php

namespace App\Http\Controllers;

use App\Services\EditorialService;

class AuthorController extends Controller
{
    public function show(string $slug, EditorialService $editorial)
    {
        $content = $editorial->authorContent($slug);
        $author = $content['author'];
        $canonicalUrl = route('authors.show', $slug);
        $breadcrumbs = [
            ['name' => 'Home', 'url' => url('/')],
            ['name' => 'Authors', 'url' => $canonicalUrl],
            ['name' => $author['name'], 'url' => $canonicalUrl],
        ];

        return view('authors.show', array_merge($content, [
            'canonicalUrl' => $canonicalUrl,
            'seoTitle' => $author['name'].' | Authors at Toolexa',
            'seoDescription' => $author['bio'].' Learn about the team’s mission, expertise and recently reviewed Toolexa content.',
            'seoKeywords' => $author['name'].', Toolexa author, editorial team, online tools experts',
            'breadcrumbs' => $breadcrumbs,
            'schemaJsonLd' => [
                [
                    '@context' => 'https://schema.org',
                    '@type' => 'ProfilePage',
                    'name' => $author['name'],
                    'url' => $canonicalUrl,
                    'mainEntity' => $editorial->personSchema($author),
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
        ]));
    }
}

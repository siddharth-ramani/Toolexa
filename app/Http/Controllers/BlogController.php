<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Tools\HomeController;
use App\Support\BlogRepository;
use App\Services\InternalLinkingService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $articles = collect(BlogRepository::all());
        $paginatedArticles = $this->paginate($articles->all(), 6, $request);

        return view('blog.index', [
            'articles' => $paginatedArticles,
            'breadcrumbs' => [
                ['name' => 'Home', 'url' => url('/')],
                ['name' => 'Blog', 'url' => route('blog.index')],
            ],
            'canonicalUrl' => route('blog.index'),
            'seoTitle' => 'Toolexa Blog - Guides for Calculators, Tools and Productivity',
            'seoDescription' => 'Read practical Toolexa guides about finance calculators, image tools, developer utilities, security and student productivity.',
            'seoKeywords' => 'Toolexa blog, calculator guides, online tools guides, finance calculators',
            'schemaJsonLd' => [
                '@context' => 'https://schema.org',
                '@type' => 'Blog',
                'name' => 'Toolexa Blog',
                'url' => route('blog.index'),
                'description' => 'Practical guides for online calculators, converters and productivity tools.',
            ],
        ]);
    }

    public function show(string $slug, InternalLinkingService $linking)
    {
        $article = BlogRepository::find($slug);
        abort_unless($article, 404);

        $relatedTools = $linking->relatedToolsForArticle($article);
        $relatedArticles = $linking->relatedArticlesForArticle($article);
        $adjacent = BlogRepository::adjacent($slug);
        $toc = BlogRepository::tableOfContents($article);
        $canonicalUrl = route('blog.show', $article['slug']);
        $publishedDate = $article['published_at'];

        return view('blog.show', [
            'article' => $article,
            'toc' => $toc,
            'relatedTools' => $relatedTools,
            'relatedArticles' => $relatedArticles,
            'previousArticle' => $adjacent['previous'],
            'nextArticle' => $adjacent['next'],
            'breadcrumbs' => [
                ['name' => 'Home', 'url' => url('/')],
                ['name' => 'Blog', 'url' => route('blog.index')],
                ['name' => $article['title'], 'url' => $canonicalUrl],
            ],
            'canonicalUrl' => $canonicalUrl,
            'seoTitle' => $article['meta_title'],
            'seoDescription' => $article['meta_description'],
            'seoKeywords' => Str::lower($article['title']).', '.$article['category'].', Toolexa guide',
            'schemaJsonLd' => [
                [
                    '@context' => 'https://schema.org',
                    '@type' => 'Article',
                    'headline' => $article['title'],
                    'description' => $article['meta_description'],
                    'url' => $canonicalUrl,
                    'datePublished' => $publishedDate,
                    'dateModified' => $publishedDate,
                    'author' => [
                        '@type' => 'Organization',
                        'name' => $article['author'],
                    ],
                    'publisher' => [
                        '@type' => 'Organization',
                        'name' => 'Toolexa',
                        'url' => url('/'),
                    ],
                    'mainEntityOfPage' => [
                        '@type' => 'WebPage',
                        '@id' => $canonicalUrl,
                    ],
                ],
                [
                    '@context' => 'https://schema.org',
                    '@type' => 'BreadcrumbList',
                    'itemListElement' => [
                        [
                            '@type' => 'ListItem',
                            'position' => 1,
                            'name' => 'Home',
                            'item' => url('/'),
                        ],
                        [
                            '@type' => 'ListItem',
                            'position' => 2,
                            'name' => 'Blog',
                            'item' => route('blog.index'),
                        ],
                        [
                            '@type' => 'ListItem',
                            'position' => 3,
                            'name' => $article['title'],
                            'item' => $canonicalUrl,
                        ],
                    ],
                ],
                [
                    '@context' => 'https://schema.org',
                    '@type' => 'FAQPage',
                    'mainEntity' => collect($article['faqs'])->map(fn (array $faq) => [
                        '@type' => 'Question',
                        'name' => $faq['question'],
                        'acceptedAnswer' => [
                            '@type' => 'Answer',
                            'text' => $faq['answer'],
                        ],
                    ])->all(),
                ],
            ],
        ]);
    }

    private function paginate(array $items, int $perPage, Request $request): LengthAwarePaginator
    {
        $page = max(1, (int) $request->query('page', 1));
        $offset = ($page - 1) * $perPage;

        return new LengthAwarePaginator(
            array_slice($items, $offset, $perPage),
            count($items),
            $perPage,
            $page,
            [
                'path' => $request->url(),
                'query' => $request->query(),
                'pageName' => 'page',
            ]
        );
    }
}

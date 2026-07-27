<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Tools\HomeController;
use App\Support\BlogRepository;
use App\Services\InternalLinkingService;
use App\Services\EditorialService;
use App\Services\BlogQualityService;
use App\Services\ComparisonService;
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

    public function show(
        string $slug,
        InternalLinkingService $linking,
        EditorialService $editorial,
        BlogQualityService $quality,
        ComparisonService $comparisons
    )
    {
        $article = BlogRepository::find($slug);
        abort_unless($article, 404);

        $relatedTools = $linking->relatedToolsForArticle($article);
        $relatedArticles = $linking->relatedArticlesForArticle($article);
        $adjacent = BlogRepository::adjacent($slug);
        $qualityContent = $quality->build($article);
        $article['reading_time'] = $qualityContent['reading_time'];
        $toc = $this->qualityTableOfContents($qualityContent);
        $relatedComparisons = $this->relatedComparisons($article, $comparisons->all());
        $canonicalUrl = route('blog.show', $article['slug']);
        $publishedDate = $article['published_at'];
        $editorialMeta = $editorial->metadata('articles', $article);

        return view('blog.show', [
            'article' => $article,
            'editorialMeta' => $editorialMeta,
            'qualityContent' => $qualityContent,
            'toc' => $toc,
            'relatedTools' => $relatedTools,
            'relatedArticles' => $relatedArticles,
            'relatedComparisons' => $relatedComparisons,
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
                    'dateModified' => $editorialMeta['updated_at'],
                    'author' => $editorial->personSchema($editorialMeta['author']),
                    'reviewedBy' => $editorial->reviewerSchema($editorialMeta['reviewer']),
                    'wordCount' => str_word_count(strip_tags(json_encode($qualityContent))),
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
                    'mainEntity' => collect($qualityContent['faqs'])->map(fn (array $faq) => [
                        '@type' => 'Question',
                        'name' => $faq['question'],
                        'acceptedAnswer' => [
                            '@type' => 'Answer',
                            'text' => $faq['answer'],
                        ],
                    ])->all(),
                ],
                array_merge(['@context' => 'https://schema.org'], $editorial->personSchema($editorialMeta['author'])),
                [
                    '@context' => 'https://schema.org',
                    '@type' => 'Organization',
                    'name' => 'Toolexa',
                    'url' => url('/'),
                    'logo' => asset('assets/images/favicon.png'),
                ],
            ],
        ]);
    }

    private function qualityTableOfContents(array $content): array
    {
        $fixed = [
            ['id' => 'introduction', 'title' => 'Introduction'],
            ['id' => 'step-by-step-guide', 'title' => 'Step-by-Step Guide'],
        ];
        $sections = collect($content['sections'])->map(fn (array $section) => [
            'id' => Str::slug($section['heading']),
            'title' => $section['heading'],
        ])->all();
        $ending = [
            ['id' => 'common-mistakes', 'title' => 'Common Mistakes'],
            ['id' => 'faq', 'title' => 'Frequently Asked Questions'],
        ];
        if (count($content['references'])) {
            $ending[] = ['id' => 'official-references', 'title' => 'Official References'];
        }

        return collect(array_merge($fixed, $sections, $ending))->unique('id')->values()->all();
    }

    private function relatedComparisons(array $article, array $comparisons): array
    {
        $terms = collect(preg_split('/[^\pL\pN]+/u', Str::lower($article['title'].' '.$article['slug'])) ?: [])
            ->filter(fn (string $term) => mb_strlen($term) > 2)
            ->all();

        return collect($comparisons)->map(function (array $comparison) use ($terms) {
            $haystack = Str::lower($comparison['title'].' '.$comparison['slug'].' '.$comparison['left']['category'].' '.$comparison['right']['category']);
            $comparison['_score'] = collect($terms)->filter(fn (string $term) => str_contains($haystack, $term))->count();

            return $comparison;
        })->filter(fn (array $comparison) => $comparison['_score'] > 0)
            ->sortByDesc('_score')
            ->take(4)
            ->map(function (array $comparison) {
                unset($comparison['_score']);

                return $comparison;
            })->values()->all();
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

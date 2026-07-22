<?php

namespace App\Services;

use App\Http\Controllers\Tools\HomeController;
use App\Support\BlogRepository;

class HomepageService
{
    public function data(): array
    {
        $tools = HomeController::tools();
        $articles = collect(BlogRepository::all())->sortByDesc('published_at')->values()->all();
        $categories = collect(HomeController::categories())->keyBy('slug');
        $featuredCategories = collect(config('homepage.featured_categories', []))
            ->map(function (array $editorial, string $slug) use ($categories) {
                $category = $categories->get($slug);
                if (! $category) {
                    return null;
                }

                return array_merge($category, $editorial);
            })->filter()->values()->all();
        $collections = collect(config('homepage.collections', []))
            ->filter(fn (array $collection) => $categories->has($collection['category']))
            ->map(function (array $collection) use ($categories) {
                $collection['count'] = $categories->get($collection['category'])['count'];

                return $collection;
            })->values()->all();
        $trendingTools = $this->configuredTools(config('homepage.trending_tools', []), $tools, 8);
        $editorPicks = $this->configuredTools(config('homepage.editor_picks', []), $tools, 6);
        $faqs = $this->faqs();

        return [
            'tools' => $tools,
            'toolCount' => count($tools),
            'articleCount' => count($articles),
            'categoryCount' => $categories->count(),
            'featuredCategories' => $featuredCategories,
            'trendingTools' => $trendingTools,
            'recentTools' => array_slice(array_reverse($tools), 0, 8),
            'featuredArticles' => array_slice($articles, 0, 6),
            'editorPicks' => $editorPicks,
            'collections' => $collections,
            'popularSearches' => app(IntelligentSearchService::class)->trendingSearches(),
            'faqs' => $faqs,
            'schemaJsonLd' => $this->schema($trendingTools, $faqs),
        ];
    }

    private function configuredTools(array $slugs, array $tools, int $limit): array
    {
        $toolsBySlug = collect($tools)->keyBy('slug');
        $selected = collect($slugs)->map(fn (string $slug) => $toolsBySlug->get($slug))->filter()->unique('slug');

        if ($selected->count() < $limit) {
            $selected = $selected->concat(collect($tools)->reject(fn (array $tool) => $selected->contains('slug', $tool['slug'])));
        }

        return $selected->take($limit)->values()->all();
    }

    private function faqs(): array
    {
        return [
            ['question' => 'Are Toolexa tools completely free?', 'answer' => 'Yes. Toolexa tools are free to use in your browser without a subscription.'],
            ['question' => 'Do I need to register for an account?', 'answer' => 'No. You can open and use tools without creating an account or signing in.'],
            ['question' => 'Can I use Toolexa on my phone?', 'answer' => 'Yes. The website and tool interfaces are responsive across phones, tablets and desktop browsers.'],
            ['question' => 'Does Toolexa store the information I enter?', 'answer' => 'Many compatible tools process information locally in the browser. Review the individual tool page before entering sensitive information.'],
            ['question' => 'How can I find the right tool?', 'answer' => 'Use intelligent search, browse a featured category or open one of the curated collections on this page.'],
            ['question' => 'How often are new tools and guides added?', 'answer' => 'The catalog is expanded regularly, and new tools and articles automatically appear in the relevant homepage sections.'],
            ['question' => 'Are calculator results professional advice?', 'answer' => 'No. Calculator results are informational estimates and important financial, legal or professional decisions should be independently verified.'],
            ['question' => 'Can I suggest a new tool?', 'answer' => 'Yes. Use the Toolexa contact page to share a tool request, correction or product suggestion.'],
        ];
    }

    private function schema(array $tools, array $faqs): array
    {
        return [
            [
                '@context' => 'https://schema.org',
                '@type' => 'CollectionPage',
                'name' => 'Toolexa Free Online Tools and Calculators',
                'description' => 'Free online calculators, PDF, image, text, developer, SEO and everyday utility tools.',
                'url' => url('/'),
                'mainEntity' => [
                    '@type' => 'ItemList',
                    'itemListElement' => collect($tools)->map(fn (array $tool, int $index) => [
                        '@type' => 'ListItem', 'position' => $index + 1, 'name' => $tool['name'], 'url' => url('tools/'.$tool['slug']),
                    ])->all(),
                ],
            ],
            [
                '@context' => 'https://schema.org',
                '@type' => 'BreadcrumbList',
                'itemListElement' => [['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => url('/')]],
            ],
            [
                '@context' => 'https://schema.org',
                '@type' => 'FAQPage',
                'mainEntity' => collect($faqs)->map(fn (array $faq) => [
                    '@type' => 'Question', 'name' => $faq['question'], 'acceptedAnswer' => ['@type' => 'Answer', 'text' => $faq['answer']],
                ])->all(),
            ],
        ];
    }
}

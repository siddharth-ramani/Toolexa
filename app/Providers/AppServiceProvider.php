<?php

namespace App\Providers;

use App\Http\Controllers\Tools\HomeController;
use App\Services\InternalLinkingService;
use App\Services\EditorialService;
use App\Services\ToolPageQualityService;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('tools.*', function ($view) {
            $viewSlugMap = collect(HomeController::tools())
                ->mapWithKeys(fn ($tool) => ['tools.'.$tool['view'] => $tool['slug']])
                ->all();
            $routeSlugViews = ['tools.finance-calculator', 'tools.text-utility', 'tools.image-utility', 'tools.browser-utility', 'tools.developer-utility', 'tools.local-utility', 'tools.pdf-utility', 'tools.seller-label', 'tools.advanced-browser-tool'];
            $viewData = $view->getData();
            $slug = in_array($view->getName(), $routeSlugViews, true)
                ? (request()->route('slug') ?: ($viewData['slug'] ?? null))
                : ($viewSlugMap[$view->getName()] ?? null);
            $tool = $slug ? HomeController::toolBySlug($slug) : null;

            if (! $tool) {
                return;
            }

            $linking = app(InternalLinkingService::class);
            $popularTools = HomeController::toolsBySlugs(HomeController::popularSlugs());
            $relatedTools = $linking->relatedToolsForTool($tool);
            $relatedArticles = $linking->relatedArticlesForTool($tool);
            $recentTools = HomeController::recentTools();
            $toolQuality = app(ToolPageQualityService::class)->build($tool);
            $editorialMeta = app(EditorialService::class)->metadata('tools', array_merge($tool, $toolQuality));
            $breadcrumbs = [
                ['name' => 'Home', 'url' => url('/')],
                ['name' => $tool['category'], 'url' => url('/').'#'.\Illuminate\Support\Str::slug($tool['category'])],
                ['name' => $tool['name'], 'url' => url('tools/'.$tool['slug'])],
            ];

            $schema = $this->toolSchema($tool, $breadcrumbs, $editorialMeta, $toolQuality);

            $view->with([
                'toolMeta' => $tool,
                'seoTitle' => $tool['seo_title'],
                'seoDescription' => $tool['seo_description'],
                'seoKeywords' => $tool['keywords'],
                'popularTools' => $popularTools,
                'relatedTools' => $relatedTools,
                'relatedArticles' => $relatedArticles,
                'recentTools' => $recentTools,
                'breadcrumbs' => $breadcrumbs,
                'schemaJsonLd' => $schema,
                'editorialMeta' => $editorialMeta,
                'toolQuality' => $toolQuality,
            ]);
        });
    }

    private function toolSchema(array $tool, array $breadcrumbs, array $editorialMeta, array $toolQuality): array
    {
        $editorial = app(EditorialService::class);
        $schemas = [
            [
                '@context' => 'https://schema.org',
                '@type' => 'SoftwareApplication',
                'name' => $tool['name'],
                'description' => $tool['seo_description'],
                'applicationCategory' => $tool['category'].'Application',
                'operatingSystem' => 'Any',
                'url' => url('tools/'.$tool['slug']),
                'author' => $editorial->personSchema($editorialMeta['author']),
                'reviewedBy' => $editorial->reviewerSchema($editorialMeta['reviewer']),
                'dateModified' => $editorialMeta['updated_at'],
                'offers' => [
                    '@type' => 'Offer',
                    'price' => '0',
                    'priceCurrency' => 'INR',
                ],
            ],
            [
                '@context' => 'https://schema.org',
                '@type' => 'BreadcrumbList',
                'itemListElement' => array_map(function ($item, $index) {
                    return [
                        '@type' => 'ListItem',
                        'position' => $index + 1,
                        'name' => $item['name'],
                        'item' => $item['url'],
                    ];
                }, $breadcrumbs, array_keys($breadcrumbs)),
            ],
            [
                '@context' => 'https://schema.org',
                '@type' => 'HowTo',
                'name' => 'How to use '.$tool['name'],
                'step' => array_map(function ($step, $index) {
                    return [
                        '@type' => 'HowToStep',
                        'position' => $index + 1,
                        'text' => $step,
                    ];
                }, $toolQuality['steps'], array_keys($toolQuality['steps'])),
            ],
            [
                '@context' => 'https://schema.org',
                '@type' => 'FAQPage',
                'mainEntity' => array_map(function ($faq) {
                    return [
                        '@type' => 'Question',
                        'name' => $faq['question'],
                        'acceptedAnswer' => [
                            '@type' => 'Answer',
                            'text' => $faq['answer'],
                        ],
                    ];
                }, $toolQuality['faqs']),
            ],
        ];

        return [
            '@context' => 'https://schema.org',
            '@graph' => $schemas,
        ];
    }
}

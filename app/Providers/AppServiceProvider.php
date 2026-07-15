<?php

namespace App\Providers;

use App\Http\Controllers\Tools\HomeController;
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

            $popularTools = HomeController::toolsBySlugs(HomeController::popularSlugs());
            $relatedTools = HomeController::toolsBySlugs($tool['related'] ?? []);
            $recentTools = HomeController::recentTools();
            $breadcrumbs = [
                ['name' => 'Home', 'url' => url('/')],
                ['name' => $tool['category'], 'url' => url('/').'#'.\Illuminate\Support\Str::slug($tool['category'])],
                ['name' => $tool['name'], 'url' => url('tools/'.$tool['slug'])],
            ];

            $schema = $this->toolSchema($tool, $breadcrumbs);

            $view->with([
                'toolMeta' => $tool,
                'seoTitle' => $tool['seo_title'],
                'seoDescription' => $tool['seo_description'],
                'seoKeywords' => $tool['keywords'],
                'popularTools' => $popularTools,
                'relatedTools' => $relatedTools,
                'recentTools' => $recentTools,
                'breadcrumbs' => $breadcrumbs,
                'schemaJsonLd' => $schema,
            ]);
        });
    }

    private function toolSchema(array $tool, array $breadcrumbs): array
    {
        $schemas = [
            [
                '@context' => 'https://schema.org',
                '@type' => 'WebApplication',
                'name' => $tool['name'],
                'description' => $tool['seo_description'],
                'applicationCategory' => $tool['category'].'Application',
                'operatingSystem' => 'Any',
                'url' => url('tools/'.$tool['slug']),
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
                }, $tool['how_to'], array_keys($tool['how_to'])),
            ],
            [
                '@context' => 'https://schema.org',
                '@type' => 'FAQPage',
                'mainEntity' => array_map(function ($faq) {
                    return [
                        '@type' => 'Question',
                        'name' => $faq['q'],
                        'acceptedAnswer' => [
                            '@type' => 'Answer',
                            'text' => $faq['a'],
                        ],
                    ];
                }, $tool['faq']),
            ],
        ];

        return [
            '@context' => 'https://schema.org',
            '@graph' => $schemas,
        ];
    }
}

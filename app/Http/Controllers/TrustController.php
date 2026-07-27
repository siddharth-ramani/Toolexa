<?php

namespace App\Http\Controllers;

class TrustController extends Controller
{
    public function show(string $page = 'trust')
    {
        $pages = config('trust.pages', []);
        abort_unless(isset($pages[$page]), 404);

        $content = $pages[$page];
        $canonicalUrl = route('trust.'.$page);
        $breadcrumbs = [
            ['name' => 'Home', 'url' => url('/')],
            ['name' => $content['name'], 'url' => $canonicalUrl],
        ];

        return view('trust.page', [
            'trustPage' => $content,
            'trustSlug' => $page,
            'trustPages' => $pages,
            'canonicalUrl' => $canonicalUrl,
            'seoTitle' => $content['meta_title'],
            'seoDescription' => $content['meta_description'],
            'seoKeywords' => $content['keywords'],
            'breadcrumbs' => $breadcrumbs,
            'schemaJsonLd' => $this->schemas($content, $canonicalUrl, $breadcrumbs),
        ]);
    }

    private function schemas(array $page, string $canonicalUrl, array $breadcrumbs): array
    {
        $schemas = [
            [
                '@context' => 'https://schema.org',
                '@type' => 'WebPage',
                'name' => $page['name'],
                'headline' => $page['heading'],
                'description' => $page['meta_description'],
                'url' => $canonicalUrl,
                'dateModified' => config('trust.last_updated_iso'),
                'isPartOf' => [
                    '@type' => 'WebSite',
                    'name' => config('app.name', 'Toolexa'),
                    'url' => url('/'),
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
        ];

        if (! empty($page['faqs'])) {
            $schemas[] = [
                '@context' => 'https://schema.org',
                '@type' => 'FAQPage',
                'mainEntity' => collect($page['faqs'])->map(fn (array $faq) => [
                    '@type' => 'Question',
                    'name' => $faq['question'],
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => $faq['answer'],
                    ],
                ])->all(),
            ];
        }

        return $schemas;
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Tools\HomeController;
use App\Support\BlogRepository;
use App\Services\CategoryLandingService;
use App\Services\InternalLinkingService;
use App\Services\IntelligentSearchService;
use App\Services\ComparisonService;
use App\Services\TopicHubService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

class SiteController extends Controller
{
    private string $brandName = 'Toolexa';
    private string $supportEmail = 'support@toolexa.in';

    private array $pages = [
        'about' => [
            'title' => 'About Toolexa',
            'description' => 'Learn about Toolexa, a free online tools and calculators website for finance, text, utility and everyday productivity tasks.',
            'heading' => 'About Toolexa',
            'content' => [
                'Toolexa is a free online tools and calculators platform created to help users complete common tasks quickly, clearly and securely. The website includes calculators, converters, text utilities and productivity tools that can be used directly from a browser without creating an account.',
                'Our focus is simple: every tool should load fast, work well on mobile devices, provide understandable results and include helpful information so users know how to use the output responsibly. We design tool pages with clean interfaces, step-by-step guidance, FAQs and related tools to improve the overall user experience.',
            ],
            'sections' => [
                [
                    'heading' => 'What We Offer',
                    'body' => 'Toolexa currently provides finance calculators, math tools, text utilities, QR tools, password tools and other everyday utilities. We continue to expand the collection with useful tools that solve practical problems for students, professionals, small business owners and general users.',
                ],
                [
                    'heading' => 'Our Quality Standards',
                    'items' => [
                        'Free access without registration',
                        'Mobile-friendly and responsive layouts',
                        'Clear formulas and explanations wherever applicable',
                        'Helpful FAQs and supporting content on tool pages',
                        'Fast loading pages designed for a clean browsing experience',
                    ],
                ],
                [
                    'heading' => 'Our Goal',
                    'body' => 'Our goal is to build Toolexa.in into a reliable collection of practical online tools that users can trust for quick estimates, simple conversions and everyday productivity tasks.',
                ],
            ],
        ],
        'contact' => [
            'title' => 'Contact Toolexa',
            'description' => 'Contact Toolexa for feedback, corrections, suggestions, tool requests and general website queries.',
            'heading' => 'Contact',
            'content' => [
                'We welcome feedback, correction requests, new tool suggestions and general questions about Toolexa. If you find an issue on any page or want to request a new calculator or utility, please contact us with a clear description.',
                'For faster review, include the page URL, the tool name and a short explanation of your message. We try to review genuine messages as quickly as possible, although response times may vary depending on request volume.',
            ],
            'contact' => true,
            'sections' => [
                [
                    'heading' => 'Email',
                    'body' => 'support@toolexa.in',
                    'email' => true,
                ],
                [
                    'heading' => 'You Can Contact Us For',
                    'items' => [
                        'Reporting incorrect information or calculation issues',
                        'Suggesting new tools, calculators or categories',
                        'Sharing feedback about design, usability or accessibility',
                        'Business, content or website-related queries',
                        'Privacy, policy or legal page questions',
                    ],
                ],
                [
                    'heading' => 'Important Note',
                    'body' => 'Toolexa provides online tools and informational content only. We cannot provide personal financial, legal, tax, medical or professional advice through email.',
                ],
            ],
        ],
        'privacy-policy' => [
            'title' => 'Privacy Policy - Toolexa',
            'description' => 'Read the Toolexa privacy policy for information about tool data, cookies, analytics, advertising and user privacy.',
            'heading' => 'Privacy Policy',
            'content' => [
                'This Privacy Policy explains how Toolexa handles information when you use Toolexa.in. We aim to provide free online tools while keeping the browsing experience simple, transparent and respectful of user privacy.',
                'Most tools on Toolexa work without account registration. Calculator values, text inputs and other tool entries are used to generate results in the tool interface and are not intended to be stored as personal records by Toolexa.',
            ],
            'sections' => [
                [
                    'heading' => 'Information We May Collect',
                    'items' => [
                        'Basic usage information such as pages visited, browser type, device type and approximate location',
                        'Information you voluntarily provide when contacting us by email',
                        'Cookies or similar identifiers used by analytics, security or advertising services',
                    ],
                ],
                [
                    'heading' => 'How We Use Information',
                    'items' => [
                        'To operate, maintain and improve Toolexa',
                        'To understand which tools are useful and how pages perform',
                        'To respond to user messages and support requests',
                        'To protect the website from spam, abuse or technical problems',
                        'To support advertising and analytics where applicable',
                    ],
                ],
                [
                    'heading' => 'Cookies, Analytics and Advertising',
                    'body' => 'Toolexa may use cookies, web analytics and advertising services such as Google Analytics or Google AdSense after they are configured. These services may collect non-personal usage data or use cookies to show relevant ads, measure performance and prevent misuse. You can control or disable cookies through your browser settings.',
                ],
                [
                    'heading' => 'Third-Party Links',
                    'body' => 'Some pages may contain links to third-party websites. Toolexa is not responsible for the privacy practices, content or policies of external websites.',
                ],
                [
                    'heading' => 'Children’s Privacy',
                    'body' => 'Toolexa is intended for general audiences and does not knowingly collect personal information from children. If you believe a child has provided personal information, contact us so we can review the request.',
                ],
                [
                    'heading' => 'Contact',
                    'body' => 'For privacy-related questions, email support@toolexa.in.',
                    'email' => true,
                ],
            ],
        ],
        'terms' => [
            'title' => 'Terms and Conditions - Toolexa',
            'description' => 'Read the terms and conditions for using Toolexa free online tools, calculators and informational content.',
            'heading' => 'Terms and Conditions',
            'content' => [
                'These Terms and Conditions explain the basic rules for using Toolexa.in. By accessing or using the website, you agree to use Toolexa responsibly and in accordance with these terms.',
                'Toolexa provides free online tools, calculators and informational content for general use. You should verify important results before making financial, legal, tax, academic, business or professional decisions.',
            ],
            'sections' => [
                [
                    'heading' => 'Use of Website',
                    'items' => [
                        'Use Toolexa only for lawful and responsible purposes',
                        'Do not attempt to damage, overload, copy or misuse the website',
                        'Do not use automated systems in a way that affects website performance',
                        'Do not submit harmful, abusive or illegal content through any form or tool',
                    ],
                ],
                [
                    'heading' => 'Accuracy of Tools',
                    'body' => 'We work to keep tools useful and accurate, but results may depend on user input, formulas, rounding and changing rules or rates. Toolexa does not guarantee that every result will be complete, current or suitable for every situation.',
                ],
                [
                    'heading' => 'Intellectual Property',
                    'body' => 'The Toolexa name, website design, written content and tool structure are owned by Toolexa unless otherwise stated. You may use the tools for personal or professional tasks, but you may not copy or republish website content as your own without permission.',
                ],
                [
                    'heading' => 'Changes to Website',
                    'body' => 'We may update, improve, remove or add tools, pages, features and policies at any time. Continued use of Toolexa after updates means you accept the revised terms.',
                ],
                [
                    'heading' => 'Contact',
                    'body' => 'For terms-related questions, email support@toolexa.in.',
                    'email' => true,
                ],
            ],
        ],
        'disclaimer' => [
            'title' => 'Disclaimer - Toolexa',
            'description' => 'Toolexa calculators and tools provide estimates for informational purposes and should not replace professional advice.',
            'heading' => 'Disclaimer',
            'content' => [
                'The information, calculators and tools available on Toolexa.in are provided for general informational and educational purposes only. While we aim to make the website useful and easy to understand, Toolexa should not be treated as a substitute for professional advice.',
                'Results from calculators and tools depend on the values entered by the user, selected options, formulas, assumptions and rounding. Actual results from banks, government departments, employers, financial institutions or professional advisors may differ.',
            ],
            'sections' => [
                [
                    'heading' => 'No Professional Advice',
                    'body' => 'Toolexa does not provide financial, investment, legal, tax, medical, accounting or professional advice. Always consult a qualified professional before making important decisions based on tool results or website content.',
                ],
                [
                    'heading' => 'Finance and Calculator Results',
                    'body' => 'Finance calculators such as EMI, SIP, FD, PPF, CAGR, inflation and loan tools are intended to provide estimates only. Interest rates, taxes, charges, penalties, rules and official calculations may vary by provider, country, state and time.',
                ],
                [
                    'heading' => 'External Links and Ads',
                    'body' => 'Toolexa may display advertisements or links to third-party websites. We do not control external websites and are not responsible for their content, products, services or policies.',
                ],
                [
                    'heading' => 'User Responsibility',
                    'items' => [
                        'Check all inputs before relying on a result',
                        'Verify important calculations from official or professional sources',
                        'Use tools as planning aids, not final decisions',
                        'Contact us if you notice incorrect content or calculation behavior',
                    ],
                ],
                [
                    'heading' => 'Contact',
                    'body' => 'To report an issue, email support@toolexa.in with the page URL and details.',
                    'email' => true,
                ],
            ],
        ],
    ];

    public function page(string $page)
    {
        abort_unless(isset($this->pages[$page]), 404);

        $data = $this->pages[$page];

        return view('static-page', [
            'page' => $data,
            'canonicalUrl' => url($page),
            'seoTitle' => $data['title'],
            'seoDescription' => $data['description'],
            'seoKeywords' => 'Toolexa, '.$data['heading'].', online tools',
            'breadcrumbs' => [
                ['name' => 'Home', 'url' => url('/')],
                ['name' => $data['heading'], 'url' => url($page)],
            ],
        ]);
    }

    public function sitemap()
    {
        $cacheKey = 'sitemap:v3:'.sha1((string) config('app.url').'|'.request()->getSchemeAndHttpHost());
        $urls = Cache::remember($cacheKey, now()->addHours(6), function () {
        $urls = [
            ['loc' => url('/'), 'priority' => '1.0', 'changefreq' => 'daily', 'lastmod' => now()->toDateString()],
            ['loc' => url('search'), 'priority' => '0.6', 'changefreq' => 'weekly', 'lastmod' => now()->toDateString()],
            ['loc' => route('blog.index'), 'priority' => '0.8', 'changefreq' => 'weekly', 'lastmod' => now()->toDateString()],
        ];

        foreach (array_keys($this->pages) as $page) {
            $urls[] = [
                'loc' => url($page),
                'priority' => '0.5',
                'changefreq' => 'monthly',
                'lastmod' => now()->toDateString(),
            ];
        }

        foreach (HomeController::categories() as $category) {
            $urls[] = [
                'loc' => url('category/'.$category['slug']),
                'priority' => '0.8',
                'changefreq' => 'weekly',
                'lastmod' => now()->toDateString(),
            ];
        }

        foreach (HomeController::tools() as $tool) {
            $urls[] = [
                'loc' => url('tools/'.$tool['slug']),
                'priority' => '0.9',
                'changefreq' => 'monthly',
                'lastmod' => now()->toDateString(),
            ];
        }

        foreach (BlogRepository::all() as $article) {
            $urls[] = [
                'loc' => route('blog.show', $article['slug']),
                'priority' => '0.7',
                'changefreq' => 'monthly',
                'lastmod' => $article['published_at'] ?? now()->toDateString(),
            ];
        }

        $urls[] = ['loc' => route('compare.index'), 'priority' => '0.7', 'changefreq' => 'weekly', 'lastmod' => now()->toDateString()];
        foreach (app(ComparisonService::class)->all() as $comparison) {
            $urls[] = [
                'loc' => route('compare.show', $comparison['slug']),
                'priority' => '0.8',
                'changefreq' => 'monthly',
                'lastmod' => date('Y-m-d', filemtime(config_path('comparisons.php')) ?: time()),
            ];
        }

        $urls[] = ['loc' => route('hub.index'), 'priority' => '0.8', 'changefreq' => 'weekly', 'lastmod' => now()->toDateString()];
        foreach (app(TopicHubService::class)->all() as $hub) {
            $urls[] = [
                'loc' => route('hub.show', $hub['slug']),
                'priority' => '0.9',
                'changefreq' => 'weekly',
                'lastmod' => date('Y-m-d', filemtime(config_path('hubs.php')) ?: time()),
            ];
        }

        return $urls;
        });

        return response()
            ->view('sitemap', ['urls' => $urls])
            ->header('Content-Type', 'application/xml; charset=UTF-8')
            ->header('Cache-Control', 'public, max-age=21600, stale-while-revalidate=86400');
    }

    public function robots()
    {
        $content = implode("\n", [
            'User-agent: *',
            'Allow: /',
            'Disallow: /storage/',
            'Disallow: /vendor/',
            'Disallow: /bootstrap/',
            'Disallow: /config/',
            '',
            'Sitemap: '.url('sitemap.xml'),
            '',
        ]);

        return response($content, 200)
            ->header('Content-Type', 'text/plain; charset=UTF-8')
            ->header('Cache-Control', 'public, max-age=3600');
    }

    public function ads()
    {
        $publisherId = trim((string) config('services.google.adsense_publisher_id'));
        $publisherId = preg_replace('/^ca-/', '', $publisherId);
        $content = $publisherId
            ? 'google.com, '.$publisherId.', DIRECT, f08c47fec0942fa0'."\n"
            : '';

        return response($content, 200)
            ->header('Content-Type', 'text/plain; charset=UTF-8')
            ->header('Cache-Control', 'public, max-age=3600');
    }

    public function search(Request $request, IntelligentSearchService $searchService)
    {
        $query = trim((string) $request->query('q', ''));
        $filter = trim((string) $request->query('filter', 'all'));
        $initialResults = $searchService->search($query, $filter);
        $popular = $searchService->popularContent();

        return view('search', [
            'query' => $query,
            'selectedFilter' => $filter,
            'initialResults' => $initialResults,
            'popularContent' => $popular,
            'trendingSearches' => $searchService->trendingSearches(),
            'breadcrumbs' => [
                ['name' => 'Home', 'url' => url('/')],
                ['name' => 'Search', 'url' => route('search')],
            ],
            'canonicalUrl' => url('search'),
            'seoTitle' => $query ? 'Search results for '.$query.' - Toolexa' : 'Search Free Online Tools - Toolexa',
            'seoDescription' => 'Search Toolexa calculators and utility tools by name, category or keyword.',
            'seoKeywords' => 'search tools, online calculators, free utility tools',
            'robotsMeta' => $query ? 'noindex, follow' : 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1',
        ]);
    }

    public function searchApi(Request $request, IntelligentSearchService $searchService)
    {
        $query = mb_substr(trim((string) $request->query('q', '')), 0, 100);
        $filter = trim((string) $request->query('filter', 'all'));

        return response()->json($searchService->search($query, $filter))
            ->header('Cache-Control', 'public, max-age=300');
    }

    public function category(Request $request, string $category, CategoryLandingService $landingService, InternalLinkingService $linkingService)
    {
        $allCategories = HomeController::categories();
        $allTools = HomeController::tools();
        $categoryMeta = collect($allCategories)->firstWhere('slug', $category);
        abort_unless($categoryMeta, 404);

        $categoryTools = collect($allTools)
            ->filter(fn ($tool) => Str::slug($tool['category']) === $category)
            ->values();
        $landing = $landingService->landing($categoryMeta, $categoryTools->all());
        $query = trim((string) $request->query('q', ''));
        $tools = $categoryTools;

        if ($query !== '') {
            $needle = Str::lower($query);
            $tools = $tools->filter(fn (array $tool) => str_contains(Str::lower(implode(' ', [
                $tool['name'], $tool['desc'], $tool['keywords'] ?? '',
            ])), $needle))->values();
        }

        $paginatedTools = $this->paginate($tools->all(), 12, $request, 'all-tools');
        $relatedArticles = $linkingService->relatedArticlesForTool([
            'name' => $landing['label'],
            'slug' => 'category-'.$category,
            'category' => $categoryMeta['name'],
            'keywords' => $categoryTools->pluck('keywords')->implode(' '),
            'desc' => $landing['description'],
        ], 4);
        $relatedCategories = $landingService->relatedCategories($categoryMeta, $allCategories, $allTools);
        $canonicalUrl = route('category.show', $category);
        $page = max(1, (int) $request->query('page', 1));

        if ($page > 1 && $query === '') {
            $canonicalUrl .= '?page='.$page;
        }

        $breadcrumbs = [
            ['name' => 'Home', 'url' => url('/')],
            ['name' => $landing['label'], 'url' => route('category.show', $category)],
        ];
        $schemaJsonLd = $this->categorySchema($categoryMeta, $landing, $categoryTools->all(), $breadcrumbs, $canonicalUrl);

        return view('category', [
            'tools' => $paginatedTools,
            'allToolCount' => $categoryTools->count(),
            'category' => $categoryMeta,
            'landing' => $landing,
            'query' => $query,
            'relatedArticles' => $relatedArticles,
            'relatedCategories' => $relatedCategories,
            'breadcrumbs' => $breadcrumbs,
            'canonicalUrl' => $canonicalUrl,
            'seoTitle' => $landing['meta_title'],
            'seoDescription' => $landing['meta_description'],
            'seoKeywords' => Str::lower($landing['label']).', online '.Str::lower($categoryMeta['name']).', free tools',
            'robotsMeta' => $query !== '' ? 'noindex, follow' : 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1',
            'schemaJsonLd' => $schemaJsonLd,
        ]);
    }

    private function categorySchema(array $category, array $landing, array $tools, array $breadcrumbs, string $canonicalUrl): array
    {
        $items = collect($tools)->values()->map(fn (array $tool, int $index) => [
            '@type' => 'ListItem',
            'position' => $index + 1,
            'name' => $tool['name'],
            'url' => url('tools/'.$tool['slug']),
        ])->all();

        return [
            [
                '@context' => 'https://schema.org',
                '@type' => 'CollectionPage',
                'name' => $landing['label'],
                'description' => $landing['description'],
                'url' => $canonicalUrl,
                'mainEntity' => ['@type' => 'ItemList', 'numberOfItems' => count($tools), 'itemListElement' => $items],
            ],
            [
                '@context' => 'https://schema.org',
                '@type' => 'BreadcrumbList',
                'itemListElement' => collect($breadcrumbs)->values()->map(fn (array $item, int $index) => [
                    '@type' => 'ListItem', 'position' => $index + 1, 'name' => $item['name'], 'item' => $item['url'],
                ])->all(),
            ],
            [
                '@context' => 'https://schema.org',
                '@type' => 'FAQPage',
                'mainEntity' => collect($landing['faqs'])->map(fn (array $faq) => [
                    '@type' => 'Question',
                    'name' => $faq['question'],
                    'acceptedAnswer' => ['@type' => 'Answer', 'text' => $faq['answer']],
                ])->all(),
            ],
        ];
    }

    private function paginate(array $items, int $perPage, Request $request, string $pageName): LengthAwarePaginator
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
                'fragment' => $pageName,
            ]
        );
    }
}

<?php

namespace App\Services\SiteAudit;

use DOMDocument;
use DOMElement;
use DOMXPath;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class SiteAuditService
{
    public function __construct(
        private readonly PageInventoryService $inventory,
        private readonly LinkValidationService $links
    ) {}

    public function report(bool $refresh = false): array
    {
        $key = $this->cacheKey();
        if ($refresh) {
            Cache::forget($key);
        }

        return Cache::remember($key, now()->addHours((int) config('site_audit.cache_hours', 6)), fn () => $this->scan());
    }

    public function cacheKey(): string
    {
        $inventory = $this->inventory->all();
        $files = [
            app_path('Services/SiteAudit/SiteAuditService.php'),
            resource_path('views/layouts/app.blade.php'),
            public_path('assets/css/style.min.css'),
            public_path('assets/js/app.min.js'),
        ];
        $versions = collect($files)->map(fn (string $file) => is_file($file) ? filemtime($file) : 0)->all();

        return 'site-audit:v1:'.substr(sha1(json_encode([array_column($inventory, 'path'), $versions, config('site_audit.page_types')])), 0, 16);
    }

    private function scan(): array
    {
        $started = microtime(true);
        $definitions = $this->inventory->all();
        $knownPaths = array_flip(array_map(fn (array $page) => $this->normalizePath($page['path']), $definitions));
        $pages = [];
        $externalUrls = [];

        foreach ($definitions as $definition) {
            $page = $this->auditPage($definition, $knownPaths);
            foreach ($page['_external_urls'] as $url) {
                $externalUrls[$url] = true;
            }
            $pages[] = $page;
        }

        $externalResults = $this->links->externalMany(array_slice(array_keys($externalUrls), 0, (int) config('site_audit.max_external_links', 100)));

        $pages = $this->applyCrossPageChecks($pages, $externalResults);
        $summary = $this->summary($pages);

        return [
            'generated_at' => now()->toIso8601String(),
            'duration_ms' => (int) round((microtime(true) - $started) * 1000),
            'summary' => $summary,
            'pages' => $pages,
            'duplicate_groups' => $this->duplicateGroups($pages),
            'external_links_checked' => count($externalResults),
        ];
    }

    private function auditPage(array $definition, array $knownPaths): array
    {
        $response = $this->render($definition['path']);
        $html = $response['html'];
        [$dom, $xpath] = $this->dom($html);
        $text = $this->cleanText($xpath->evaluate('string(//body)'));
        $title = trim($xpath->evaluate('string(//title)'));
        $description = trim($xpath->evaluate('string(//meta[@name="description"]/@content)'));
        $h1 = trim($xpath->evaluate('string(//h1[1])'));
        $schemas = $this->schemaTypes($xpath);
        $typeRules = config('site_audit.page_types.'.$definition['type'], ['schemas' => [], 'eeat' => [], 'links' => []]);
        $allAnchors = $this->anchors($xpath, $definition['path'], '//a[@href]');
        $contentAnchors = $this->anchors($xpath, $definition['path'], '//main//a[@href]');
        $internalUrls = collect($contentAnchors)->where('kind', 'internal')->pluck('url')->unique()->values()->all();
        $externalUrls = collect($contentAnchors)->where('kind', 'external')->pluck('url')->unique()->values()->all();
        $allInternalUrls = collect($allAnchors)->where('kind', 'internal')->pluck('url')->unique()->values()->all();
        $allExternalUrls = collect($allAnchors)->where('kind', 'external')->pluck('url')->unique()->values()->all();
        $paragraphs = collect($xpath->query('//main//p'))->map(fn (DOMElement $node) => $this->cleanText($node->textContent))->filter(fn (string $value) => str_word_count($value) >= 12)->values()->all();
        $faqCount = $xpath->query('//*[contains(concat(" ", normalize-space(@class), " "), " faq-panel ")]//details')->length;

        $modules = [
            'metadata' => $this->metadataChecks($xpath, $title, $description, $h1),
            'eeat' => $this->eeatChecks($html, $xpath, $typeRules['eeat'] ?? []),
            'content' => $this->contentChecks($text, $xpath, $faqCount, $internalUrls, $externalUrls, $paragraphs, $definition['type']),
            'internal_linking' => $this->internalLinkChecks($html, $typeRules['links'] ?? [], $internalUrls),
            'structured_data' => $this->schemaChecks($schemas, $typeRules['schemas'] ?? []),
            'accessibility' => $this->accessibilityChecks($xpath),
            'performance' => $this->performanceChecks($xpath, $response['headers']),
            'trust' => $this->trustChecks($xpath),
        ];
        $internalBroken = [];
        foreach ($allInternalUrls as $url) {
            $path = $this->normalizePath($url);
            $result = isset($knownPaths[$path]) ? ['ok' => true, 'status' => 200] : $this->links->internal($url);
            if (! $result['ok']) {
                $internalBroken[] = ['url' => $url, 'status' => $result['status']];
            }
        }
        foreach ($this->imageUrls($xpath) as $url) {
            if (parse_url($url, PHP_URL_HOST) && parse_url($url, PHP_URL_HOST) !== parse_url(config('app.url'), PHP_URL_HOST)) {
                $allExternalUrls[] = $url;

                continue;
            }
            $result = $this->links->internal($url);
            if (! $result['ok']) {
                $internalBroken[] = ['url' => $url, 'status' => $result['status'], 'resource' => 'image'];
            }
        }
        $allExternalUrls = array_values(array_unique($allExternalUrls));
        $modules['broken_links'] = [
            $this->check('Internal links resolve', count($internalBroken) === 0, count($internalBroken).' broken internal links'),
            $this->check('Page response is successful', $response['status'] >= 200 && $response['status'] < 400, 'HTTP '.$response['status']),
        ];
        $modules['duplicates'] = [];
        $scores = $this->moduleScores($modules);

        return array_merge($definition, [
            'url' => url($definition['path']),
            'status_code' => $response['status'],
            'title' => $title,
            'meta_description' => $description,
            'h1' => $h1,
            'schemas' => $schemas,
            'metrics' => [
                'word_count' => str_word_count($text),
                'heading_count' => $xpath->query('//main//*[self::h1 or self::h2 or self::h3 or self::h4 or self::h5 or self::h6]')->length,
                'image_count' => $xpath->query('//main//img')->length,
                'faq_count' => $faqCount,
                'internal_links' => count($internalUrls),
                'external_references' => count($externalUrls),
                'reading_time' => max(1, (int) ceil(str_word_count($text) / 220)),
                'uniqueness_score' => 100,
                'content_depth_score' => $this->contentDepthScore(str_word_count($text), $faqCount, count($internalUrls), $definition['type']),
            ],
            'signals' => $this->pageSignals($xpath, $html, $definition['type'], $faqCount, $internalUrls, $externalUrls),
            'modules' => $modules,
            'module_scores' => $scores,
            '_paragraph_hashes' => array_map(fn (string $paragraph) => sha1(Str::lower($paragraph)), $paragraphs),
            '_intro_hash' => isset($paragraphs[0]) ? sha1(Str::lower($paragraphs[0])) : null,
            '_internal_urls' => $allInternalUrls,
            '_external_urls' => $allExternalUrls,
            '_broken_internal' => $internalBroken,
        ]);
    }

    private function applyCrossPageChecks(array $pages, array $externalResults): array
    {
        $valueCounts = ['title' => [], 'meta_description' => [], 'h1' => [], '_intro_hash' => []];
        $paragraphCounts = [];
        $incoming = [];
        foreach ($pages as $page) {
            foreach (array_keys($valueCounts) as $field) {
                $value = $page[$field] ?? null;
                if ($value) {
                    $valueCounts[$field][$value] = ($valueCounts[$field][$value] ?? 0) + 1;
                }
            }
            foreach ($page['_paragraph_hashes'] as $hash) {
                $paragraphCounts[$hash] = ($paragraphCounts[$hash] ?? 0) + 1;
            }
            foreach ($page['_internal_urls'] as $url) {
                $incoming[$this->normalizePath($url)] = ($incoming[$this->normalizePath($url)] ?? 0) + 1;
            }
        }

        foreach ($pages as &$page) {
            $duplicateParagraphs = collect($page['_paragraph_hashes'])->filter(fn (string $hash) => ($paragraphCounts[$hash] ?? 0) > 1)->count();
            $paragraphTotal = max(count($page['_paragraph_hashes']), 1);
            $uniqueness = max(0, (int) round(100 - ($duplicateParagraphs / $paragraphTotal * 100)));
            $page['metrics']['uniqueness_score'] = $uniqueness;
            $duplicateChecks = [
                $this->check('Unique title', ($valueCounts['title'][$page['title']] ?? 0) <= 1, 'Used by '.($valueCounts['title'][$page['title']] ?? 0).' pages'),
                $this->check('Unique meta description', ($valueCounts['meta_description'][$page['meta_description']] ?? 0) <= 1, 'Used by '.($valueCounts['meta_description'][$page['meta_description']] ?? 0).' pages'),
                $this->check('Unique H1', ($valueCounts['h1'][$page['h1']] ?? 0) <= 1, 'Used by '.($valueCounts['h1'][$page['h1']] ?? 0).' pages'),
                $this->check('Unique introduction', ! $page['_intro_hash'] || ($valueCounts['_intro_hash'][$page['_intro_hash']] ?? 0) <= 1, 'Exact introductory paragraph match'),
                $this->check('Unique content blocks', $uniqueness >= 70, $uniqueness.'% unique substantial paragraphs'),
            ];
            $page['modules']['duplicates'] = $duplicateChecks;
            $path = $this->normalizePath($page['path']);
            $orphan = $path !== '/' && ($incoming[$path] ?? 0) === 0;
            $page['modules']['internal_linking'][] = $this->check('Page has incoming internal links', ! $orphan, ($incoming[$path] ?? 0).' incoming links');

            $brokenExternal = [];
            foreach ($page['_external_urls'] as $url) {
                if (isset($externalResults[$url]) && ! $externalResults[$url]['ok']) {
                    $brokenExternal[] = ['url' => $url, 'status' => $externalResults[$url]['status'] ?? null];
                }
            }
            $page['modules']['broken_links'][] = $this->check('External links resolve', count($brokenExternal) === 0, count($brokenExternal).' broken external links');
            $page['broken_links'] = array_merge($page['_broken_internal'], $brokenExternal);
            $page['module_scores'] = $this->moduleScores($page['modules']);
            $page['score'] = $this->healthScore($page['module_scores']);
            $page['health'] = $this->healthLabel($page['score']);
            $page['recommendations'] = $this->recommendations($page);
            unset($page['_paragraph_hashes'], $page['_intro_hash'], $page['_internal_urls'], $page['_external_urls'], $page['_broken_internal']);
        }
        unset($page);

        return $pages;
    }

    private function render(string $path): array
    {
        $originalRequest = app('request');
        $request = Request::create($path, 'GET', [], [], [], [
            'HTTP_HOST' => parse_url(config('app.url'), PHP_URL_HOST) ?: 'localhost',
            'HTTP_ACCEPT' => 'text/html',
            'HTTP_X_TO0LEXA_AUDIT' => '1',
        ]);
        try {
            $response = app(Kernel::class)->handle($request);
        } finally {
            app()->instance('request', $originalRequest);
            app('url')->setRequest($originalRequest);
        }
        $headers = [];
        foreach ($response->headers->all() as $name => $values) {
            $headers[strtolower($name)] = implode(', ', $values);
        }

        return ['status' => $response->getStatusCode(), 'html' => (string) $response->getContent(), 'headers' => $headers];
    }

    private function dom(string $html): array
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $previous = libxml_use_internal_errors(true);
        $dom->loadHTML('<?xml encoding="UTF-8">'.$html, LIBXML_NOWARNING | LIBXML_NOERROR);
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        return [$dom, new DOMXPath($dom)];
    }

    private function metadataChecks(DOMXPath $xpath, string $title, string $description, string $h1): array
    {
        $containsEncodedEntity = static fn (string $value): bool => preg_match(
            '/&(?:amp|quot|apos|lt|gt|#\d+|#x[a-f0-9]+);/i',
            $value
        ) === 1;

        return [
            $this->check('Title exists', $title !== '', $title ?: 'Missing'),
            $this->check('Meta description exists', $description !== '', $description ? mb_strlen($description).' characters' : 'Missing'),
            $this->check(
                'Metadata is not double escaped',
                ! $containsEncodedEntity($title) && ! $containsEncodedEntity($description) && ! $containsEncodedEntity($h1),
                'No encoded entity text remains after HTML parsing'
            ),
            $this->check('Canonical exists', $xpath->query('//link[@rel="canonical" and @href]')->length > 0),
            $this->check('OpenGraph exists', $xpath->query('//meta[starts-with(@property, "og:")]')->length >= 4),
            $this->check('Twitter Card exists', $xpath->query('//meta[@name="twitter:card"]')->length > 0),
            $this->check('One H1 exists', $xpath->query('//h1')->length === 1, $xpath->query('//h1')->length.' H1 elements'),
        ];
    }

    private function eeatChecks(string $html, DOMXPath $xpath, array $required): array
    {
        $checks = [
            'written' => ['Written By', stripos($html, 'Written by') !== false],
            'verified' => ['Verified By', stripos($html, 'Verified by') !== false],
            'reviewed' => ['Reviewed By', stripos($html, 'Reviewed by') !== false],
            'updated' => ['Last Updated', stripos($html, 'Last Updated') !== false || stripos($html, 'Updated') !== false],
            'reading_time' => ['Reading Time', stripos($html, 'min read') !== false],
            'author_profile' => ['Author Profile', $xpath->query('//a[contains(@href, "/authors/")]')->length > 0],
            'feedback' => ['Feedback section', stripos($html, 'helpful?') !== false],
        ];

        return collect($required)->map(fn (string $key) => $this->check($checks[$key][0], $checks[$key][1]))->values()->all();
    }

    private function contentChecks(string $text, DOMXPath $xpath, int $faqs, array $internal, array $external, array $paragraphs, string $type): array
    {
        $words = str_word_count($text);
        $minimum = in_array($type, ['blog', 'tool', 'comparison', 'topic', 'category'], true) ? 700 : 100;
        $official = collect($external)->filter(fn (string $url) => $this->isOfficial($url))->count();

        return [
            $this->check('Adequate word count', $words >= $minimum, $words.' words; target '.$minimum.'+'),
            $this->check('Useful heading depth', $xpath->query('//main//*[self::h2 or self::h3]')->length >= 2, $xpath->query('//main//*[self::h2 or self::h3]')->length.' supporting headings'),
            $this->check('Internal links present', count($internal) >= 3, count($internal).' links'),
            $this->check('FAQs present where valuable', ! in_array($type, ['blog', 'tool', 'comparison', 'topic', 'category'], true) || $faqs >= 6, $faqs.' FAQs'),
            $this->check('Official references present where expected', ! in_array($type, ['blog', 'tool', 'comparison', 'topic', 'category'], true) || $official > 0, $official.' official references'),
            $this->check('Substantial paragraphs present', count($paragraphs) >= 3, count($paragraphs).' substantial paragraphs'),
        ];
    }

    private function internalLinkChecks(string $html, array $required, array $internal): array
    {
        $patterns = [
            'tools' => ['/tools/'],
            'articles' => ['/blog/'],
            'comparisons' => ['/compare/'],
            'topics' => ['/topics', '-tools'],
        ];
        $checks = [];
        foreach ($required as $kind) {
            $matched = collect($patterns[$kind])->contains(fn (string $pattern) => str_contains($html, $pattern));
            $checks[] = $this->check('Related '.Str::headline($kind), $matched);
        }
        $checks[] = $this->check('No duplicate link targets', count($internal) === count(array_unique($internal)), count($internal).' unique destinations');

        return $checks;
    }

    private function schemaChecks(array $schemas, array $required): array
    {
        return collect($required)->map(fn (string $type) => $this->check($type.' schema', in_array($type, $schemas, true), implode(', ', $schemas)))->values()->all();
    }

    private function accessibilityChecks(DOMXPath $xpath): array
    {
        $images = $xpath->query('//main//img');
        $missingAlt = $xpath->query('//main//img[not(@alt)]')->length;
        $buttons = $xpath->query('//main//button');
        $unlabelledButtons = 0;
        foreach ($buttons as $button) {
            if (trim($button->textContent) === '' && ! $button->hasAttribute('aria-label') && ! $button->hasAttribute('title')) {
                $unlabelledButtons++;
            }
        }
        $headings = [];
        foreach ($xpath->query('//main//*[self::h1 or self::h2 or self::h3 or self::h4 or self::h5 or self::h6]') as $heading) {
            $headings[] = (int) substr($heading->nodeName, 1);
        }
        $hierarchyValid = true;
        for ($index = 1; $index < count($headings); $index++) {
            if ($headings[$index] > $headings[$index - 1] + 1) {
                $hierarchyValid = false;
                break;
            }
        }

        return [
            $this->check('All images have ALT', $missingAlt === 0, $missingAlt.' of '.$images->length.' missing'),
            $this->check('Heading hierarchy has no skipped levels', $hierarchyValid),
            $this->check('Buttons have accessible labels', $unlabelledButtons === 0, $unlabelledButtons.' unlabeled'),
            $this->check('ARIA landmarks or labels present', $xpath->query('//*[@aria-label or @aria-labelledby or @role]')->length > 0),
            $this->check('Interactive controls use native keyboard elements', $xpath->query('//main//*[@onclick and not(self::button) and not(self::a)]')->length === 0),
        ];
    }

    private function performanceChecks(DOMXPath $xpath, array $headers): array
    {
        $images = $xpath->query('//main//img');
        $belowFold = $xpath->query('//main//img[position() > 1]');
        $missingLazy = $xpath->query('//main//img[position() > 1 and not(@loading="lazy")]')->length;
        $missingDimensions = $xpath->query('//main//img[not(@width) or not(@height)]')->length;
        $modern = $xpath->query('//picture/source[contains(@type, "webp") or contains(@type, "avif")]')->length;
        $large = 0;
        foreach ($images as $image) {
            $src = $image->getAttribute('src');
            $path = parse_url($src, PHP_URL_PATH);
            if ($path && str_contains($path, '/assets/')) {
                $file = public_path(ltrim(substr($path, strpos($path, '/assets/')), '/'));
                if (is_file($file) && filesize($file) > (int) config('site_audit.large_image_bytes')) {
                    $large++;
                }
            }
        }
        $blockingScripts = $xpath->query('//head/script[@src and not(@defer) and not(@async) and not(@type="module")]')->length;

        return [
            $this->check('Below-fold images use lazy loading', $missingLazy === 0, $missingLazy.' of '.$belowFold->length.' missing'),
            $this->check('Images define dimensions', $missingDimensions === 0, $missingDimensions.' missing dimensions'),
            $this->check('Modern image formats available', $images->length === 0 || $modern > 0, $modern.' WebP/AVIF sources'),
            $this->check('No oversized local images', $large === 0, $large.' over '.config('site_audit.large_image_bytes').' bytes'),
            $this->check('No blocking scripts', $blockingScripts === 0, $blockingScripts.' blocking scripts'),
            $this->check('Cache headers present', isset($headers['cache-control']), $headers['cache-control'] ?? 'Missing'),
        ];
    }

    private function pageSignals(DOMXPath $xpath, string $html, string $type, int $faqCount, array $internalUrls, array $externalUrls): array
    {
        $headings = collect($xpath->query('//main//*[self::h1 or self::h2 or self::h3 or self::h4]'))
            ->map(fn (DOMElement $heading) => Str::lower($this->cleanText($heading->textContent)))
            ->implode(' | ');
        $hasHeading = fn (array $needles): bool => collect($needles)
            ->contains(fn (string $needle) => str_contains($headings, Str::lower($needle)));
        $officialReferences = collect($externalUrls)->filter(fn (string $url) => $this->isOfficial($url))->count();

        return [
            'introduction' => $xpath->query('//main//p[string-length(normalize-space()) >= 120]')->length > 0,
            'tool_interface' => $xpath->query('//main//form | //main//input | //main//textarea | //main//select')->length > 0,
            'how_to' => $hasHeading(['how to use', 'how it works']),
            'examples' => $hasHeading(['example', 'real-world', 'practical']),
            'formula' => $hasHeading(['formula', 'calculation']),
            'advantages' => $hasHeading(['advantage', 'benefit']),
            'limitations' => $hasHeading(['limitation', 'important note']),
            'best_practices' => $hasHeading(['best practice', 'tips']),
            'common_mistakes' => $hasHeading(['common mistake', 'mistakes to avoid']),
            'faq' => $faqCount > 0,
            'related_tools' => collect($internalUrls)->contains(fn (string $url) => str_contains($url, '/tools/')),
            'related_articles' => collect($internalUrls)->contains(fn (string $url) => str_contains($url, '/blog/')),
            'related_comparisons' => collect($internalUrls)->contains(fn (string $url) => str_contains($url, '/compare/')),
            'topic_links' => collect($internalUrls)->contains(fn (string $url) => str_contains($url, '/topics') || (bool) preg_match('#/[a-z0-9-]+-tools$#', parse_url($url, PHP_URL_PATH) ?: '')),
            'official_references' => $officialReferences > 0,
            'feedback' => stripos($html, 'helpful?') !== false,
            'summary' => $hasHeading(['summary', 'quick answer', 'key takeaway']),
            'table_of_contents' => $xpath->query('//main//nav[contains(@class, "toc") or contains(@aria-label, "contents")] | //main//*[@data-table-of-contents]')->length > 0
                || stripos($html, 'Table of Contents') !== false,
            'author' => stripos($html, 'Written by') !== false || stripos($html, 'Verified by') !== false,
            'reviewer' => stripos($html, 'Reviewed by') !== false,
            'winner_summary' => $hasHeading(['quick winner', 'winner', 'at a glance']),
            'comparison_table' => $xpath->query('//main//table')->length > 0,
            'decision_guidance' => $hasHeading(['when should you choose', 'which should you choose', 'how to choose']),
            'pros_cons' => $hasHeading(['advantages', 'disadvantages', 'pros', 'cons']),
            'overview' => $hasHeading(['overview', 'about']),
            'featured_tools' => $hasHeading(['featured tools', 'popular tools']),
            'popular_articles' => $hasHeading(['popular articles', 'featured articles', 'latest articles']),
            'comparisons' => $hasHeading(['comparison']),
            'glossary' => $hasHeading(['glossary']),
            'beginner_guide' => $hasHeading(['beginner guide', 'complete guide', 'understanding']),
            'lazy_loading' => $xpath->query('//main//img[position() > 1 and not(@loading="lazy")]')->length === 0,
            'image_dimensions' => $xpath->query('//main//img[not(@width) or not(@height)]')->length === 0,
            'responsive_viewport' => $xpath->query('//meta[@name="viewport" and contains(@content, "width=device-width")]')->length > 0,
            'keyboard_controls' => $xpath->query('//main//*[@onclick and not(self::button) and not(self::a)]')->length === 0,
            'page_type' => $type,
        ];
    }

    private function trustChecks(DOMXPath $xpath): array
    {
        $required = [
            '/privacy-policy' => 'Privacy Policy', '/terms' => 'Terms', '/disclaimer' => 'Disclaimer',
            '/editorial-policy' => 'Editorial Policy', '/accuracy-policy' => 'Accuracy Policy',
            '/trust' => 'Trust Center', '/contact' => 'Contact Page', '/about' => 'About Page',
        ];
        $checks = [];
        foreach ($required as $path => $label) {
            $checks[] = $this->check($label.' linked', $xpath->query('//footer//a[contains(@href, "'.$path.'")]')->length > 0);
        }
        $checks[] = $this->check('Footer exists', $xpath->query('//footer[contains(concat(" ", normalize-space(@class), " "), " site-footer ")]')->length === 1);

        return $checks;
    }

    private function anchors(DOMXPath $xpath, string $currentPath, string $query): array
    {
        $baseHost = parse_url(config('app.url'), PHP_URL_HOST);

        return collect($xpath->query($query))->map(function (DOMElement $anchor) use ($baseHost, $currentPath) {
            $href = trim($anchor->getAttribute('href'));
            if ($href === '' || str_starts_with($href, '#') || str_starts_with($href, 'mailto:') || str_starts_with($href, 'tel:') || str_starts_with($href, 'javascript:')) {
                return null;
            }
            $host = parse_url($href, PHP_URL_HOST);
            if ($host && $host !== $baseHost && $host !== request()->getHost()) {
                return ['url' => $href, 'kind' => 'external'];
            }
            if (! str_starts_with($href, 'http')) {
                $href = str_starts_with($href, '/') ? $href : '/'.ltrim(dirname($currentPath).'/'.$href, '/');
            }

            return ['url' => $href, 'kind' => 'internal'];
        })->filter()->values()->all();
    }

    private function imageUrls(DOMXPath $xpath): array
    {
        return collect($xpath->query('//img[@src]'))->map(fn (DOMElement $image) => trim($image->getAttribute('src')))->filter()->unique()->values()->all();
    }

    private function schemaTypes(DOMXPath $xpath): array
    {
        $types = [];
        foreach ($xpath->query('//script[@type="application/ld+json"]') as $script) {
            $decoded = json_decode($script->textContent, true);
            $this->collectSchemaTypes($decoded, $types);
        }

        return array_values(array_unique($types));
    }

    private function collectSchemaTypes(mixed $value, array &$types): void
    {
        if (! is_array($value)) {
            return;
        }
        if (isset($value['@type'])) {
            foreach ((array) $value['@type'] as $type) {
                $types[] = $type;
            }
        }
        foreach ($value as $child) {
            if (is_array($child)) {
                $this->collectSchemaTypes($child, $types);
            }
        }
    }

    private function moduleScores(array $modules): array
    {
        return collect($modules)->map(fn (array $checks) => count($checks) === 0 ? 100 : (int) round(collect($checks)->where('pass', true)->count() / count($checks) * 100))->all();
    }

    private function healthScore(array $scores): int
    {
        $weights = ['metadata' => 14, 'eeat' => 13, 'content' => 15, 'internal_linking' => 10, 'structured_data' => 14, 'accessibility' => 10, 'performance' => 9, 'trust' => 8, 'broken_links' => 5, 'duplicates' => 2];
        $total = 0;
        foreach ($weights as $module => $weight) {
            $total += ($scores[$module] ?? 100) * $weight / 100;
        }

        return max(0, min(100, (int) round($total)));
    }

    private function recommendations(array $page): array
    {
        $recommendations = [];
        foreach ($page['modules'] as $module => $checks) {
            foreach ($checks as $check) {
                if (! $check['pass']) {
                    $recommendations[] = [
                        'module' => Str::headline($module),
                        'issue' => $check['label'],
                        'action' => $this->actionFor($check['label'], $page),
                    ];
                }
            }
        }

        return array_slice($recommendations, 0, 12);
    }

    private function actionFor(string $label, array $page): string
    {
        return match (true) {
            str_contains($label, 'Official references') => 'Add a directly relevant government, standards-body or official documentation reference.',
            str_contains($label, 'schema') => 'Add the missing '.$label.' to the page JSON-LD graph.',
            str_contains($label, 'incoming') => 'Add a contextual link to this page from a related tool, article, category or topic directory.',
            str_contains($label, 'ALT') => 'Add concise descriptive alt text to every meaningful image.',
            str_contains($label, 'dimensions') => 'Set explicit width and height attributes to prevent layout shifts.',
            str_contains($label, 'word count') => 'Expand the page with original explanations, examples, limitations and actionable guidance.',
            str_contains($label, 'Feedback') => 'Add the reusable local-only helpfulness component.',
            str_contains($label, 'External links') || str_contains($label, 'Internal links resolve') => 'Repair or replace the listed broken URL and verify the destination.',
            str_contains($label, 'Unique') => 'Rewrite the duplicated element so it accurately describes '.$page['name'].' specifically.',
            default => 'Review the '.$label.' check and add the missing page-specific implementation.',
        };
    }

    private function summary(array $pages): array
    {
        $scores = collect($pages)->pluck('score');
        $metrics = collect($pages)->pluck('metrics');
        $warnings = collect($pages)->whereBetween('score', [50, 89])->count();
        $critical = collect($pages)->where('score', '<', 50)->count();

        return [
            'total_pages' => count($pages),
            'healthy_pages' => collect($pages)->where('score', '>=', 90)->count(),
            'warnings' => $warnings,
            'critical_issues' => $critical,
            'average_score' => (int) round($scores->avg() ?: 0),
            'average_word_count' => (int) round($metrics->avg('word_count') ?: 0),
            'average_internal_links' => round((float) ($metrics->avg('internal_links') ?: 0), 1),
            'average_faq_count' => round((float) ($metrics->avg('faq_count') ?: 0), 1),
        ];
    }

    private function duplicateGroups(array $pages): array
    {
        $groups = [];
        foreach (['title', 'meta_description', 'h1'] as $field) {
            $duplicates = collect($pages)->groupBy($field)->filter(fn ($items, $value) => $value !== '' && $items->count() > 1);
            foreach ($duplicates as $value => $items) {
                $groups[] = ['field' => $field, 'value' => $value, 'pages' => $items->pluck('path')->all()];
            }
        }

        return $groups;
    }

    private function contentDepthScore(int $words, int $faqs, int $links, string $type): int
    {
        $wordTarget = in_array($type, ['blog', 'tool', 'comparison', 'topic', 'category'], true) ? 1500 : 500;

        return min(100, (int) round(min(1, $words / $wordTarget) * 60 + min(1, $faqs / 10) * 20 + min(1, $links / 10) * 20));
    }

    private function healthLabel(int $score): string
    {
        return match (true) {
            $score >= 90 => 'Excellent',
            $score >= 75 => 'Good',
            $score >= 50 => 'Needs Improvement',
            default => 'Critical',
        };
    }

    private function check(string $label, bool $pass, ?string $detail = null): array
    {
        return ['label' => $label, 'pass' => $pass, 'detail' => $detail];
    }

    private function cleanText(string $text): string
    {
        return trim(preg_replace('/\s+/u', ' ', $text) ?? '');
    }

    private function normalizePath(string $url): string
    {
        $path = parse_url($url, PHP_URL_PATH) ?: '/';
        $base = rtrim(request()->getBaseUrl(), '/');
        if ($base !== '' && str_starts_with($path, $base)) {
            $path = substr($path, strlen($base)) ?: '/';
        }

        return '/'.ltrim(rtrim($path, '/'), '/') ?: '/';
    }

    private function isOfficial(string $url): bool
    {
        $host = Str::lower((string) parse_url($url, PHP_URL_HOST));

        return collect(config('site_audit.official_hosts', []))->contains(fn (string $official) => $host === $official || str_ends_with($host, '.'.$official));
    }
}

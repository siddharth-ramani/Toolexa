<?php

namespace App\Services\AdSense;

use App\Services\SiteAudit\LinkValidationService;
use App\Services\SiteAudit\SiteAuditService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdSenseReadinessService
{
    public function __construct(
        private readonly SiteAuditService $siteAudit,
        private readonly LinkValidationService $links
    ) {}

    public function report(bool $refresh = false): array
    {
        $key = 'adsense-readiness:v1:'.$this->siteAudit->cacheKey();
        if ($refresh) {
            Cache::forget($key);
        }

        $report = Cache::remember(
            $key,
            now()->addHours((int) config('adsense_validator.cache_hours', 6)),
            fn () => $this->build($this->siteAudit->report($refresh))
        );

        if ($refresh) {
            $this->storeHistory($report);
        }

        $report['history'] = $this->history();

        return $report;
    }

    public function history(): array
    {
        if (! Storage::disk('local')->exists('private/adsense-validator-history.json')) {
            return [];
        }

        $history = json_decode((string) Storage::disk('local')->get('private/adsense-validator-history.json'), true);

        return is_array($history) ? $history : [];
    }

    private function build(array $audit): array
    {
        $pages = collect($audit['pages']);
        $modules = [
            'trust' => $this->trustChecks($pages),
            'content' => $this->baseModuleChecks($pages, 'content', 'Content quality'),
            'tool_quality' => $this->qualityChecks($pages, 'tool'),
            'blog_quality' => $this->qualityChecks($pages, 'blog'),
            'comparison_quality' => $this->qualityChecks($pages, 'comparison'),
            'topic_quality' => $this->qualityChecks($pages, ['topic', 'category']),
            'technical_seo' => $this->combinedModuleChecks($pages, ['metadata', 'structured_data'], 'Technical SEO'),
            'performance' => $this->performanceChecks($pages),
            'accessibility' => $this->baseModuleChecks($pages, 'accessibility', 'Accessibility'),
            'mobile' => $this->mobileChecks($pages),
            'internal_linking' => $this->baseModuleChecks($pages, 'internal_linking', 'Internal linking'),
            'policy_compliance' => $this->policyChecks($pages),
        ];

        $moduleScores = collect($modules)->map(fn (array $checks) => $this->score($checks))->all();
        $score = $this->overallScore($moduleScores);
        $actions = $this->actions($modules);
        $critical = collect($actions)->where('priority', 'Critical')->count();
        $status = match (true) {
            $score >= (int) config('adsense_validator.ready_score', 90) && $critical === 0 => 'ready',
            $score >= (int) config('adsense_validator.almost_ready_score', 75) => 'almost_ready',
            default => 'not_ready',
        };

        return [
            'generated_at' => now()->toIso8601String(),
            'source_generated_at' => $audit['generated_at'],
            'score' => $score,
            'status' => $status,
            'status_label' => match ($status) {
                'ready' => 'Ready For AdSense Review',
                'almost_ready' => 'Almost Ready',
                default => 'Not Ready',
            },
            'status_reason' => $this->statusReason($status, $score, $actions),
            'modules' => $modules,
            'module_scores' => $moduleScores,
            'actions' => $actions,
            'summary' => [
                'total_pages' => $audit['summary']['total_pages'],
                'healthy_pages' => $audit['summary']['healthy_pages'],
                'critical_issues' => $critical,
                'warnings' => collect($actions)->whereIn('priority', ['High', 'Medium'])->count(),
                'passed_checks' => collect($modules)->flatten(1)->where('pass', true)->count(),
                'total_checks' => collect($modules)->flatten(1)->count(),
            ],
            'pages' => $this->pageReadiness($audit['pages']),
        ];
    }

    private function trustChecks($pages): array
    {
        $required = [
            '/about' => 'About page', '/contact' => 'Contact page', '/privacy-policy' => 'Privacy Policy',
            '/terms' => 'Terms', '/disclaimer' => 'Disclaimer', '/editorial-policy' => 'Editorial Policy',
            '/accuracy-policy' => 'Accuracy Policy', '/trust' => 'Trust Center',
            '/how-we-test-tools' => 'How We Test Tools',
        ];
        $checks = [];
        foreach ($required as $path => $label) {
            $page = $pages->firstWhere('path', $path);
            $checks[] = $this->check($label.' is published', $page && ($page['status_code'] ?? 0) === 200, $page ? $path : 'Missing', $page ? [] : [$path]);
        }
        foreach (['/sitemap.xml' => 'XML sitemap', '/robots.txt' => 'robots.txt'] as $path => $label) {
            $result = $this->links->internal($path);
            $checks[] = $this->check($label.' resolves', $result['ok'], 'HTTP '.($result['status'] ?? 'error'), $result['ok'] ? [] : [$path]);
        }
        $publisher = preg_replace('/^ca-/', '', trim((string) config('services.google.adsense_publisher_id')));
        $ads = $this->links->internal('/ads.txt');
        $checks[] = $this->check('ads.txt is configured', $ads['ok'] && (bool) preg_match('/^pub-\d+$/', $publisher), $publisher ?: 'Publisher ID missing', $ads['ok'] ? [] : ['/ads.txt']);
        $checks = array_merge($checks, $this->baseModuleChecks($pages, 'trust', 'Trust signals'));

        return $checks;
    }

    private function qualityChecks($pages, string|array $types): array
    {
        $types = (array) $types;
        $selected = $pages->whereIn('type', $types);
        $signals = collect($types)->flatMap(fn (string $type) => config('adsense_validator.quality_signals.'.$type, []))->unique();
        $labels = config('adsense_validator.signal_labels', []);
        $checks = [];
        foreach ($signals as $signal) {
            $failed = $selected->filter(fn (array $page) => ! ($page['signals'][$signal] ?? false))->pluck('path')->values()->all();
            $checks[] = $this->check(
                ($labels[$signal] ?? Str::headline($signal)).' present',
                count($failed) === 0,
                count($failed).' of '.$selected->count().' pages missing',
                $failed
            );
        }

        return $checks ?: [$this->check('Applicable pages available', true, 'No matching public pages')];
    }

    private function performanceChecks($pages): array
    {
        $checks = $this->baseModuleChecks($pages, 'performance', 'Performance');
        $checks[] = $this->signalCoverageCheck($pages, 'lazy_loading', 'Lazy loading safeguards');
        $checks[] = $this->signalCoverageCheck($pages, 'image_dimensions', 'CLS image-dimension safeguards');
        $checks[] = $this->check(
            'LCP and INP source safeguards',
            $pages->every(fn (array $page) => $this->modulePassed($page, 'performance', 'No blocking scripts')
                && ($page['signals']['keyboard_controls'] ?? false)),
            'Static validation of render-blocking scripts and native controls'
        );

        return $checks;
    }

    private function mobileChecks($pages): array
    {
        return [
            $this->signalCoverageCheck($pages, 'responsive_viewport', 'Responsive viewport'),
            $this->signalCoverageCheck($pages, 'keyboard_controls', 'Keyboard and touch-friendly native controls'),
            $this->check('Responsive CSS is loaded', is_file(public_path('assets/css/style.min.css')) && filesize(public_path('assets/css/style.min.css')) > 0, 'Production stylesheet'),
            $this->check('Bootstrap responsive foundation is loaded', is_file(public_path('assets/css/bootstrap-lite.min.css')), 'Bootstrap-compatible responsive asset'),
        ];
    }

    private function policyChecks($pages): array
    {
        $thin = $pages->filter(fn (array $page) => in_array($page['type'], ['tool', 'blog', 'comparison', 'topic', 'category'], true) && $page['metrics']['word_count'] < 700)->pluck('path')->all();
        $duplicate = $pages->filter(fn (array $page) => ! $this->allPassed($page['modules']['duplicates'] ?? []))->pluck('path')->all();
        $broken = $pages->filter(fn (array $page) => count($page['broken_links'] ?? []) > 0)->pluck('path')->all();
        $trust = $pages->filter(fn (array $page) => ! $this->allPassed($page['modules']['trust'] ?? []))->pluck('path')->all();

        return [
            $this->check('No thin primary content pages', count($thin) === 0, count($thin).' pages below 700 words', $thin),
            $this->check('No duplicate metadata or introductions', count($duplicate) === 0, count($duplicate).' affected pages', $duplicate),
            $this->check('No broken links or images', count($broken) === 0, count($broken).' affected pages', $broken),
            $this->check('Trust and legal links appear site-wide', count($trust) === 0, count($trust).' affected pages', $trust),
        ];
    }

    private function baseModuleChecks($pages, string $module, string $label): array
    {
        $labels = $pages->flatMap(fn (array $page) => collect($page['modules'][$module] ?? [])->pluck('label'))->unique();

        return $labels->map(function (string $checkLabel) use ($pages, $module, $label) {
            $failed = $pages->filter(fn (array $page) => ! $this->modulePassed($page, $module, $checkLabel))->pluck('path')->all();

            return $this->check($label.': '.$checkLabel, count($failed) === 0, count($failed).' of '.$pages->count().' pages affected', $failed);
        })->values()->all();
    }

    private function combinedModuleChecks($pages, array $modules, string $label): array
    {
        return collect($modules)->flatMap(fn (string $module) => $this->baseModuleChecks($pages, $module, $label))->values()->all();
    }

    private function signalCoverageCheck($pages, string $signal, string $label): array
    {
        $failed = $pages->filter(fn (array $page) => ! ($page['signals'][$signal] ?? false))->pluck('path')->all();

        return $this->check($label, count($failed) === 0, count($failed).' of '.$pages->count().' pages affected', $failed);
    }

    private function modulePassed(array $page, string $module, string $label): bool
    {
        $check = collect($page['modules'][$module] ?? [])->firstWhere('label', $label);

        return ! $check || (bool) $check['pass'];
    }

    private function allPassed(array $checks): bool
    {
        return collect($checks)->every(fn (array $check) => (bool) $check['pass']);
    }

    private function score(array $checks): int
    {
        return count($checks) === 0 ? 100 : (int) round(collect($checks)->where('pass', true)->count() / count($checks) * 100);
    }

    private function overallScore(array $scores): int
    {
        $weights = config('adsense_validator.weights', []);
        $totalWeight = max(1, array_sum($weights));
        $score = 0;
        foreach ($weights as $module => $weight) {
            $score += ($scores[$module] ?? 0) * $weight;
        }

        return (int) round($score / $totalWeight);
    }

    private function actions(array $modules): array
    {
        $actions = [];
        foreach ($modules as $module => $checks) {
            foreach ($checks as $check) {
                if ($check['pass']) {
                    continue;
                }
                $count = count($check['pages']);
                $priority = $this->priority($module, $check['label'], $count);
                $actions[] = [
                    'priority' => $priority,
                    'module' => Str::headline($module),
                    'issue' => $check['label'],
                    'action' => $this->actionFor($check['label']),
                    'pages' => $check['pages'],
                    'affected' => $count,
                ];
            }
        }
        $order = ['Critical' => 0, 'High' => 1, 'Medium' => 2, 'Low' => 3];

        return collect($actions)->sortBy(fn (array $item) => sprintf('%d-%05d', $order[$item['priority']], 99999 - $item['affected']))->values()->all();
    }

    private function priority(string $module, string $label, int $affected): string
    {
        if ($module === 'trust' && (str_contains($label, 'published') || str_contains($label, 'ads.txt'))) {
            return 'Critical';
        }
        if (str_contains($label, 'Title') || str_contains($label, 'Meta description') || str_contains($label, 'thin') || str_contains($label, 'Author')) {
            return 'Critical';
        }
        if (in_array($module, ['content', 'technical_seo', 'policy_compliance'], true) || $affected >= 10) {
            return 'High';
        }

        return $affected >= 3 ? 'Medium' : 'Low';
    }

    private function actionFor(string $label): string
    {
        return match (true) {
            str_contains($label, 'Official reference') => 'Add directly relevant government, standards-body, or official documentation sources.',
            str_contains($label, 'author') || str_contains($label, 'Author') => 'Add the configured author profile and visible attribution.',
            str_contains($label, 'review') || str_contains($label, 'Review') => 'Add the configured reviewer and accuracy-review metadata.',
            str_contains($label, 'FAQ') || str_contains($label, 'Frequently') => 'Add page-specific user questions with concise, useful answers and valid FAQ schema.',
            str_contains($label, 'thin') || str_contains($label, 'word count') => 'Expand affected pages with original explanations, examples, limitations, and practical guidance.',
            str_contains($label, 'duplicate') || str_contains($label, 'Unique') => 'Rewrite duplicated metadata or content so each page answers its own search intent.',
            str_contains($label, 'broken') || str_contains($label, 'resolve') => 'Repair or replace the affected links and verify the destination response.',
            str_contains($label, 'schema') => 'Add the page-type-specific JSON-LD schema to the shared SEO graph.',
            str_contains($label, 'ALT') => 'Add descriptive alternative text to meaningful images.',
            str_contains($label, 'dimension') => 'Add explicit image width and height to prevent layout shifts.',
            default => 'Add or correct the missing signal on every listed page, then refresh the validator.',
        };
    }

    private function pageReadiness(array $pages): array
    {
        return collect($pages)->map(function (array $page) {
            $failed = collect($page['modules'])->flatten(1)->where('pass', false)->count();

            return [
                'name' => $page['name'], 'path' => $page['path'], 'type' => $page['type'],
                'score' => $page['score'], 'health' => $page['health'],
                'failed_checks' => $failed, 'word_count' => $page['metrics']['word_count'],
            ];
        })->sortBy('score')->values()->all();
    }

    private function statusReason(string $status, int $score, array $actions): string
    {
        $top = $actions[0]['issue'] ?? null;

        return match ($status) {
            'ready' => 'No critical readiness blockers were detected in the current rendered-page audit.',
            'almost_ready' => 'The site scored '.$score.'%, but remaining issues should be resolved before submitting. Highest priority: '.($top ?: 'review warnings').'.',
            default => 'Critical or high-impact requirements remain incomplete. Start with: '.($top ?: 'the listed action items').'.',
        };
    }

    private function check(string $label, bool $pass, string $detail = '', array $pages = []): array
    {
        return ['label' => $label, 'pass' => $pass, 'detail' => $detail, 'pages' => array_values(array_unique($pages))];
    }

    private function storeHistory(array $report): void
    {
        $history = $this->history();
        array_unshift($history, [
            'generated_at' => $report['generated_at'],
            'score' => $report['score'],
            'status' => $report['status'],
            'critical_issues' => $report['summary']['critical_issues'],
            'warnings' => $report['summary']['warnings'],
            'healthy_pages' => $report['summary']['healthy_pages'],
        ]);
        $history = array_slice($history, 0, (int) config('adsense_validator.history_limit', 20));
        Storage::disk('local')->put('private/adsense-validator-history.json', json_encode($history, JSON_PRETTY_PRINT));
    }
}

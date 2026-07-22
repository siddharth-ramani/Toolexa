<?php

namespace App\Services;

use App\Http\Controllers\Tools\HomeController;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class ComparisonService
{
    public function all(): array
    {
        return collect(config('comparisons.pairs', []))->map(fn (array $pair) => $this->build($pair['left'], $pair['right']))->filter()->values()->all();
    }

    public function find(string $slug): ?array
    {
        $pair = collect(config('comparisons.pairs', []))->first(fn (array $pair) => $pair['left'].'-vs-'.$pair['right'] === $slug);

        return $pair ? $this->build($pair['left'], $pair['right']) : null;
    }

    private function build(string $leftKey, string $rightKey): ?array
    {
        $fingerprint = substr(sha1(json_encode(config('comparisons'))), 0, 12);

        return Cache::remember('comparison:'.$fingerprint.':'.$leftKey.':'.$rightKey, now()->addHours(6), function () use ($leftKey, $rightKey) {
            $left = $this->subject($leftKey);
            $right = $this->subject($rightKey);
            if (! $left || ! $right) {
                return null;
            }

            $slug = $leftKey.'-vs-'.$rightKey;
            $title = $left['name'].' vs '.$right['name'];
            $rows = $this->rows($left, $right);
            $faqs = $this->faqs($left, $right);
            $linking = app(InternalLinkingService::class);
            $context = [
                'name' => $title,
                'slug' => 'compare-'.$slug,
                'category' => $left['category'] === $right['category'] ? $left['category'] : 'Comparison',
                'keywords' => implode(' ', [$left['name'], $right['name'], $left['summary'], $right['summary']]),
                'desc' => 'Compare '.$left['name'].' and '.$right['name'].' by features, advantages, disadvantages and best use cases.',
            ];

            return [
                'slug' => $slug,
                'title' => $title,
                'left' => $left,
                'right' => $right,
                'introduction' => $this->introduction($left, $right),
                'rows' => $rows,
                'winners' => $this->winners($rows, $left, $right),
                'faqs' => $faqs,
                'mistakes' => $this->mistakes($left, $right),
                'related_tools' => $linking->relatedToolsForTool($context, 8),
                'related_articles' => $linking->relatedArticlesForTool($context, 4),
                'meta_title' => $title.': Differences, Pros, Cons & Best Choice | Toolexa',
                'meta_description' => Str::limit('Compare '.$left['name'].' vs '.$right['name'].'. See key differences, advantages, disadvantages, best use cases and which option fits your needs.', 158, ''),
                'schema' => $this->schema($slug, $title, $left, $right, $faqs),
            ];
        });
    }

    private function subject(string $key): ?array
    {
        $subject = config('comparisons.subjects.'.$key);
        if ($subject) {
            $subject['key'] = $key;
            $subject['tool_data'] = HomeController::toolBySlug($subject['tool'] ?? '');

            return $subject;
        }

        $tool = collect(HomeController::tools())->first(fn (array $tool) => $tool['slug'] === $key || $tool['slug'] === $key.'-calculator');
        if (! $tool) {
            return null;
        }

        return [
            'key' => $key, 'name' => $tool['name'], 'category' => $tool['category'], 'tool' => $tool['slug'], 'tool_data' => $tool,
            'summary' => $tool['seo_description'], 'advantages' => array_slice($tool['features'] ?? [$tool['desc']], 0, 4),
            'disadvantages' => ['Results depend on correct inputs', 'Important outputs may require independent verification'],
            'use_cases' => array_slice($tool['how_to'] ?? [$tool['desc']], 0, 3),
            'attributes' => ['Purpose' => ['value' => $tool['desc'], 'score' => 4], 'Ease of use' => ['value' => 'Browser-based and straightforward', 'score' => 4], 'Cost' => ['value' => 'Free', 'score' => 5]],
        ];
    }

    private function rows(array $left, array $right): array
    {
        $labels = array_values(array_unique(array_merge(array_keys($left['attributes']), array_keys($right['attributes']))));

        return collect($labels)->map(fn (string $label) => [
            'label' => $label,
            'left' => $left['attributes'][$label] ?? ['value' => 'Not applicable', 'score' => 0],
            'right' => $right['attributes'][$label] ?? ['value' => 'Not applicable', 'score' => 0],
        ])->all();
    }

    private function winners(array $rows, array $left, array $right): array
    {
        return collect($rows)->filter(fn (array $row) => $row['left']['score'] !== $row['right']['score'])->take(4)->map(function (array $row) use ($left, $right) {
            $winner = $row['left']['score'] > $row['right']['score'] ? $left : $right;

            return ['label' => 'Best for '.$row['label'], 'winner' => $winner['name'], 'value' => $row[$winner['key'] === $left['key'] ? 'left' : 'right']['value']];
        })->values()->all();
    }

    private function introduction(array $left, array $right): string
    {
        return $left['name'].' and '.$right['name'].' solve related needs, but they prioritize different strengths. '.$left['summary'].' '.$right['summary'].' This comparison explains the practical differences so you can choose according to your workflow rather than treating either option as universally better.';
    }

    private function mistakes(array $left, array $right): array
    {
        return [
            'Choosing '.$left['name'].' or '.$right['name'].' only because it is familiar, without checking the actual output or goal.',
            'Comparing one feature in isolation while ignoring compatibility, quality, risk, size or long-term requirements.',
            'Using unrealistic inputs or low-quality source material and expecting the selected option to correct the underlying problem.',
            'Converting or switching repeatedly without keeping an original copy for verification.',
            'Treating estimates and general guidance as a substitute for professional or project-specific requirements.',
        ];
    }

    private function faqs(array $left, array $right): array
    {
        return [
            ['question' => 'What is the main difference between '.$left['name'].' and '.$right['name'].'?', 'answer' => $left['name'].' is primarily suited to '.Str::lower(implode(', ', array_slice($left['use_cases'], 0, 2))).', while '.$right['name'].' is commonly chosen for '.Str::lower(implode(', ', array_slice($right['use_cases'], 0, 2))).'.'],
            ['question' => 'Is '.$left['name'].' better than '.$right['name'].'?', 'answer' => 'Not in every situation. '.$left['name'].' is stronger when its advantages match your goal, while '.$right['name'].' is better when you need its specific strengths.'],
            ['question' => 'When should I choose '.$left['name'].'?', 'answer' => 'Choose '.$left['name'].' for '.Str::lower(implode(', ', $left['use_cases'])).'.'],
            ['question' => 'When should I choose '.$right['name'].'?', 'answer' => 'Choose '.$right['name'].' for '.Str::lower(implode(', ', $right['use_cases'])).'.'],
            ['question' => 'Which option is easier to use?', 'answer' => 'Compare the Ease of use row and your existing workflow. Familiar software, required inputs and the destination format or provider can affect the practical answer.'],
            ['question' => 'Can '.$left['name'].' and '.$right['name'].' be used together?', 'answer' => 'Often yes. Related formats, tools or financial approaches may support different stages or goals, provided you understand the implications of converting, combining or allocating between them.'],
            ['question' => 'Are there free Toolexa tools for this comparison?', 'answer' => 'Yes. Use the linked Toolexa tools on this page to calculate, convert or inspect relevant inputs directly in your browser.'],
            ['question' => 'How should I make the final choice?', 'answer' => 'Start with your required outcome, then compare compatibility, quality, cost, risk, file size or time horizon as applicable. Test a realistic example before committing to an important workflow.'],
        ];
    }

    private function schema(string $slug, string $title, array $left, array $right, array $faqs): array
    {
        $url = route('compare.show', $slug);
        $modified = date('Y-m-d', filemtime(config_path('comparisons.php')) ?: time());

        return [
            ['@context' => 'https://schema.org', '@type' => 'Article', 'headline' => $title, 'description' => 'A detailed comparison of '.$left['name'].' and '.$right['name'].'.', 'url' => $url, 'dateModified' => $modified, 'author' => ['@type' => 'Organization', 'name' => 'Toolexa Editorial Team'], 'publisher' => ['@type' => 'Organization', 'name' => 'Toolexa', 'url' => url('/')]],
            ['@context' => 'https://schema.org', '@type' => 'BreadcrumbList', 'itemListElement' => [
                ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => url('/')],
                ['@type' => 'ListItem', 'position' => 2, 'name' => 'Comparisons', 'item' => route('compare.index')],
                ['@type' => 'ListItem', 'position' => 3, 'name' => $title, 'item' => $url],
            ]],
            ['@context' => 'https://schema.org', '@type' => 'FAQPage', 'mainEntity' => collect($faqs)->map(fn (array $faq) => ['@type' => 'Question', 'name' => $faq['question'], 'acceptedAnswer' => ['@type' => 'Answer', 'text' => $faq['answer']]])->all()],
        ];
    }
}

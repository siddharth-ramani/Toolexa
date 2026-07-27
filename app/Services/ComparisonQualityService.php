<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class ComparisonQualityService
{
    public function build(array $comparison, array $catalog): array
    {
        $override = config('comparison_quality.comparisons.'.$comparison['slug'], []);
        $fingerprint = substr(sha1(json_encode([$comparison, $override, array_column($catalog, 'slug')])), 0, 16);

        return Cache::remember(
            'comparison-quality:v1:'.$comparison['slug'].':'.$fingerprint,
            now()->addHours((int) config('comparison_quality.cache_hours', 6)),
            fn () => $this->compile($comparison, $catalog, $override)
        );
    }

    private function compile(array $comparison, array $catalog, array $override): array
    {
        $left = $comparison['left'];
        $right = $comparison['right'];
        $category = $left['category'] === $right['category'] ? $left['category'] : $left['category'];
        $profile = config('comparison_quality.categories.'.$category, $this->defaultProfile());
        $faqs = $override['faqs'] ?? $this->faqs($comparison, $profile);
        $content = [
            'recommendations' => $override['recommendations'] ?? $this->recommendations($comparison),
            'summary' => $override['summary'] ?? $this->summary($left, $right, $profile),
            'explanations' => $override['explanations'] ?? [
                'left' => $this->explanation($left, $profile),
                'right' => $this->explanation($right, $profile),
            ],
            'examples' => $override['examples'] ?? $this->examples($left, $right),
            'decisions' => $override['decisions'] ?? [
                'left' => $this->decision($left, $right),
                'right' => $this->decision($right, $left),
            ],
            'mistakes' => $override['mistakes'] ?? $comparison['mistakes'],
            'faqs' => $faqs,
            'references' => $override['references'] ?? $profile['references'],
            'related_comparisons' => $this->related($comparison, $catalog),
            'published_at' => $override['published_at'] ?? config('comparison_quality.default_published_at'),
            'content_version' => $override['content_version'] ?? config('comparison_quality.content_version', '1.0'),
            'version_history' => $override['version_history'] ?? [],
        ];
        $content['word_count'] = $this->wordCount($comparison, $content);
        $content['reading_time'] = max(1, (int) ceil($content['word_count'] / 220));

        return $content;
    }

    private function recommendations(array $comparison): array
    {
        $left = $comparison['left'];
        $right = $comparison['right'];
        $rows = collect($comparison['rows']);
        $criteria = [
            'Best Overall Fit' => null,
            'Best for Beginners' => ['ease', 'learning'],
            'Best Performance' => ['performance', 'speed', 'compression', 'parsing', 'return potential'],
            'Best Compatibility' => ['compatibility', 'browser', 'web use'],
            'Best Long-Term Choice' => ['future', 'time horizon', 'quality', 'validation'],
        ];

        return collect($criteria)->map(function (?array $needles, string $label) use ($rows, $left, $right) {
            $matching = $needles
                ? $rows->filter(fn (array $row) => collect($needles)->contains(fn (string $needle) => str_contains(Str::lower($row['label']), $needle)))
                : $rows;
            if ($matching->isEmpty()) {
                $matching = $rows;
            }
            $leftScore = $matching->sum(fn (array $row) => $row['left']['score']);
            $rightScore = $matching->sum(fn (array $row) => $row['right']['score']);
            $winner = $leftScore === $rightScore ? null : ($leftScore > $rightScore ? $left : $right);
            $reasonRow = $matching->sortByDesc(fn (array $row) => abs($row['left']['score'] - $row['right']['score']))->first();

            return [
                'label' => $label,
                'winner' => $winner['name'] ?? 'Tie — depends on your needs',
                'reason' => $winner
                    ? $winner['name'].' has the stronger fit for '.$reasonRow['label'].': '.$reasonRow[$winner['key'] === $left['key'] ? 'left' : 'right']['value'].'.'
                    : 'Both options score equally across the relevant configured criteria, so the use case should decide.',
            ];
        })->values()->all();
    }

    private function summary(array $left, array $right, array $profile): array
    {
        return [
            'left' => [
                'title' => 'Choose '.$left['name'].' if',
                'text' => 'Your priority is '.Str::lower(implode(', ', array_slice($left['use_cases'], 0, 2))).'. Its strongest practical advantages are '.Str::lower(implode(' and ', array_slice($left['advantages'], 0, 2))).'.',
            ],
            'right' => [
                'title' => 'Choose '.$right['name'].' if',
                'text' => 'Your priority is '.Str::lower(implode(', ', array_slice($right['use_cases'], 0, 2))).'. Its strongest practical advantages are '.Str::lower(implode(' and ', array_slice($right['advantages'], 0, 2))).'.',
            ],
            'context' => 'The final choice should account for '.$profile['context'].'. Neither option is universally better.',
        ];
    }

    private function explanation(array $subject, array $profile): array
    {
        return [
            'overview' => $subject['summary'],
            'history' => $subject['name'].' became established because it addressed a recurring need in '.$subject['category'].'. Its continued use is tied to '.Str::lower(implode(', ', array_slice($subject['advantages'], 0, 2))).'.',
            'how_it_works' => $subject['name'].' applies the characteristics shown in the comparison table to produce or support its intended outcome. In practice, the source input, chosen settings and destination requirements determine whether those characteristics are beneficial.',
            'where_used' => $subject['name'].' is commonly used for '.Str::lower(implode(', ', $subject['use_cases'])).'. Evaluate it in a realistic workflow rather than relying on a label or headline score alone.',
            'context' => 'When assessing '.$subject['name'].', consider '.$profile['context'].'.',
        ];
    }

    private function examples(array $left, array $right): array
    {
        return collect([$left, $right])->flatMap(fn (array $subject) => collect($subject['use_cases'])->take(2)->map(fn (string $useCase) => [
            'option' => $subject['name'],
            'scenario' => $useCase,
            'explanation' => $subject['name'].' is a practical fit for '.Str::lower($useCase).' because '.Str::lower($subject['advantages'][0]).'. Confirm the actual constraints before using it for an important project.',
        ]))->values()->all();
    }

    private function decision(array $subject, array $alternative): array
    {
        return [
            'choose_if' => array_values(array_unique(array_merge($subject['use_cases'], array_slice($subject['advantages'], 0, 3)))),
            'reconsider_if' => array_map(fn (string $limitation) => $limitation.' matters more than the benefits for your workflow.', array_slice($subject['disadvantages'], 0, 3)),
            'alternative' => 'Choose '.$alternative['name'].' instead when its strengths match the destination, risk level or output requirement more closely.',
        ];
    }

    private function faqs(array $comparison, array $profile): array
    {
        $left = $comparison['left'];
        $right = $comparison['right'];
        $extra = [
            ['question' => 'Which is better for beginners: '.$left['name'].' or '.$right['name'].'?', 'answer' => 'Check the Ease of use or learning-related row. The simpler choice depends on the tools you already use and the output your destination accepts.'],
            ['question' => 'Which option offers better long-term support?', 'answer' => 'Look at compatibility, standards support, ecosystem adoption and whether your destination workflow actively accepts the option. Long-term suitability can change, so verify current requirements.'],
            ['question' => 'Are there security differences between '.$left['name'].' and '.$right['name'].'?', 'answer' => 'Security depends on implementation, source data and handling as well as format or product choice. Use trusted software, validate inputs and avoid exposing confidential information.'],
            ['question' => 'Can I switch between '.$left['name'].' and '.$right['name'].' later?', 'answer' => 'Often yes, but conversion, withdrawal, migration or repeated processing may introduce quality loss, fees, tax effects or missing information. Keep the original source and test first.'],
            ['question' => 'How was this comparison evaluated?', 'answer' => 'The page compares purpose, practical features, limitations, use cases and category-specific criteria. Recommendations are criterion-based and do not claim one universal winner.'],
            ['question' => 'What should I verify before making the final choice?', 'answer' => 'Verify '.$profile['context'].', then test a realistic scenario and consult an official source when rules or standards may have changed.'],
            ['question' => 'Does price decide which option is better?', 'answer' => 'Not by itself. Price or cost matters only alongside suitability, risk, quality, compatibility, time and the cost of changing later.'],
        ];

        return collect(array_merge($comparison['faqs'], $extra))->unique('question')->take(15)->values()->all();
    }

    private function related(array $current, array $catalog): array
    {
        $keys = [$current['left']['key'], $current['right']['key']];
        $categories = [$current['left']['category'], $current['right']['category']];

        return collect($catalog)->reject(fn (array $item) => $item['slug'] === $current['slug'])
            ->map(function (array $item) use ($keys, $categories) {
                $subjects = [$item['left']['key'], $item['right']['key']];
                $item['_score'] = count(array_intersect($keys, $subjects)) * 4
                    + count(array_intersect($categories, [$item['left']['category'], $item['right']['category']])) * 2;

                return $item;
            })->filter(fn (array $item) => $item['_score'] > 0)->sortByDesc('_score')->take(4)
            ->map(function (array $item) {
                unset($item['_score']);

                return $item;
            })->values()->all();
    }

    private function wordCount(array $comparison, array $content): int
    {
        $editorialContent = [
            $comparison['title'],
            $comparison['introduction'],
            $comparison['rows'],
            $comparison['left']['summary'],
            $comparison['left']['advantages'],
            $comparison['left']['disadvantages'],
            $comparison['left']['use_cases'],
            $comparison['right']['summary'],
            $comparison['right']['advantages'],
            $comparison['right']['disadvantages'],
            $comparison['right']['use_cases'],
            $content['recommendations'],
            $content['summary'],
            $content['explanations'],
            $content['examples'],
            $content['decisions'],
            $content['mistakes'],
            $content['faqs'],
        ];

        return str_word_count(strip_tags(json_encode($editorialContent)));
    }

    private function defaultProfile(): array
    {
        return [
            'context' => 'the actual goal, limitations, compatibility, cost and consequences of changing later',
            'references' => [],
        ];
    }
}

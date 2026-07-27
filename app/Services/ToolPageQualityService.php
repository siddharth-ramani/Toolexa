<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class ToolPageQualityService
{
    public function build(array $tool): array
    {
        $fingerprint = substr(sha1(json_encode([
            config('tool_quality.version'),
            config('tool_quality.overrides.'.$tool['slug']),
            $tool['slug'],
            $tool['formula'] ?? null,
            $tool['fields'] ?? null,
        ])), 0, 12);

        return Cache::remember('tool-quality:'.$fingerprint, now()->addDay(), fn () => $this->generate($tool));
    }

    private function generate(array $tool): array
    {
        $override = config('tool_quality.overrides.'.$tool['slug'], []);
        $profile = config('tool_quality.category_profiles.'.$tool['category'], [
            'audience' => 'students, professionals and everyday users',
            'application' => 'practical browser-based tasks and productivity',
        ]);

        $quality = [
            'introduction' => $override['introduction'] ?? $this->introduction($tool, $profile),
            'steps' => $override['steps'] ?? $this->steps($tool),
            'steps_note' => $override['steps_note'] ?? null,
            'examples' => $override['examples'] ?? $this->examples($tool, $profile),
            'formula' => $this->formula($tool, $override),
            'detailed_explanation' => $override['detailed_explanation'] ?? $this->detailedExplanation($tool, $profile),
            'advantages' => $override['advantages'] ?? $this->advantages($tool),
            'limitations' => $override['limitations'] ?? $this->limitations($tool),
            'mistakes' => $override['mistakes'] ?? $this->mistakes($tool),
            'best_practices' => $override['best_practices'] ?? $this->bestPractices($tool),
            'faqs' => $override['faqs'] ?? $this->faqs($tool),
            'references' => $override['references'] ?? $this->references($tool),
            'comparisons' => $this->comparisons($tool),
        ];

        $quality['reading_time'] = max(1, (int) ceil(
            str_word_count(strip_tags(collect($quality)->flatten()->filter(fn ($value) => is_string($value))->implode(' ')))
            / (int) config('tool_quality.words_per_minute', 220)
        ));

        return $quality;
    }

    private function introduction(array $tool, array $profile): string
    {
        $fields = $this->fieldNames($tool);
        $inputContext = $fields
            ? 'The main inputs include '.Str::lower($this->humanList($fields)).', so each value can be reviewed before the result is produced.'
            : 'The interface keeps the required input and output in one focused workflow, making it easier to review the result before using or downloading it.';

        return $tool['name'].' is a free online '.$tool['category'].' tool that helps you '.Str::lower(rtrim($tool['desc'], '.')).'. It is designed for '.$profile['audience'].' who need a clear result without installing specialist software or building a separate spreadsheet. '.$inputContext.' Use '.$tool['name'].' when you are working on '.$profile['application'].'. It is particularly useful when you want to test more than one scenario, check a manual result, prepare information for a report, or understand how changing an input affects the output. The tool works in a modern browser and uses a responsive layout, so the same workflow is available on desktop, tablet and mobile screens. Results are shown in a structured form that can be read, copied or used as the starting point for the next task. No account is required. Before relying on an important result, check that every input, unit, format and option matches the real situation. The educational sections below explain the workflow, practical examples, relevant formulas, limitations and reliable ways to verify the output.';
    }

    private function steps(array $tool): array
    {
        $steps = collect($tool['how_to'] ?? [])->take(3)->values()->all();
        $defaults = [
            'Review the input labels and enter the value or source content required by '.$tool['name'].'.',
            'Choose any unit, rate, format or processing option that matches your task.',
            'Select the calculate, convert or generate action to create the result.',
            'Review the output, then copy, download or compare it before using it elsewhere.',
        ];

        return array_slice(array_values(array_unique(array_merge($steps, $defaults))), 0, 5);
    }

    private function examples(array $tool, array $profile): array
    {
        $fields = $this->fieldNames($tool);
        $primary = $fields[0] ?? 'source input';
        $secondary = $fields[1] ?? 'selected option';
        $name = $tool['name'];

        return [
            ['title' => 'Quick everyday check', 'input' => $primary.': a representative everyday value', 'action' => 'Select the relevant '.$secondary.' and run '.$name.'.', 'outcome' => 'Review a clear result before continuing the task.'],
            ['title' => 'Compare two scenarios', 'input' => 'Use the same '.$primary.' in two separate runs', 'action' => 'Change only '.$secondary.' for the second run.', 'outcome' => 'See how one controlled change affects the output.'],
            ['title' => 'Work or study example', 'input' => 'Use a realistic value from '.$profile['application'], 'action' => 'Process it and check the units or output format.', 'outcome' => 'Use the verified result in notes, a report or the next workflow step.'],
            ['title' => 'Boundary check', 'input' => 'Try the smallest realistic value, then a larger valid value', 'action' => 'Run '.$name.' after checking both inputs.', 'outcome' => 'Confirm that the result behaves consistently across the expected range.'],
        ];
    }

    private function formula(array $tool, array $override): ?array
    {
        if (empty($tool['formula']['items'])) {
            return null;
        }

        $variables = $override['formula_variables'] ?? collect($this->fieldNames($tool))->map(fn (string $field) => [
            'symbol' => $field,
            'meaning' => 'The '.$field.' value entered or selected in the tool.',
        ])->take(6)->all();

        return [
            'title' => $tool['formula']['title'],
            'items' => $tool['formula']['items'],
            'explanation' => $tool['formula']['explanation'] ?? 'The formula applies the entered values in the order shown to produce the displayed result.',
            'variables' => $variables,
        ];
    }

    private function detailedExplanation(array $tool, array $profile): array
    {
        $name = $tool['name'];
        $description = rtrim($tool['desc'], '.');
        $fields = $this->fieldNames($tool);
        $fieldText = $fields ? $this->humanList($fields) : 'the source input and selected settings';
        $formula = ! empty($tool['formula']['items'])
            ? 'The calculation follows '.implode(' and ', array_slice($tool['formula']['items'], 0, 2)).'.'
            : 'The tool applies a defined browser-side transformation or validation process instead of a financial or mathematical formula.';

        return [
            "{$name} begins with a focused purpose: to {$description}. The page separates the inputs from the output so you can confirm what is being processed. In practice, the quality of the result depends first on the quality of the source information. Review {$fieldText} carefully, because a technically correct operation can still produce an unsuitable answer when a value, unit, date, encoding or format is wrong.",
            "{$formula} The interface converts that underlying method into a repeatable workflow. Validation checks are used where possible to identify missing or unsupported input before processing. Once the action is started, the tool applies the same rules consistently and presents the result in a readable format. This consistency makes it useful for checking manual work and comparing scenarios, but it does not remove the need to understand the assumptions behind the task.",
            "The tool matters because {$profile['application']} often involves small decisions that can affect later work. A percentage entered in the wrong field, an incorrect file format, or an unsuitable conversion option can create errors that are difficult to notice downstream. Using {$name} gives you a dedicated place to test the task, inspect the output and repeat the process with one controlled change at a time.",
            "Common use cases include individual checks, classroom examples, client or internal reporting, content preparation and quality assurance. {$name} can also support professionals who already know the method but want a faster second check. It should be treated as a practical assistant rather than an unexplained answer box: read the labels, keep the original source available and compare important output with an official reference or trusted independent method.",
            'In an industry workflow, the result may become an input for accounting, publishing, development, ecommerce, documentation or communication. That is why format and context are as important as the visible output. If another system expects a specific precision, unit, character encoding, browser capability or document standard, confirm those requirements before transferring the result. A successful browser operation does not guarantee acceptance by every external provider.',
            "For the most reliable use, start with a small representative example whose expected outcome you understand. Run {$name}, compare the output, and only then process a larger or more important case. Change one input at a time when comparing alternatives. Save or copy the result only after checking it, and avoid entering confidential information unless the tool clearly supports an appropriate local workflow. These habits make the result easier to explain, reproduce and verify.",
        ];
    }

    private function advantages(array $tool): array
    {
        return array_slice(array_values(array_unique(array_merge($tool['features'] ?? [], [
            'Provides a repeatable workflow for '.$tool['name'],
            'Makes inputs and results easier to review',
            'Supports quick scenario comparison without a spreadsheet',
            'Works in a responsive browser interface',
            'Includes examples, limitations and verification guidance',
            'Connects to related Toolexa tools and educational resources',
        ]))), 0, 10);
    }

    private function limitations(array $tool): array
    {
        $items = [
            $tool['name'].' depends on accurate, complete and correctly formatted user input.',
            'The output may use common assumptions that differ from a provider, institution or external system.',
            'Browser, device, file-size and memory limits can affect advanced processing tasks.',
            'Important results should be checked with an official source or a second reliable method.',
            'The tool provides general information and does not replace professional financial, legal, tax or technical advice.',
        ];
        if (! empty($tool['formula'])) {
            $items[] = 'Rounding at different stages can create small differences from another calculator.';
        }

        return $items;
    }

    private function mistakes(array $tool): array
    {
        $fields = $this->fieldNames($tool);
        $first = $fields[0] ?? 'source input';

        return [
            'Entering the right value in the wrong field or using an unsuitable unit.',
            'Processing '.$first.' without checking for typing, formatting or source errors.',
            'Changing several inputs at once and then being unable to explain the result difference.',
            'Assuming a successful result is automatically accepted by every external service.',
            'Using rounded, incomplete or outdated values for an important decision.',
            'Skipping the final review before copying, downloading or sharing the output.',
        ];
    }

    private function bestPractices(array $tool): array
    {
        return [
            'Read every label and confirm the expected unit or format before starting.',
            'Test '.$tool['name'].' with a small example whose result you can independently check.',
            'Change one input at a time when comparing scenarios.',
            'Keep the original values or source file until the output has been verified.',
            'Use current official rates, standards or provider requirements when they apply.',
            'Avoid entering confidential information unless the workflow is clearly appropriate.',
            'Record important assumptions when using the result in a report or business process.',
        ];
    }

    private function faqs(array $tool): array
    {
        $faqs = collect($tool['faq'] ?? [])->map(fn (array $faq) => [
            'question' => $faq['q'] ?? $faq['question'],
            'answer' => $faq['a'] ?? $faq['answer'],
        ])->all();
        $name = $tool['name'];
        $extra = [
            ['question' => 'Who should use '.$name.'?', 'answer' => $name.' is useful for people who need '.$tool['desc'].' It supports quick checks, learning and repeatable professional workflows.'],
            ['question' => 'How can I verify the '.$name.' result?', 'answer' => 'Recheck every input and unit, test a simple known example, and compare important output with an official source or another reliable method.'],
            ['question' => 'Does '.$name.' work on mobile devices?', 'answer' => 'Yes. The interface is responsive and designed for current desktop, tablet and mobile browsers.'],
            ['question' => 'Does Toolexa save my '.$name.' input?', 'answer' => 'The page does not create a personal account record from the values you enter. Avoid confidential data unless you understand the processing method and associated risk.'],
            ['question' => 'Why might another tool show a different result?', 'answer' => 'Differences can come from input values, units, precision, rounding, assumptions, rates, formats or the order in which operations are applied.'],
            ['question' => 'Can I use '.$name.' for professional work?', 'answer' => 'You can use it as a productivity and checking aid, but you remain responsible for verifying the output against the standards and requirements of your profession or provider.'],
            ['question' => 'Is '.$name.' free?', 'answer' => 'Yes. You can use the tool in your browser without creating an account or purchasing a subscription.'],
            ['question' => 'What should I do if the result looks wrong?', 'answer' => 'Check the inputs first. If the issue remains, report the page URL, inputs, expected output and browser details through the Toolexa Contact page.'],
        ];

        return array_slice(collect(array_merge($faqs, $extra))->unique('question')->values()->all(), 0, 12);
    }

    private function references(array $tool): array
    {
        $slug = $tool['slug'];
        if (str_contains($slug, 'gst')) {
            return config('tool_quality.references.gst', []);
        }
        if (str_contains($slug, 'epf') || str_contains($slug, 'provident')) {
            return config('tool_quality.references.epf', []);
        }
        if (str_contains($slug, 'nps')) {
            return config('tool_quality.references.nps', []);
        }
        if ($tool['category'] === 'Finance') {
            return config('tool_quality.references.finance', []);
        }
        if ($tool['category'] === 'Security Tools') {
            return config('tool_quality.references.security', []);
        }
        if ($tool['category'] === 'PDF Tools') {
            return config('tool_quality.references.pdf', []);
        }
        if (in_array($tool['category'], ['Developer Tools', 'Web Tools', 'SEO Tools', 'Color Tools'], true)) {
            return config('tool_quality.references.web', []);
        }

        return [];
    }

    private function comparisons(array $tool): array
    {
        return collect(app(ComparisonService::class)->all())
            ->filter(function (array $comparison) use ($tool) {
                $subjects = [$comparison['left'], $comparison['right']];

                return collect($subjects)->contains(fn (array $subject) => ($subject['tool_data']['slug'] ?? null) === $tool['slug']
                    || $subject['category'] === $tool['category']
                    || str_contains(Str::lower($comparison['title']), Str::lower(str_replace([' Calculator', ' Converter', ' Tool'], '', $tool['name'])))
                );
            })
            ->take(4)
            ->values()
            ->all();
    }

    private function fieldNames(array $tool): array
    {
        return collect($tool['fields'] ?? [])
            ->pluck('label')
            ->filter()
            ->map(fn (string $label) => trim(preg_replace('/\s*\([^)]*\)\s*/', ' ', $label)))
            ->unique()
            ->take(6)
            ->values()
            ->all();
    }

    private function humanList(array $items): string
    {
        if (count($items) < 2) {
            return $items[0] ?? 'the required input';
        }

        $last = array_pop($items);

        return implode(', ', $items).' and '.$last;
    }
}

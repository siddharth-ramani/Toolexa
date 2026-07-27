<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class BlogQualityService
{
    public function build(array $article): array
    {
        $override = config('blog_quality.articles.'.$article['slug'], []);
        $fingerprint = substr(sha1(json_encode([$article, $override, config('blog_quality.categories')])), 0, 16);

        return Cache::remember(
            'blog-quality:v1:'.$article['slug'].':'.$fingerprint,
            now()->addHours((int) config('blog_quality.cache_hours', 6)),
            fn () => $this->compile($article, $override)
        );
    }

    private function compile(array $article, array $override): array
    {
        $profile = config('blog_quality.categories.'.$article['category'], $this->defaultProfile());
        $sections = $override['sections'] ?? $article['sections'];
        $introduction = $override['introduction'] ?? $this->introduction($article, $sections, $profile);
        $mainSections = collect($sections)
            ->reject(fn (array $section) => str_contains(Str::lower($section['heading']), 'introduction'))
            ->values()
            ->all();
        $faqs = $this->faqs($article, $override['faqs'] ?? $article['faqs'], $profile);

        return [
            'summary' => $override['summary'] ?? $this->summary($article, $sections),
            'introduction' => $introduction,
            'sections' => $mainSections,
            'examples' => $override['examples'] ?? $this->sectionItems($sections, ['example', 'scenario']),
            'steps' => $override['steps'] ?? $this->steps($article, $sections),
            'mistakes' => $override['mistakes'] ?? $this->mistakes($article, $sections, $profile),
            'blocks' => $override['blocks'] ?? $this->blocks($article, $sections, $profile),
            'faqs' => $faqs,
            'references' => $override['references'] ?? $this->references($article, $profile),
            'content_version' => $override['content_version'] ?? config('blog_quality.content_version', '1.0'),
            'version_history' => $override['version_history'] ?? [],
            'reading_time' => $this->readingTime($article, $introduction, $mainSections, $faqs),
        ];
    }

    private function introduction(array $article, array $sections, array $profile): array
    {
        $source = collect($sections)->flatMap(fn (array $section) => $section['paragraphs'])->values();
        $paragraphs = $source->take(4)->all();
        $paragraphs[] = $article['title'].' is relevant to '.$profile['audience'].'. This guide explains the core idea, shows how it applies in realistic situations and highlights the checks that matter before you act on the result.';
        $paragraphs[] = 'By the end, you will know how to '.$profile['goal'].'. You will also find practical tools, common mistakes, official references where applicable and answers to the questions readers most often ask.';

        return $this->fitIntroduction($paragraphs, $article, $profile);
    }

    private function fitIntroduction(array $paragraphs, array $article, array $profile): array
    {
        $paragraphs = array_values(array_unique(array_filter($paragraphs)));
        $words = str_word_count(implode(' ', $paragraphs));
        if ($words < 300) {
            $paragraphs[] = 'Use the examples as a method, not merely as answers to copy. Start with the stated assumptions, substitute your own values or source material, and compare the outcome with what you expected. That process makes the explanation useful beyond a single calculation or conversion.';
            $paragraphs[] = 'Toolexa keeps the learning path connected: read the explanation first, open a related free tool when you are ready to apply it, and return to the checklist before sharing or relying on the output. For consequential work, keep a record of the inputs and consult the appropriate authority.';
        }
        if (str_word_count(implode(' ', $paragraphs)) < 300) {
            $paragraphs[] = 'A careful workflow is more valuable than a fast answer alone. Pause when an output looks surprising, confirm the labels beside every input and repeat the example with simpler values. Being able to reproduce a result is one of the strongest checks that you have understood both the topic and the tool.';
        }

        return $paragraphs;
    }

    private function summary(array $article, array $sections): array
    {
        $takeaways = [$article['excerpt']];
        foreach ($sections as $section) {
            $first = $section['paragraphs'][0] ?? null;
            if ($first) {
                $takeaways[] = Str::of($first)->before('.')->append('.')->toString();
            }
        }

        return collect($takeaways)->filter()->unique()->take(5)->values()->all();
    }

    private function steps(array $article, array $sections): array
    {
        $matched = $this->sectionItems($sections, ['step-by-step', 'how to', 'guide']);
        if (count($matched) >= 3) {
            return array_slice($matched, 0, 6);
        }

        $tool = Str::headline($article['related_tools'][0] ?? '');

        return [
            'Define the exact question or output you need before entering any data.',
            'Collect the source values, rate, unit, format or settings mentioned in the guide.',
            ($tool ? 'Open the '.$tool.' and ' : '').'enter one realistic example without changing multiple assumptions at once.',
            'Review the result, compare it with a simple manual check and save the inputs when the decision is important.',
        ];
    }

    private function mistakes(array $article, array $sections, array $profile): array
    {
        $items = $this->sectionItems($sections, ['mistake', 'avoid', 'limitation']);
        $fallback = [
            'Using an input, unit or format that does not match the source information.',
            'Changing several assumptions together and then being unable to explain why the result changed.',
            'Treating an estimate or transformed output as final without checking it in the destination context.',
            $profile['warning'],
        ];

        return collect(array_merge($items, $fallback))->filter()->unique()->take(6)->values()->all();
    }

    private function blocks(array $article, array $sections, array $profile): array
    {
        $tips = $this->sectionItems($sections, ['tip', 'accurate', 'better', 'practice']);
        $example = $this->sectionItems($sections, ['example', 'scenario'])[0] ?? $article['excerpt'];

        return [
            ['type' => 'important', 'label' => 'Important Note', 'text' => $profile['warning']],
            ['type' => 'tip', 'label' => 'Pro Tip', 'text' => $tips[0] ?? 'Change one input at a time so you can see exactly what affects the output.'],
            ['type' => 'note', 'label' => 'Real-life context', 'text' => $example],
            ['type' => 'practice', 'label' => 'Best Practice', 'text' => 'Keep the source inputs with the result so another person can reproduce and verify your work.'],
        ];
    }

    private function faqs(array $article, array $faqs, array $profile): array
    {
        $tools = collect($article['related_tools'])->map(fn (string $slug) => Str::headline($slug))->join(', ', ' and ');
        $extra = [
            ['question' => 'Who should read this '.$article['title'].' guide?', 'answer' => 'It is written for '.$profile['audience'].' who want a practical explanation before applying the topic to a real task.'],
            ['question' => 'How can I verify the result or advice in this guide?', 'answer' => 'Recheck the original inputs, test a simple example and use the official references listed on this page when the decision involves rules, money, compliance or security.'],
            ['question' => 'Which free Toolexa tools are related to this topic?', 'answer' => 'Relevant tools include '.$tools.'. The related-tools section is matched automatically from the article topic.'],
            ['question' => 'When should I review this information again?', 'answer' => 'Review it whenever the source data, rate, rule, format requirement or destination platform changes. The last-updated and content-version details show the freshness of this page.'],
        ];

        return collect(array_merge($faqs, $extra))->unique('question')->take(12)->values()->all();
    }

    private function references(array $article, array $profile): array
    {
        $title = Str::lower($article['title']);
        $references = $profile['references'];
        if (str_contains($title, 'gst')) {
            return array_values(array_filter($references, fn (array $reference) => str_contains(Str::lower($reference['name']), 'gst')));
        }
        if (str_contains($title, 'provident') || str_contains($title, 'epf')) {
            return [['name' => 'Employees’ Provident Fund Organisation', 'url' => 'https://www.epfindia.gov.in/']];
        }

        return $references;
    }

    private function sectionItems(array $sections, array $needles): array
    {
        return collect($sections)
            ->filter(function (array $section) use ($needles) {
                $heading = Str::lower($section['heading']);

                return collect($needles)->contains(fn (string $needle) => str_contains($heading, $needle));
            })
            ->flatMap(fn (array $section) => $section['paragraphs'])
            ->filter()
            ->unique()
            ->values()
            ->all();
    }

    private function readingTime(array $article, array $introduction, array $sections, array $faqs): int
    {
        $content = [$article['title'], $introduction, $sections, $faqs];

        return max(1, (int) ceil(str_word_count(strip_tags(json_encode($content))) / 220));
    }

    private function defaultProfile(): array
    {
        return [
            'audience' => 'students, professionals and everyday users',
            'goal' => 'apply the topic carefully and verify the output in its intended context',
            'warning' => 'Check important outputs against the original source and the requirements of the service where you will use them.',
            'references' => [],
        ];
    }
}

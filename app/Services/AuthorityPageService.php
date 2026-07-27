<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class AuthorityPageService
{
    public function build(string $slug, array $source, array $tools, array $articles, array $comparisons): array
    {
        $override = config('authority_pages.pages.'.$slug, []);
        $profile = $this->profile($slug, $source);
        $fingerprint = substr(sha1(json_encode([$source, $override, array_column($tools, 'slug'), array_column($articles, 'slug'), array_column($comparisons, 'slug')])), 0, 16);

        return Cache::remember('authority-page:v1:'.$slug.':'.$fingerprint, now()->addHours((int) config('authority_pages.cache_hours', 6)), function () use ($slug, $source, $tools, $articles, $comparisons, $override, $profile) {
            $title = $source['title'] ?? $source['label'];
            $overview = $override['overview'] ?? $this->overview($title, $source);
            $faqs = $override['faqs'] ?? $this->faqs($title, $source);
            $authority = [
                'overview' => $overview,
                'use_cases' => $override['use_cases'] ?? $this->useCases($source),
                'best_practices' => $override['best_practices'] ?? ($source['best_practices'] ?? $this->practices($title, $source)),
                'mistakes' => $override['mistakes'] ?? ($source['mistakes'] ?? $this->mistakes($title)),
                'glossary' => $override['glossary'] ?? ($source['glossary'] ?? $profile['glossary']),
                'faqs' => $faqs,
                'references' => $override['references'] ?? $profile['references'],
                'last_updated' => $override['last_updated'] ?? config('authority_pages.last_updated'),
                'content_version' => $override['content_version'] ?? config('authority_pages.content_version', '1.0'),
                'tool_count' => count($tools),
                'article_count' => count($articles),
                'comparison_count' => count($comparisons),
            ];
            $authority['word_count'] = str_word_count(strip_tags(json_encode([$overview, $source['introduction'] ?? $source['guide'] ?? [], $authority['use_cases'], $authority['best_practices'], $authority['mistakes'], $authority['glossary'], $faqs])));
            $authority['reading_time'] = max(1, (int) ceil($authority['word_count'] / 220));

            return $authority;
        });
    }

    private function overview(string $title, array $source): array
    {
        $description = $source['description'];
        $audience = implode(', ', $source['audience'] ?? ['students', 'professionals', 'business owners and everyday users']);
        $benefits = implode(', ', $source['outcomes'] ?? $source['benefits'] ?? ['faster work', 'clearer output', 'fewer manual errors']);
        $uses = implode(', ', $source['workflows'] ?? $source['use_cases'] ?? ['planning', 'validation and content preparation']);
        $examples = implode(', ', $source['examples'] ?? ['calculations, conversions and browser-based preparation']);

        return [
            $description.' This authority page connects focused tools, educational articles and relevant comparisons so visitors can understand the topic before choosing an action. It covers the core terminology, the practical differences between available approaches and the checks that make an output dependable. New catalog items appear automatically when they belong to this topic, while editors can refine every educational section manually.',
            $title.' are useful for '.$audience.'. A beginner can start with the overview and glossary, while an experienced visitor can search or sort the complete directory. The main benefits include '.$benefits.'. Browser access removes installation friction, but responsible use still means reading the individual tool’s assumptions and independently verifying high-stakes financial, security, legal or production decisions.',
            'Real-world applications include '.$uses.'. Practical examples include '.$examples.'. Begin with a representative input whose expected result you can roughly predict, preserve the original source and change one setting at a time. That approach makes it easier to understand cause and effect, compare alternatives and recognize an output that does not fit the destination requirements.',
            'Use the featured cards as starting points rather than universal recommendations. Popularity, recent updates and editorial selection represent different signals. The complete directory, comparisons and guides exist because the right choice depends on context: desired output, accuracy, compatibility, privacy, speed, file size, time horizon or readability. Following those connected resources turns '.$title.' into a learning path rather than a simple list of links. Review the last-updated information whenever standards, rates or destination requirements may have changed. If the result will be shared, keep enough context for another person to reproduce the same workflow and understand its limitations.',
        ];
    }

    private function faqs(string $title, array $source): array
    {
        $base = $source['faqs'] ?? [];
        $extra = [
            ['question' => 'What does this '.$title.' authority page cover?', 'answer' => 'It connects free tools, beginner guidance, practical use cases, comparisons, articles, terminology, best practices and official references for the topic.'],
            ['question' => 'Who reviews this topic page?', 'answer' => 'The Toolexa Editorial Team maintains the page and the Toolexa Review Team reviews educational content for clarity, consistency and accuracy.'],
            ['question' => 'How often is this '.$title.' page updated?', 'answer' => 'Counts and matched content update automatically when catalog items change. Editorial sections can also be revised manually, with the displayed last-updated date showing freshness.'],
            ['question' => 'Can I request another tool or guide for this topic?', 'answer' => 'Yes. Use the Toolexa contact page to suggest a missing tool, article, comparison or improvement.'],
        ];

        return collect(array_merge($base, $extra))->unique('question')->take(15)->values()->all();
    }

    private function useCases(array $source): array
    {
        $audiences = $source['audience'] ?? ['Students', 'Professionals', 'Business owners'];
        $workflows = $source['workflows'] ?? $source['use_cases'] ?? [];
        $examples = $source['examples'] ?? [];

        return collect($audiences)->take(6)->values()->map(fn (string $audience, int $index) => [
            'audience' => Str::headline($audience),
            'scenario' => $workflows[$index % max(count($workflows), 1)] ?? ($examples[$index % max(count($examples), 1)] ?? 'Completing a focused browser-based task'),
        ])->all();
    }

    private function practices(string $title, array $source): array
    {
        return [
            'Define the required result and destination before selecting a '.$title.' page.',
            'Use a representative test input and preserve the original source.',
            'Change one setting at a time when comparing possible outputs.',
            'Read the individual tool’s assumptions, limitations and privacy notes.',
            'Verify important results against an official source or qualified professional.',
            'Document the input, settings and date when the result affects future work.',
        ];
    }

    private function mistakes(string $title): array
    {
        return [
            'Choosing the first '.$title.' result without checking whether its output matches the real task.',
            'Entering the right value with the wrong unit, rate, period, format or mode.',
            'Changing several variables together and being unable to explain the difference.',
            'Deleting source material before reviewing the generated result.',
            'Treating a browser tool as a substitute for official or professional guidance.',
            'Sharing an output without confirming compatibility at its destination.',
        ];
    }

    private function profile(string $slug, array $source): array
    {
        $baseKeys = [$slug, Str::slug($source['category'] ?? ''), Str::slug($source['title'] ?? $source['label'] ?? '')];
        $keys = collect($baseKeys)->flatMap(fn (string $key) => [$key, Str::before($key, '-tools')])->filter()->unique()->all();
        foreach ($keys as $key) {
            $profile = config('authority_pages.profiles.'.$key);
            if ($profile) {
                return $profile;
            }
        }

        $topic = Str::lower($source['title'] ?? $source['label']);
        $terms = collect($source['concepts'] ?? [])->take(6)->mapWithKeys(fn (string $term) => [
            Str::headline($term) => 'An important concept within '.$topic.' that should be interpreted according to the current task and tool guidance.',
        ])->all();
        $glossary = count($terms) >= 6 ? $terms : [
            'Input' => 'The source value, file or text supplied to a '.$topic.' workflow.',
            'Output' => 'The result produced after the selected '.$topic.' operation.',
            'Validation' => 'Checking that information satisfies the expected rules before relying on it.',
            'Conversion' => 'Changing a value or resource from one supported representation to another.',
            'Precision' => 'The level of numerical or descriptive detail retained in a result.',
            'Compatibility' => 'Whether an output works correctly in its intended destination.',
        ];

        return ['glossary' => $glossary, 'references' => []];
    }
}

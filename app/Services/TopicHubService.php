<?php

namespace App\Services;

use App\Http\Controllers\Tools\HomeController;
use App\Support\BlogRepository;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class TopicHubService
{
    public function all(): array
    {
        return collect(config('hubs.topics', []))->map(fn (array $topic, string $slug) => [
            'slug' => $slug,
            'title' => $topic['title'],
            'description' => $topic['description'],
            'tool_count' => collect(HomeController::tools())->where('category', $topic['category'])->count(),
        ])->values()->all();
    }

    public function find(string $slug): ?array
    {
        $profile = config('hubs.topics.'.$slug);
        if (! $profile) {
            return null;
        }

        $tools = collect(HomeController::tools())->where('category', $profile['category'])->values()->all();
        $comparisons = collect(app(ComparisonService::class)->all())->filter(fn (array $comparison) => $comparison['left']['category'] === $profile['category'] || $comparison['right']['category'] === $profile['category'])->values()->all();
        $fingerprint = substr(sha1(json_encode([$profile, array_column($tools, 'slug'), array_column($comparisons, 'slug')])), 0, 12);

        return Cache::remember('topic-hub:v2:'.$slug.':'.$fingerprint, now()->addHours(6), function () use ($slug, $profile, $tools, $comparisons) {
            $search = app(IntelligentSearchService::class)->search($profile['title'], 'articles', 12);
            $articles = collect($search['groups']['articles'])
                ->map(fn (array $article) => BlogRepository::find($article['slug']))
                ->filter()
                ->values()
                ->all();
            $category = collect(HomeController::categories())->firstWhere('name', $profile['category']);
            $featured = $category ? app(CategoryLandingService::class)->landing($category, $tools)['featured'] : [];
            $faqs = $this->faqs($profile);

            return array_merge($profile, [
                'slug' => $slug,
                'tools' => $tools,
                'tool_count' => count($tools),
                'featured_tools' => collect($featured)->pluck('tool')->values()->all(),
                'articles' => $articles,
                'article_count' => $search['total'],
                'comparisons' => $comparisons,
                'comparison_count' => count($comparisons),
                'guide' => $this->guide($profile),
                'faqs' => $faqs,
                'related_hubs' => $this->relatedHubs($slug, $profile),
                'meta_title' => $profile['title'].' Guide: Free Tools, Best Practices & Resources | Toolexa',
                'meta_description' => Str::limit($profile['description'].' Explore free tools, detailed guides, comparisons, best practices, FAQs and essential terminology.', 158, ''),
                'schema' => $this->schema($slug, $profile, $tools, $faqs),
            ]);
        });
    }

    private function guide(array $profile): array
    {
        $audience = implode(', ', $profile['audience']);
        $outcomes = implode(', ', $profile['outcomes']);
        $workflows = implode(', ', $profile['workflows']);
        $concepts = implode(', ', $profile['concepts']);
        $examples = implode(', ', $profile['examples']);
        $title = $profile['title'];

        return [
            ['heading' => 'Understanding '.$title, 'paragraphs' => [
                $profile['description'].' The topic covers a connected set of tasks rather than one isolated action. Understanding the relationship between inputs, processing choices and final output makes it easier to select the right tool and judge whether the result is suitable. Toolexa brings these workflows together so a visitor can learn the underlying ideas, open a focused utility and continue into a related guide or comparison without starting the research again.',
                'A useful starting point is to separate the goal from the method. People often begin with a familiar tool name, even when another workflow is more appropriate. Define what the finished result must accomplish, where it will be used and which constraints matter. Those constraints may include quality, compatibility, speed, privacy, accuracy, size, time horizon or readability. Once the outcome is clear, the tools listed in this hub become much easier to compare.',
            ]],
            ['heading' => 'Who benefits from '.$title.'?', 'paragraphs' => [
                'This hub is designed for '.$audience.'. A first-time user can follow the explanations and examples without specialist knowledge, while an experienced user can move directly to the complete directory. The consistent page structure reduces the learning curve between tools: descriptions identify the task, instructions explain the inputs, and results are presented with supporting context rather than as an unexplained number or file.',
                'Different audiences use the same utilities for different reasons. A student may need to understand a concept and verify a small example. A professional may need repeatable output during a larger workflow. A small team may want to avoid purchasing or installing software for a quick operation. A mobile user may simply need an answer away from a desk. Browser-based access keeps those scenarios in one place while preserving the need to verify high-stakes results.',
            ]],
            ['heading' => 'Core concepts you should understand', 'paragraphs' => [
                'The most important concepts include '.$concepts.'. These ideas affect how a tool interprets input and what its output can reliably represent. Learning the vocabulary first prevents many avoidable mistakes because labels that appear similar can have different technical meanings. The glossary later on this page provides concise definitions, while the related articles offer more depth when one concept needs a complete explanation.',
                'Concepts are most useful when tested with a realistic example. Begin with a small input whose expected outcome you can roughly predict. Run the tool once, review every field and then change only one setting. Comparing the two outputs reveals what that setting controls. This method is more dependable than changing several variables at once, and it creates a repeatable mental model that transfers to other utilities in the same topic.',
            ]],
            ['heading' => 'Common workflows and real examples', 'paragraphs' => [
                'Typical workflows include '.$workflows.'. Although each task has its own details, a dependable process follows the same broad pattern: preserve the source, identify the destination requirements, choose the relevant tool, test a representative input, review the result and only then use it in the final project. This sequence reduces accidental data loss and makes it easier to identify where an unexpected result was introduced.',
                'Practical examples include '.$examples.'. In each case, the best choice depends on context rather than a universal ranking. A fast output may be more valuable for a disposable draft, while accuracy, compatibility or reversibility may matter more for an official deliverable. Comparison pages on this hub highlight those tradeoffs directly, and related articles explain the reasoning behind them.',
            ]],
            ['heading' => 'How to choose the right tool', 'paragraphs' => [
                'Start by reading the short description on each card and matching its promised output to your actual need. Then open the tool page and check supported inputs, limitations and processing notes. Featured tools provide convenient entry points, but popularity is not proof that a tool fits every situation. The complete directory exists so you can compare specialized options instead of forcing an unrelated utility into the workflow.',
                'Consider privacy before entering data. Many browser-compatible tasks can run locally, but the individual page should always be the source of truth about processing behavior. Remove secrets and personal information from test samples whenever possible. For calculations, confirm units, rates and periods. For files or text, preserve an original. For technical output, validate it in the destination environment rather than assuming that a successful transformation guarantees integration compatibility.',
            ]],
            ['heading' => 'Benefits of an organized browser toolkit', 'paragraphs' => [
                'Used carefully, '.$title.' can support '.$outcomes.'. Focused utilities reduce context switching because they expose only the controls needed for a defined task. There is no lengthy setup, and consistent interfaces make related operations easier to discover. Supporting guides also help users understand why an output changes instead of encouraging blind copying.',
                'The connected structure matters for both efficiency and learning. A calculator can lead to a conceptual article, a format tool can lead to a comparison, and a hub can introduce nearby categories. These internal paths prevent individual tools from becoming isolated pages and help visitors continue naturally when one task produces another. As new tools, comparisons and articles are published, their counts and relevant cards update here automatically.',
            ]],
            ['heading' => 'A reliable step-by-step approach', 'paragraphs' => [
                'First, write down the result you need and the destination requirements. Second, prepare a safe copy of the source data or a representative sample. Third, choose the narrowest tool that performs the required action. Fourth, read the labels and defaults before running it. Fifth, inspect the complete output rather than only the most prominent result. Finally, save, copy or download it with a descriptive name and verify it where it will actually be used.',
                'If the result is unexpected, return to the inputs before changing tools. Check units, formats, whitespace, source quality, selected modes and assumptions. Compare against a second example and use the FAQ or related guide to clarify unfamiliar terminology. For important business, financial, security or production decisions, treat browser utilities as useful aids and confirm the final outcome with authoritative documentation or a qualified professional.',
            ]],
            ['heading' => 'Building long-term confidence', 'paragraphs' => [
                'Confidence comes from understanding repeatable principles rather than memorizing one interface. Keep notes about destination requirements, use clear filenames or examples, and revisit comparisons when standards or project needs change. The best practices below summarize habits that remain useful across the topic, while the common-mistakes section identifies shortcuts that frequently cause poor output or incorrect conclusions.',
                'Toolexa is designed as a growing reference point. Bookmark this hub when the topic is part of your regular work, use search when you know the operation you need and browse related hubs when the task crosses into another category. New catalog items appear without requiring the hub layout to be rebuilt, allowing this page to remain a current map of the available tools, learning material and comparisons.',
            ]],
        ];
    }

    private function faqs(array $profile): array
    {
        $title = $profile['title'];

        return [
            ['question' => 'What are '.$title.'?', 'answer' => $profile['description']],
            ['question' => 'Who should use '.$title.'?', 'answer' => 'They are useful for '.implode(', ', $profile['audience']).'.'],
            ['question' => 'Are the tools in this hub free?', 'answer' => 'Yes. Toolexa tools can be opened and used for free without creating an account.'],
            ['question' => 'How do I choose the correct tool?', 'answer' => 'Define the output you need, review the tool descriptions and confirm supported inputs and destination requirements before processing real data.'],
            ['question' => 'Can I use these tools on mobile?', 'answer' => 'Yes. Hub pages and tools use responsive layouts for modern phones, tablets and desktop browsers.'],
            ['question' => 'Is my input uploaded or stored?', 'answer' => 'Many compatible utilities run locally in the browser. Check the individual tool page for processing details and avoid unnecessary sensitive input.'],
            ['question' => 'How accurate are the results?', 'answer' => 'Results depend on the supplied inputs, selected settings and documented processing method. Verify important outputs independently.'],
            ['question' => 'Does this hub update when new content is added?', 'answer' => 'Yes. New tools, matched articles and configured comparisons appear automatically when they belong to this topic.'],
        ];
    }

    private function relatedHubs(string $currentSlug, array $profile): array
    {
        $currentTerms = $this->terms(implode(' ', array_merge($profile['concepts'], $profile['workflows'])));

        return collect(config('hubs.topics', []))->reject(fn (array $hub, string $slug) => $slug === $currentSlug)->map(function (array $hub, string $slug) use ($currentTerms) {
            $hub['slug'] = $slug;
            $hub['_score'] = count(array_intersect($currentTerms, $this->terms(implode(' ', array_merge($hub['concepts'], $hub['workflows'])))));

            return $hub;
        })->sortByDesc('_score')->take(3)->map(function (array $hub) { unset($hub['_score']); return $hub; })->values()->all();
    }

    private function terms(string $text): array
    {
        return collect(preg_split('/[^\pL\pN]+/u', Str::lower($text)) ?: [])->filter(fn (string $term) => mb_strlen($term) > 3)->unique()->all();
    }

    private function schema(string $slug, array $profile, array $tools, array $faqs): array
    {
        $url = route('hub.show', $slug);
        $items = collect($tools)->values()->map(fn (array $tool, int $index) => ['@type' => 'ListItem', 'position' => $index + 1, 'name' => $tool['name'], 'url' => url('tools/'.$tool['slug'])])->all();

        return [
            ['@context' => 'https://schema.org', '@type' => 'CollectionPage', 'name' => $profile['title'], 'description' => $profile['description'], 'url' => $url, 'mainEntity' => ['@type' => 'ItemList', 'numberOfItems' => count($tools), 'itemListElement' => $items]],
            ['@context' => 'https://schema.org', '@type' => 'BreadcrumbList', 'itemListElement' => [
                ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => url('/')],
                ['@type' => 'ListItem', 'position' => 2, 'name' => 'Topic Hubs', 'item' => route('hub.index')],
                ['@type' => 'ListItem', 'position' => 3, 'name' => $profile['title'], 'item' => $url],
            ]],
            ['@context' => 'https://schema.org', '@type' => 'FAQPage', 'mainEntity' => collect($faqs)->map(fn (array $faq) => ['@type' => 'Question', 'name' => $faq['question'], 'acceptedAnswer' => ['@type' => 'Answer', 'text' => $faq['answer']]])->all()],
        ];
    }
}

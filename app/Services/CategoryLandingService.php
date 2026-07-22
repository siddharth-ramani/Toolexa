<?php

namespace App\Services;

use App\Http\Controllers\Tools\HomeController;
use Illuminate\Support\Str;

class CategoryLandingService
{
    public function landing(array $category, array $tools): array
    {
        $label = str_contains($category['name'], 'Tools') ? $category['name'] : $category['name'].' Tools';
        $profile = $this->profile($category['slug'], $label);

        return array_merge($profile, [
            'label' => $label,
            'description' => $profile['description'],
            'introduction' => $this->introduction($label, $profile),
            'faqs' => $this->faqs($label, $profile),
            'featured' => $this->featuredTools($tools),
            'meta_title' => $label.' – Free Online '.Str::before($label, ' Tools').' Tools | Toolexa',
            'meta_description' => Str::limit($profile['description'].' Use '.count($tools).' free, fast and secure Toolexa tools without signup.', 158, ''),
        ]);
    }

    public function relatedCategories(array $current, array $categories, array $allTools, int $limit = 3): array
    {
        $currentTools = collect($allTools)->where('category', $current['name']);
        $terms = $this->terms($current['name'].' '.$currentTools->pluck('keywords')->implode(' '));

        return collect($categories)
            ->reject(fn (array $category) => $category['slug'] === $current['slug'])
            ->map(function (array $category) use ($allTools, $terms) {
                $tools = collect($allTools)->where('category', $category['name']);
                $candidateTerms = $this->terms($category['name'].' '.$tools->pluck('keywords')->implode(' '));
                $category['_score'] = count(array_intersect($terms, $candidateTerms));

                return $category;
            })
            ->sortByDesc('_score')
            ->take($limit)
            ->map(function (array $category) {
                unset($category['_score']);

                return $category;
            })
            ->values()
            ->all();
    }

    private function featuredTools(array $tools): array
    {
        $collection = collect($tools)->unique('slug')->values();
        $popularSlugs = HomeController::popularSlugs();
        $popular = $collection->sortBy(fn (array $tool) => array_search($tool['slug'], $popularSlugs, true) === false ? PHP_INT_MAX : array_search($tool['slug'], $popularSlugs, true))->first();
        $editor = $collection->reject(fn (array $tool) => $tool['slug'] === ($popular['slug'] ?? null))
            ->sortByDesc(fn (array $tool) => count($tool['features'] ?? []) + count($tool['faq'] ?? []) + mb_strlen($tool['desc'] ?? '') / 100)
            ->first();
        $recent = $collection->reverse()->first(fn (array $tool) => ! in_array($tool['slug'], array_filter([$popular['slug'] ?? null, $editor['slug'] ?? null]), true));

        return collect([
            $popular ? ['label' => 'Most Popular', 'tool' => $popular] : null,
            $editor ? ['label' => "Editor's Choice", 'tool' => $editor] : null,
            $recent ? ['label' => 'Recently Updated', 'tool' => $recent] : null,
        ])->filter()->values()->all();
    }

    private function introduction(string $label, array $profile): array
    {
        $audience = implode(', ', $profile['audience']);
        $benefits = implode(', ', $profile['benefits']);
        $uses = implode(', ', $profile['use_cases']);
        $examples = implode(', ', $profile['examples']);

        return [
            ['heading' => 'What are '.$label.'?', 'paragraphs' => [
                $profile['overview'].' This Toolexa collection brings those tasks into one clear browser-based destination, so you can move from a question to a useful result without installing specialist software. Each page focuses on a defined job, explains the important inputs and presents output in a format that is easy to review. The collection grows automatically as relevant tools are published, which keeps this landing page useful for both first-time visitors and returning users.',
                'The category includes practical options for '.$examples.'. Some tools calculate or convert values, while others validate, generate, inspect or reorganize information. That range matters because real work rarely follows one fixed pattern. You may need a quick answer on a phone, a repeatable check during a project, or a clean output to copy into another application. The complete tool directory below makes those workflows easy to discover from a single page.',
            ]],
            ['heading' => 'Who should use these tools?', 'paragraphs' => [
                $label.' are useful for '.$audience.'. They are designed to be approachable for someone completing an occasional task while still being efficient enough for regular professional use. Clear labels and short descriptions help you choose the right tool before opening it, and supporting instructions explain unfamiliar concepts without forcing experienced users through a lengthy setup.',
                'Students and independent learners can use the collection to check examples and understand how an output changes when an input changes. Professionals can use it for quick preparation, validation and routine calculations. Small teams and business owners can reduce repetitive manual work without purchasing software for a single operation. Because the interfaces are responsive, the same workflow remains available at a desk, during a meeting or while working from a mobile device.',
            ]],
            ['heading' => 'Benefits of using '.$label, 'paragraphs' => [
                'The main benefits are '.$benefits.'. Toolexa tools open directly in the browser and do not require an account, which removes friction when a task needs attention immediately. Consistent layouts also make it easier to move between related tools: inputs, actions, results and explanatory sections appear in familiar places, reducing the time needed to learn each page.',
                'Speed does not have to mean guesswork. Tool pages include descriptions, usage steps, examples, formulas where relevant and answers to common questions. That context helps you understand what a result represents and when it should be independently verified. Many utilities process information locally in the browser when their function permits it, supporting a more private workflow for everyday data. Important professional decisions should still be checked against authoritative sources.',
            ]],
            ['heading' => 'Real-life uses and examples', 'paragraphs' => [
                'Common real-life uses include '.$uses.'. A typical workflow begins by identifying the output you need, opening the most closely matched tool, entering a small realistic example and checking whether the result has the expected format. Once the setup is clear, replace the example with your actual values or content. Changing one input at a time is a useful way to compare scenarios and spot mistakes.',
                'For example, you might use '.$examples.' during planning, study, content production, development or day-to-day administration. Related article links provide deeper explanations when the task involves a concept you want to understand, while related category links help you continue into a nearby workflow. This structure turns the category page into more than a directory: it is a practical starting point that connects discovery, learning and action.',
            ]],
            ['heading' => 'How to choose the right tool', 'paragraphs' => [
                'Start with the exact job rather than the broad topic. Read each card description and look for the input and output that match your situation. The featured section highlights a popular option, an editor-selected option and a recently added or updated option, but the complete grid remains the best place to compare everything available. Use the category search when the collection is large or when you already know part of a tool name or function.',
                'Before relying on an output, confirm units, formats, rates, date conventions and any assumptions shown on the tool page. Save or copy results only after reviewing them. If one task naturally leads to another, follow the contextual links to a related tool or guide instead of repeating work manually. This approach is faster, creates fewer input errors and makes the Toolexa collection useful as a connected toolkit rather than a set of isolated pages.',
            ]],
        ];
    }

    private function faqs(string $label, array $profile): array
    {
        $singular = Str::lower($label);

        return [
            ['question' => 'What are '.$label.' used for?', 'answer' => $profile['overview'].' Common examples include '.implode(', ', $profile['examples']).'.'],
            ['question' => 'Are Toolexa '.$label.' free?', 'answer' => 'Yes. The tools in this category are free to open and use without a paid subscription.'],
            ['question' => 'Do I need to create an account?', 'answer' => 'No. You can use '.$singular.' without registering or signing in.'],
            ['question' => 'Can I use these tools on a mobile phone?', 'answer' => 'Yes. Category pages and tool interfaces are responsive and work across modern phones, tablets and desktop browsers.'],
            ['question' => 'How do I choose the correct tool?', 'answer' => 'Search within the category or compare the name and description on each card. Open the tool whose inputs and output match your task.'],
            ['question' => 'Are the results accurate?', 'answer' => 'Tools use the formulas or browser operations explained on their pages. Check your inputs and verify results independently when making important financial, legal or professional decisions.'],
            ['question' => 'Is my information secure?', 'answer' => 'Many compatible utilities process data directly in your browser. Avoid entering sensitive information unless the individual tool page clearly explains how it is handled.'],
            ['question' => 'Will new '.$singular.' appear here automatically?', 'answer' => 'Yes. When a new tool is assigned to this category, its card, count and internal links appear on this page automatically.'],
        ];
    }

    private function profile(string $slug, string $label): array
    {
        $profiles = [
            'finance' => ['description' => 'Plan loans, taxes, savings, investments and everyday money decisions with clear financial calculators.', 'overview' => 'Finance tools help turn rates, amounts and time periods into estimates that are easier to compare and understand.', 'audience' => ['households', 'students', 'borrowers', 'investors', 'accountants', 'small-business owners'], 'benefits' => ['faster calculations', 'clear scenario comparisons', 'fewer manual formula errors', 'better planning'], 'use_cases' => ['checking loan affordability', 'estimating tax', 'comparing savings growth', 'planning recurring investments'], 'examples' => ['EMI, GST, interest, SIP and deposit calculations']],
            'pdf-tools' => ['description' => 'Create, combine, split, compress and organize PDF documents directly from your browser.', 'overview' => 'PDF tools simplify common document operations without requiring a full desktop editing suite.', 'audience' => ['students', 'office teams', 'freelancers', 'teachers', 'administrators', 'small businesses'], 'benefits' => ['smaller files', 'organized documents', 'quick format changes', 'portable workflows'], 'use_cases' => ['combining reports', 'extracting pages', 'preparing uploads', 'turning images into documents'], 'examples' => ['PDF merging, splitting, compression and conversion']],
            'image-tools' => ['description' => 'Resize, compress, convert and prepare images for websites, documents and social sharing.', 'overview' => 'Image tools make everyday visual file preparation faster by handling size, format and layout tasks in the browser.', 'audience' => ['designers', 'bloggers', 'students', 'online sellers', 'developers', 'social media teams'], 'benefits' => ['faster uploads', 'consistent dimensions', 'smaller files', 'easy format conversion'], 'use_cases' => ['optimizing website images', 'preparing product photos', 'resizing profile pictures', 'creating document assets'], 'examples' => ['image compression, resizing, cropping and format conversion']],
            'text-tools' => ['description' => 'Write, clean, count, transform and organize text with quick browser-based utilities.', 'overview' => 'Text tools automate repetitive editing and cleanup operations for words, lines and structured snippets.', 'audience' => ['writers', 'editors', 'students', 'marketers', 'researchers', 'developers'], 'benefits' => ['consistent formatting', 'faster cleanup', 'instant counts', 'copy-ready output'], 'use_cases' => ['cleaning pasted lists', 'changing letter case', 'counting words', 'removing duplicate lines'], 'examples' => ['case conversion, word counting, line cleanup and text generation']],
            'developer-tools' => ['description' => 'Format, validate, encode, decode and inspect development data without leaving the browser.', 'overview' => 'Developer tools support everyday coding, debugging and data transformation tasks with focused interfaces.', 'audience' => ['software developers', 'QA engineers', 'technical writers', 'students', 'API teams', 'support engineers'], 'benefits' => ['quick validation', 'readable data', 'repeatable transformations', 'less context switching'], 'use_cases' => ['debugging API payloads', 'formatting code', 'encoding values', 'testing identifiers'], 'examples' => ['JSON formatting, Base64 conversion, URL parsing and hash generation']],
            'seo-tools' => ['description' => 'Research, validate and generate essential on-page SEO elements for discoverable websites.', 'overview' => 'SEO tools help site owners inspect and prepare the technical and content signals used by search engines and sharing platforms.', 'audience' => ['SEO specialists', 'content writers', 'developers', 'bloggers', 'agencies', 'site owners'], 'benefits' => ['cleaner metadata', 'better content checks', 'consistent URLs', 'faster technical reviews'], 'use_cases' => ['building meta tags', 'checking keyword usage', 'creating sitemaps', 'preparing social previews'], 'examples' => ['keyword analysis, slug generation, robots rules and sitemap creation']],
            'security-tools' => ['description' => 'Generate, inspect and strengthen everyday security data with privacy-conscious browser tools.', 'overview' => 'Security tools support safer account and development workflows through password, hash and verification utilities.', 'audience' => ['internet users', 'developers', 'IT teams', 'students', 'administrators', 'security learners'], 'benefits' => ['stronger credentials', 'quick integrity checks', 'local processing', 'clear security guidance'], 'use_cases' => ['creating strong passwords', 'checking password strength', 'generating hashes', 'reviewing encoded data'], 'examples' => ['password generation, strength checks and cryptographic hashes']],
            'color-tools' => ['description' => 'Convert, inspect and create color values for design, development and brand workflows.', 'overview' => 'Color tools make it easier to move between formats and build consistent palettes for digital projects.', 'audience' => ['designers', 'front-end developers', 'artists', 'marketers', 'students', 'brand teams'], 'benefits' => ['consistent color values', 'faster conversions', 'accessible comparisons', 'copy-ready codes'], 'use_cases' => ['matching brand colors', 'converting HEX and RGB', 'building palettes', 'checking contrast'], 'examples' => ['color conversion, palette generation and contrast checking']],
            'utility' => ['description' => 'Complete common calculations, conversions and everyday browser tasks quickly in one place.', 'overview' => 'Utility tools cover practical tasks that do not require specialist software but benefit from a fast, dependable interface.', 'audience' => ['students', 'families', 'professionals', 'teachers', 'travelers', 'general internet users'], 'benefits' => ['quick answers', 'simple interfaces', 'broad everyday coverage', 'mobile access'], 'use_cases' => ['converting units', 'calculating age', 'generating QR codes', 'making quick selections'], 'examples' => ['unit conversion, age calculation, random generation and QR creation']],
            'utility-tools' => ['description' => 'Complete common calculations, conversions and everyday browser tasks quickly in one place.', 'overview' => 'Utility tools cover practical tasks that do not require specialist software but benefit from a fast, dependable interface.', 'audience' => ['students', 'families', 'professionals', 'teachers', 'travelers', 'general internet users'], 'benefits' => ['quick answers', 'simple interfaces', 'broad everyday coverage', 'mobile access'], 'use_cases' => ['converting units', 'calculating age', 'generating QR codes', 'making quick selections'], 'examples' => ['unit conversion, age calculation, random generation and QR creation']],
            'business-tools' => ['description' => 'Handle practical pricing, planning, operations and administrative calculations for everyday business work.', 'overview' => 'Business tools reduce repetitive operational work and make routine figures easier to prepare and review.', 'audience' => ['entrepreneurs', 'online sellers', 'freelancers', 'managers', 'operations teams', 'accountants'], 'benefits' => ['faster decisions', 'consistent calculations', 'simpler administration', 'clear output'], 'use_cases' => ['estimating margins', 'preparing invoices', 'planning pricing', 'organizing operational data'], 'examples' => ['margin, markup, tax, pricing and document calculations']],
            'date-time-tools' => ['description' => 'Calculate, compare, convert and format dates and times for schedules and technical workflows.', 'overview' => 'Date and time tools remove the difficulty from calendar arithmetic, durations, timestamps and timezone-related tasks.', 'audience' => ['project teams', 'developers', 'students', 'travelers', 'event planners', 'administrators'], 'benefits' => ['accurate durations', 'clear date comparisons', 'consistent timestamps', 'easier scheduling'], 'use_cases' => ['counting days', 'converting timestamps', 'planning deadlines', 'comparing time values'], 'examples' => ['date differences, Unix timestamps, working days and time conversion']],
            'web-tools' => ['description' => 'Inspect, generate and troubleshoot common web data, URLs and browser-facing resources.', 'overview' => 'Web tools provide focused utilities for understanding and preparing the building blocks used across websites.', 'audience' => ['developers', 'site owners', 'SEO teams', 'technical support', 'students', 'digital marketers'], 'benefits' => ['faster troubleshooting', 'cleaner web data', 'portable browser access', 'copy-ready results'], 'use_cases' => ['inspecting URLs', 'testing page metadata', 'generating web resources', 'checking browser values'], 'examples' => ['URL inspection, metadata generation, user-agent checks and web encoding']],
        ];

        return $profiles[$slug] ?? ['description' => 'Explore free online '.$label.' for quick, clear and dependable everyday results.', 'overview' => $label.' bring related browser-based tasks together in one convenient collection.', 'audience' => ['students', 'professionals', 'small teams', 'independent users'], 'benefits' => ['faster workflows', 'clear results', 'simple access', 'mobile-friendly use'], 'use_cases' => ['planning work', 'checking information', 'creating reusable output', 'reducing manual steps'], 'examples' => ['calculations, conversions, validation and content preparation']];
    }

    private function terms(string $value): array
    {
        return collect(preg_split('/[^\pL\pN]+/u', Str::lower($value)) ?: [])->filter(fn ($term) => mb_strlen($term) > 3)->unique()->all();
    }
}

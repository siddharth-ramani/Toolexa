<?php

return [
    'default_author' => 'toolexa-editorial-team',
    'default_reviewer' => 'toolexa-review-team',
    'default_updated_at' => '2026-07-27',

    'authors' => [
        'toolexa-editorial-team' => [
            'slug' => 'toolexa-editorial-team',
            'name' => 'Toolexa Editorial Team',
            'role' => 'Editorial Team',
            'photo' => 'assets/images/favicon.png',
            'bio' => 'A team focused on creating and maintaining accurate, easy-to-understand online tools, calculators and educational resources.',
            'biography' => 'The Toolexa Editorial Team researches practical online tasks, develops supporting explanations and maintains the educational content connected to Toolexa tools. The team focuses on turning formulas, technical workflows and everyday problems into clear browser-based experiences that users can understand and verify.',
            'mission' => 'Our mission is to make useful digital tools and educational resources freely accessible while explaining assumptions, limitations and safe next steps clearly.',
            'expertise' => [
                'Online calculators and formula explanations',
                'Developer, text and browser utilities',
                'Image and PDF workflows',
                'SEO and website productivity tools',
                'Educational content and user-focused documentation',
                'Content quality, accessibility and performance',
            ],
            'years_experience' => 5,
            'social_links' => [],
            'email' => null,
            'website' => null,
        ],
    ],

    'reviewers' => [
        'toolexa-review-team' => [
            'slug' => 'toolexa-review-team',
            'name' => 'Toolexa Review Team',
            'role' => 'Content Review',
            'bio' => 'Reviews tool explanations, examples and educational content for clarity, consistency and accuracy before and after publication.',
            'expertise' => ['Content accuracy', 'Calculation review', 'Readability', 'User experience'],
        ],
    ],

    'article_assignments' => [
        'how-to-calculate-marketplace-profit-per-order' => ['updated_at' => '2026-10-30'],
        'cod-charges-seller-payout-explained' => ['updated_at' => '2026-10-30'],
        'gst-inclusive-vs-gst-exclusive-explained' => ['updated_at' => '2026-10-30'],
        'uuid-explained-what-it-is-and-why-developers-use-it' => ['updated_at' => '2026-10-30'],
    ],
    'tool_assignments' => [
        'image-compressor' => ['updated_at' => '2026-10-30'],
        'meesho-label-cropper' => ['updated_at' => '2026-10-30'],
        'percentage-calculator' => ['updated_at' => '2026-10-30'],
        'gst-calculator' => ['updated_at' => '2026-10-30'],
        'myntra-label-cropper' => ['updated_at' => '2026-10-30'],
        'unit-converter' => ['updated_at' => '2026-10-30'],
        'barcode-generator' => ['updated_at' => '2026-10-30'],
    ],
    'comparison_assignments' => [],

    'history' => [
        'articles' => [
            'how-to-calculate-gst-in-india' => [
                ['date' => '2026-07-27', 'note' => 'Reviewed calculation guidance and added clearer GST examples.'],
            ],
            'how-to-calculate-marketplace-profit-per-order' => [
                ['date' => '2026-10-30', 'note' => 'Reconciled the worked example with the calculator and documented its fee-tax assumption.'],
            ],
            'cod-charges-seller-payout-explained' => [
                ['date' => '2026-10-30', 'note' => 'Replaced generic guidance with a verified COD fee, tax and payout example.'],
            ],
            'gst-inclusive-vs-gst-exclusive-explained' => [
                ['date' => '2026-10-30', 'note' => 'Added reconciled inclusive and exclusive GST examples and clarified the taxable-value formulas.'],
            ],
            'uuid-explained-what-it-is-and-why-developers-use-it' => [
                ['date' => '2026-10-30', 'note' => 'Updated the guide to RFC 9562 with exact UUIDv4 structure, version guidance and security limitations.'],
            ],
        ],
        'tools' => [
            'image-compressor' => [
                ['date' => '2026-10-30', 'note' => 'Replaced generic guidance with browser-encoder behavior, format-specific limitations and verified workflows.'],
            ],
            'meesho-label-cropper' => [
                ['date' => '2026-10-30', 'note' => 'Replaced generic guidance with source-layout, export-layout and batch-verification instructions.'],
            ],
            'percentage-calculator' => [
                ['date' => '2026-10-30', 'note' => 'Added percentage-of, percentage-share and percentage-change modes with clearer formulas and verified examples.'],
            ],
            'gst-calculator' => [
                ['date' => '2026-10-30', 'note' => 'Added inclusive GST extraction while preserving the existing exclusive GST calculation.'],
            ],
            'myntra-label-cropper' => [
                ['date' => '2026-10-30', 'note' => 'Added Myntra-specific multi-label, printer-layout and barcode-verification guidance.'],
            ],
            'unit-converter' => [
                ['date' => '2026-10-30', 'note' => 'Expanded the converter to verified length, weight, temperature and volume conversions with labelled output.'],
            ],
            'barcode-generator' => [
                ['date' => '2026-10-30', 'note' => 'Corrected Code 128 encoding and added explicit EAN-13 and UPC-A length and check-digit validation.'],
            ],
        ],
        'comparisons' => [],
    ],
];

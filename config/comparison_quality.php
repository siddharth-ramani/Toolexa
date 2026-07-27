<?php

return [
    'cache_hours' => 6,
    'default_published_at' => '2026-07-20',
    'content_version' => '1.0',

    'categories' => [
        'Image Tools' => [
            'context' => 'image quality, file size, transparency, software compatibility and the final publishing destination',
            'references' => [
                ['name' => 'MDN Image file type guide', 'url' => 'https://developer.mozilla.org/en-US/docs/Web/Media/Guides/Formats/Image_types'],
                ['name' => 'W3C Images Tutorial', 'url' => 'https://www.w3.org/WAI/tutorials/images/'],
            ],
        ],
        'Finance' => [
            'context' => 'risk tolerance, time horizon, liquidity, tax treatment and whether returns are guaranteed or market-linked',
            'references' => [
                ['name' => 'Reserve Bank of India', 'url' => 'https://www.rbi.org.in/'],
                ['name' => 'SEBI Investor', 'url' => 'https://investor.sebi.gov.in/'],
                ['name' => 'Income Tax Department', 'url' => 'https://www.incometax.gov.in/'],
            ],
        ],
        'Developer Tools' => [
            'context' => 'data shape, ecosystem support, validation requirements, performance and the systems that must consume the output',
            'references' => [
                ['name' => 'JSON specification', 'url' => 'https://www.rfc-editor.org/rfc/rfc8259'],
                ['name' => 'W3C Extensible Markup Language', 'url' => 'https://www.w3.org/XML/'],
                ['name' => 'MDN Web Docs', 'url' => 'https://developer.mozilla.org/'],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Manual comparison overrides
    |--------------------------------------------------------------------------
    | Every generated field can be replaced per slug: recommendations,
    | summaries, explanations, examples, decisions, mistakes, faqs,
    | references, content_version, published_at and version_history.
    */
    'comparisons' => [
        'jpg-vs-png' => [
            'content_version' => '1.1',
            'examples' => [
                ['option' => 'JPG', 'scenario' => 'Product photography', 'explanation' => 'A store can export photographic product images as high-quality JPG files to keep pages lighter while retaining natural color detail.'],
                ['option' => 'PNG', 'scenario' => 'Transparent brand logo', 'explanation' => 'A logo placed over several background colors needs PNG transparency and lossless edges to remain clean.'],
                ['option' => 'PNG', 'scenario' => 'Software screenshot', 'explanation' => 'Interface text and sharp lines usually remain clearer in PNG than in a heavily compressed JPG.'],
            ],
        ],
    ],
];

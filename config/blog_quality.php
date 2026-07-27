<?php

return [
    'cache_hours' => 6,
    'content_version' => '1.0',

    /*
    |--------------------------------------------------------------------------
    | Category guidance
    |--------------------------------------------------------------------------
    | These profiles provide safe editorial context. Any article can replace
    | every generated field in the "articles" map below.
    */
    'categories' => [
        'Finance' => [
            'audience' => 'households, students, borrowers, investors and business owners',
            'goal' => 'understand the assumptions behind a financial result before using it for planning',
            'warning' => 'Rates, taxes, fees and eligibility rules can change. Treat calculations as estimates and verify important decisions with an official source or qualified adviser.',
            'references' => [
                ['name' => 'Reserve Bank of India', 'url' => 'https://www.rbi.org.in/'],
                ['name' => 'Income Tax Department', 'url' => 'https://www.incometax.gov.in/'],
                ['name' => 'GST Portal', 'url' => 'https://www.gst.gov.in/'],
            ],
        ],
        'Developer' => [
            'audience' => 'developers, testers, students and technical teams',
            'goal' => 'understand the data format or browser behavior before using an output in production',
            'warning' => 'Validate generated or transformed output in the destination environment before deploying it.',
            'references' => [
                ['name' => 'MDN Web Docs', 'url' => 'https://developer.mozilla.org/'],
                ['name' => 'World Wide Web Consortium', 'url' => 'https://www.w3.org/'],
                ['name' => 'PHP Documentation', 'url' => 'https://www.php.net/docs.php'],
            ],
        ],
        'Image' => [
            'audience' => 'designers, publishers, sellers, students and website owners',
            'goal' => 'choose settings that balance visual quality, compatibility and file size',
            'warning' => 'Keep the original image. Repeated lossy conversion or aggressive compression can permanently remove detail.',
            'references' => [
                ['name' => 'MDN Image file type guide', 'url' => 'https://developer.mozilla.org/en-US/docs/Web/Media/Guides/Formats/Image_types'],
                ['name' => 'W3C Images Tutorial', 'url' => 'https://www.w3.org/WAI/tutorials/images/'],
            ],
        ],
        'SEO' => [
            'audience' => 'website owners, writers, marketers and developers',
            'goal' => 'make pages understandable to people and search engines without manipulative shortcuts',
            'warning' => 'SEO recommendations are not ranking guarantees. Prioritize helpful content and verify implementation in the published page.',
            'references' => [
                ['name' => 'Google Search Central', 'url' => 'https://developers.google.com/search/docs'],
                ['name' => 'Schema.org', 'url' => 'https://schema.org/'],
            ],
        ],
        'Security' => [
            'audience' => 'individuals, administrators, developers and security-conscious teams',
            'goal' => 'use secure defaults while understanding that a utility is only one part of a broader security process',
            'warning' => 'Never paste production secrets, recovery codes or confidential credentials into a tool unless you have verified how the data is processed.',
            'references' => [
                ['name' => 'OWASP Foundation', 'url' => 'https://owasp.org/'],
                ['name' => 'NIST Cybersecurity', 'url' => 'https://www.nist.gov/cybersecurity'],
            ],
        ],
        'Text' => [
            'audience' => 'writers, editors, students, marketers and office teams',
            'goal' => 'produce clear, accurate text while preserving meaning and required formatting',
            'warning' => 'Review transformed text before publishing; automated cleanup can change intentional spacing, capitalization or punctuation.',
            'references' => [],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Manual article overrides
    |--------------------------------------------------------------------------
    | Editors can set summary, introduction, sections, examples, steps,
    | mistakes, blocks, faqs, references, version and version_history here.
    | Missing fields are safely derived from that article's own definition.
    */
    'articles' => [
        'how-to-calculate-gst-in-india' => [
            'content_version' => '1.1',
            'references' => [
                ['name' => 'GST Portal', 'url' => 'https://www.gst.gov.in/'],
                ['name' => 'Central Board of Indirect Taxes and Customs', 'url' => 'https://cbic-gst.gov.in/'],
            ],
        ],
    ],
];

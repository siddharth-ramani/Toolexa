<?php

$csv = fn (string $key, string $default) => array_values(array_filter(array_map('trim', explode(',', env($key, $default)))));

return [
    'trending_tools' => $csv('HOMEPAGE_TRENDING_TOOLS', 'gst-calculator,emi-calculator,sip-calculator,fd-calculator,ppf-calculator,age-calculator,qr-generator,password-generator'),
    'editor_picks' => $csv('HOMEPAGE_EDITOR_PICKS', 'percentage-calculator,image-compressor,pdf-merger,json-formatter,word-counter,password-strength-checker'),
    'featured_categories' => [
        'finance' => ['icon' => 'FIN', 'description' => 'Plan loans, taxes, savings and investments.'],
        'image-tools' => ['icon' => 'IMG', 'description' => 'Resize, compress and prepare visual files.'],
        'pdf-tools' => ['icon' => 'PDF', 'description' => 'Combine, split and organize PDF documents.'],
        'developer-tools' => ['icon' => 'DEV', 'description' => 'Format, validate and transform development data.'],
        'seo-tools' => ['icon' => 'SEO', 'description' => 'Prepare and inspect essential search signals.'],
        'text-tools' => ['icon' => 'TXT', 'description' => 'Write, count, clean and transform text.'],
        'security-tools' => ['icon' => 'SEC', 'description' => 'Create and inspect security-focused data.'],
        'business-tools' => ['icon' => 'BUS', 'description' => 'Simplify pricing, planning and operations.'],
        'utility' => ['icon' => 'UTL', 'description' => 'Complete practical everyday browser tasks.'],
        'color-tools' => ['icon' => 'CLR', 'description' => 'Convert, inspect and build digital colors.'],
    ],
    'collections' => [
        ['title' => 'Financial Planning', 'category' => 'finance', 'icon' => 'FIN', 'description' => 'Calculators for loans, savings, tax and long-term goals.'],
        ['title' => 'PDF Essentials', 'category' => 'pdf-tools', 'icon' => 'PDF', 'description' => 'Everyday tools for creating and organizing PDF files.'],
        ['title' => 'Image Editing', 'category' => 'image-tools', 'icon' => 'IMG', 'description' => 'Quick image preparation for web, work and social sharing.'],
        ['title' => 'Developer Toolkit', 'category' => 'developer-tools', 'icon' => 'DEV', 'description' => 'Focused utilities for code, APIs and structured data.'],
        ['title' => 'SEO Toolkit', 'category' => 'seo-tools', 'icon' => 'SEO', 'description' => 'Technical and content helpers for discoverable websites.'],
        ['title' => 'Student Essentials', 'category' => 'utility', 'icon' => 'EDU', 'description' => 'Fast calculators and utilities for study and daily work.'],
    ],
];

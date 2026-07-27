<?php

return [
    'admin' => [
        'username' => env('SITE_AUDIT_USERNAME'),
        'password' => env('SITE_AUDIT_PASSWORD'),
    ],
    'cache_hours' => (int) env('SITE_AUDIT_CACHE_HOURS', 6),
    'external_link_checks' => (bool) env('SITE_AUDIT_EXTERNAL_LINK_CHECKS', true),
    'external_link_timeout' => (int) env('SITE_AUDIT_EXTERNAL_LINK_TIMEOUT', 4),
    'max_external_links' => (int) env('SITE_AUDIT_MAX_EXTERNAL_LINKS', 100),
    'large_image_bytes' => (int) env('SITE_AUDIT_LARGE_IMAGE_BYTES', 350000),
    'official_hosts' => [
        'rbi.org.in', 'incometax.gov.in', 'gst.gov.in', 'epfindia.gov.in',
        'uidai.gov.in', 'w3.org', 'developer.mozilla.org', 'php.net',
        'developers.google.com', 'schema.org', 'owasp.org', 'nist.gov',
        'unicode.org', 'iso.org', 'iana.org', 'sebi.gov.in', 'mca.gov.in',
        'rfc-editor.org', 'whatwg.org', 'pdfa.org',
    ],
    'page_types' => [
        'homepage' => ['schemas' => ['Organization', 'WebSite'], 'eeat' => []],
        'tool' => ['schemas' => ['Organization', 'WebSite', 'BreadcrumbList', 'SoftwareApplication', 'Person'], 'eeat' => ['verified', 'updated', 'reading_time', 'author_profile', 'feedback'], 'links' => ['tools', 'articles']],
        'blog' => ['schemas' => ['Organization', 'WebSite', 'BreadcrumbList', 'FAQPage', 'Article', 'Person'], 'eeat' => ['written', 'reviewed', 'updated', 'reading_time', 'author_profile', 'feedback'], 'links' => ['tools', 'articles']],
        'comparison' => ['schemas' => ['Organization', 'WebSite', 'BreadcrumbList', 'FAQPage', 'Article', 'Person'], 'eeat' => ['written', 'reviewed', 'updated', 'reading_time', 'author_profile', 'feedback'], 'links' => ['tools', 'articles', 'comparisons']],
        'topic' => ['schemas' => ['Organization', 'WebSite', 'BreadcrumbList', 'FAQPage', 'CollectionPage', 'ItemList'], 'eeat' => ['written', 'reviewed', 'updated', 'reading_time', 'author_profile', 'feedback'], 'links' => ['tools', 'articles', 'comparisons', 'topics']],
        'category' => ['schemas' => ['Organization', 'WebSite', 'BreadcrumbList', 'FAQPage', 'CollectionPage', 'ItemList'], 'eeat' => ['written', 'reviewed', 'updated', 'reading_time', 'author_profile', 'feedback'], 'links' => ['tools', 'articles', 'comparisons', 'topics']],
        'trust' => ['schemas' => ['Organization', 'WebSite', 'BreadcrumbList'], 'eeat' => ['updated']],
        'legal' => ['schemas' => ['Organization', 'WebSite', 'BreadcrumbList'], 'eeat' => []],
        'author' => ['schemas' => ['Organization', 'WebSite', 'BreadcrumbList', 'Person'], 'eeat' => []],
        'directory' => ['schemas' => ['Organization', 'WebSite', 'CollectionPage', 'ItemList'], 'eeat' => []],
        'utility_page' => ['schemas' => ['Organization', 'WebSite'], 'eeat' => []],
    ],
];

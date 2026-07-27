<?php

return [
    'cache_hours' => 6,
    'last_updated' => '2026-07-27',
    'content_version' => '1.0',

    'profiles' => [
        'finance' => [
            'glossary' => ['Principal' => 'The original amount borrowed, invested or deposited.', 'Interest' => 'The cost of borrowing or return on eligible savings.', 'EMI' => 'A regular instalment used to repay a loan.', 'APR' => 'An annualized representation of borrowing cost where applicable.', 'Compounding' => 'Applying returns to principal and accumulated returns.', 'Liquidity' => 'How readily an asset can be converted into spendable money.'],
            'references' => [['name' => 'Reserve Bank of India', 'url' => 'https://www.rbi.org.in/'], ['name' => 'Income Tax Department', 'url' => 'https://www.incometax.gov.in/'], ['name' => 'GST Portal', 'url' => 'https://www.gst.gov.in/'], ['name' => 'SEBI Investor', 'url' => 'https://investor.sebi.gov.in/']],
        ],
        'image-tools' => [
            'glossary' => ['JPEG' => 'A widely supported lossy format commonly used for photographs.', 'PNG' => 'A lossless raster format supporting alpha transparency.', 'WebP' => 'A modern image format with efficient lossy and lossless modes.', 'SVG' => 'A scalable vector format described with XML markup.', 'DPI' => 'Dots per inch, commonly used for print-density discussions.', 'Compression' => 'Reducing file size with or without discarding image information.'],
            'references' => [['name' => 'MDN Image file type guide', 'url' => 'https://developer.mozilla.org/en-US/docs/Web/Media/Guides/Formats/Image_types'], ['name' => 'W3C Images Tutorial', 'url' => 'https://www.w3.org/WAI/tutorials/images/']],
        ],
        'pdf-tools' => [
            'glossary' => ['PDF' => 'Portable Document Format, designed to preserve document layout.', 'OCR' => 'Recognition that converts scanned text into searchable characters.', 'Merge' => 'Combining multiple documents into one PDF.', 'Split' => 'Separating selected pages into smaller files.', 'Metadata' => 'Information such as document title, author and creation software.', 'Encryption' => 'Protection that restricts document access without appropriate credentials.'],
            'references' => [['name' => 'PDF Association', 'url' => 'https://pdfa.org/resource/iso-standards/'], ['name' => 'W3C PDF Techniques', 'url' => 'https://www.w3.org/WAI/WCAG21/Techniques/pdf/']],
        ],
        'developer-tools' => [
            'glossary' => ['JSON' => 'A lightweight structured-data format using objects and arrays.', 'XML' => 'An extensible markup language for structured data and documents.', 'API' => 'An interface through which software exchanges requests and responses.', 'UUID' => 'A standardized identifier designed to be broadly unique.', 'Base64' => 'A binary-to-text encoding; it is not encryption.', 'Hash' => 'A fixed-length digest derived from input data.'],
            'references' => [['name' => 'MDN Web Docs', 'url' => 'https://developer.mozilla.org/'], ['name' => 'PHP Documentation', 'url' => 'https://www.php.net/docs.php'], ['name' => 'W3C Standards', 'url' => 'https://www.w3.org/standards/']],
        ],
        'seo-tools' => [
            'glossary' => ['Crawl' => 'Discovering and requesting pages and resources.', 'Index' => 'A search engine collection of understood pages.', 'Canonical URL' => 'The preferred representative URL among equivalent pages.', 'Meta description' => 'A concise page summary that may appear in search results.', 'Sitemap' => 'A machine-readable list of important site URLs.', 'Search intent' => 'The purpose behind a user’s search query.'],
            'references' => [['name' => 'Google Search Central', 'url' => 'https://developers.google.com/search/docs'], ['name' => 'Schema.org', 'url' => 'https://schema.org/'], ['name' => 'W3C Web Standards', 'url' => 'https://www.w3.org/standards/']],
        ],
        'text-tools' => [
            'glossary' => ['Character' => 'A letter, number, symbol or whitespace unit.', 'Word count' => 'The number of detected word tokens in text.', 'Whitespace' => 'Spaces, tabs, line breaks and related separators.', 'Case' => 'The uppercase or lowercase form of letters.', 'Delimiter' => 'A character used to separate values or fields.', 'Encoding' => 'A convention for representing text or bytes.'],
            'references' => [['name' => 'Unicode Standard', 'url' => 'https://www.unicode.org/standard/standard.html']],
        ],
        'shopping' => [
            'glossary' => ['List price' => 'The displayed price before an eligible discount.', 'Discount' => 'A reduction from an original price.', 'Sale price' => 'The amount due after discounts before any later adjustments.', 'Percentage' => 'A proportion expressed per hundred.', 'Tax' => 'A required charge applied under applicable rules.', 'Unit price' => 'The cost for one standard unit of quantity.'],
            'references' => [],
        ],
        'math' => [
            'glossary' => ['Percentage' => 'A proportion expressed per hundred.', 'Ratio' => 'A comparison between two quantities.', 'Average' => 'A representative value calculated from a set.', 'Rounding' => 'Reducing displayed precision according to a rule.', 'Decimal' => 'A base-ten representation including fractional values.', 'Formula' => 'A defined relationship used to calculate a result.'],
            'references' => [],
        ],
        'utility' => [
            'glossary' => ['Conversion factor' => 'A multiplier used to move between equivalent units.', 'Unit' => 'A defined standard used to measure a quantity.', 'QR code' => 'A two-dimensional code that stores scannable data.', 'Timestamp' => 'A value representing a specific point in time.', 'Randomization' => 'Selection designed to avoid a predictable fixed sequence.', 'Precision' => 'The level of numerical detail represented in a result.'],
            'references' => [],
        ],
        'business-tools' => [
            'glossary' => ['Revenue' => 'Income generated before subtracting applicable costs.', 'Cost' => 'Resources spent to produce or deliver an outcome.', 'Margin' => 'Profit expressed relative to revenue.', 'Markup' => 'An amount added relative to cost.', 'Cash flow' => 'Movement of money into and out of a business.', 'Invoice' => 'A document requesting payment for goods or services.'],
            'references' => [['name' => 'GST Portal', 'url' => 'https://www.gst.gov.in/'], ['name' => 'Ministry of Corporate Affairs', 'url' => 'https://www.mca.gov.in/']],
        ],
        'seller-tools' => [
            'glossary' => ['Shipping label' => 'A carrier or marketplace document identifying a parcel.', 'Crop area' => 'The selected portion retained from a source page or image.', 'DPI' => 'Dots per inch used when discussing print density.', 'Bleed' => 'Extra printed area extending beyond a final trim edge.', 'Aspect ratio' => 'The proportional relationship between width and height.', 'Batch processing' => 'Applying one workflow to several source files.'],
            'references' => [],
        ],
        'security-tools' => [
            'glossary' => ['Entropy' => 'A measure of uncertainty relevant to generated secrets.', 'Hash' => 'A one-way digest used for integrity and other documented purposes.', 'Salt' => 'Random data added before password hashing.', 'Encryption' => 'Reversible protection using a key.', 'Encoding' => 'Data representation that provides no secrecy by itself.', 'MFA' => 'Authentication that requires more than one factor.'],
            'references' => [['name' => 'OWASP Foundation', 'url' => 'https://owasp.org/'], ['name' => 'NIST Cybersecurity', 'url' => 'https://www.nist.gov/cybersecurity']],
        ],
        'color-tools' => [
            'glossary' => ['HEX' => 'A hexadecimal notation for RGB color values.', 'RGB' => 'A color model based on red, green and blue light.', 'HSL' => 'A color representation using hue, saturation and lightness.', 'Contrast ratio' => 'A measure of luminance difference between colors.', 'Palette' => 'A coordinated set of colors.', 'Alpha' => 'A value representing opacity or transparency.'],
            'references' => [['name' => 'W3C CSS Color', 'url' => 'https://www.w3.org/TR/css-color-4/'], ['name' => 'W3C Contrast Guidance', 'url' => 'https://www.w3.org/WAI/WCAG21/Understanding/contrast-minimum.html']],
        ],
        'date-time-tools' => [
            'glossary' => ['UTC' => 'The global reference time standard.', 'Timezone' => 'A region that observes a uniform standard time.', 'Unix timestamp' => 'Seconds elapsed since the Unix epoch in UTC.', 'Leap year' => 'A calendar year containing an additional day.', 'Duration' => 'Elapsed time between two points.', 'ISO 8601' => 'An international standard for date and time representation.'],
            'references' => [['name' => 'ISO 8601 overview', 'url' => 'https://www.iso.org/iso-8601-date-and-time-format.html'], ['name' => 'IANA Time Zone Database', 'url' => 'https://www.iana.org/time-zones']],
        ],
        'web-tools' => [
            'glossary' => ['URL' => 'The address of a web resource.', 'HTTP' => 'The protocol used for web requests and responses.', 'DNS' => 'The system that maps domain names to network addresses.', 'User agent' => 'Information identifying client software to a server.', 'Redirect' => 'A response that sends a client to another URL.', 'MIME type' => 'A label describing the format of transferred content.'],
            'references' => [['name' => 'MDN Web Docs', 'url' => 'https://developer.mozilla.org/'], ['name' => 'WHATWG Standards', 'url' => 'https://spec.whatwg.org/'], ['name' => 'W3C Standards', 'url' => 'https://www.w3.org/standards/']],
        ],
    ],

    'pages' => [
        // Editors may override overview, use_cases, practices, mistakes,
        // glossary, faqs, references, last_updated and content_version.
    ],
];

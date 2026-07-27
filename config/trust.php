<?php

return [
    'last_updated' => 'July 27, 2026',
    'last_updated_iso' => '2026-07-27',

    'pages' => [
        'trust' => [
            'name' => 'Trust Center',
            'heading' => 'Trust & Transparency',
            'eyebrow' => 'Toolexa Trust Center',
            'meta_title' => 'Trust Center | How Toolexa Builds and Reviews Tools',
            'meta_description' => 'Learn how Toolexa creates, reviews, tests, updates and maintains accurate online tools and educational content.',
            'keywords' => 'Toolexa trust center, tool accuracy, editorial standards, privacy, testing process',
            'introduction' => [
                'Toolexa is committed to providing accurate, fast and reliable online tools and educational resources. This Trust Center explains the standards, review steps and privacy principles that guide our work.',
                'We want users to understand how a tool reaches publication, what its results mean, how content is reviewed and how to report a concern. Transparency helps people use Toolexa confidently and make informed decisions about important results.',
            ],
            'highlights' => [
                ['icon' => 'TEST', 'title' => 'Tested before release', 'text' => 'Core calculations, expected inputs and edge cases are checked before a tool is published.'],
                ['icon' => 'PRIV', 'title' => 'Privacy conscious', 'text' => 'We avoid unnecessary collection and favor browser-based processing where the tool supports it.'],
                ['icon' => 'OPEN', 'title' => 'Clear limitations', 'text' => 'We explain when outputs are estimates and when an official or professional source should be consulted.'],
                ['icon' => 'FIX', 'title' => 'Corrections welcomed', 'text' => 'Users can report incorrect results, unclear explanations, broken tools and missing features.'],
            ],
            'sections' => [
                [
                    'id' => 'mission',
                    'heading' => 'Our Mission',
                    'paragraphs' => [
                        'Toolexa exists to make useful calculations, conversions and browser utilities easier to access. Students should be able to check a percentage, developers should be able to format data, sellers should be able to prepare routine files, and businesses and everyday users should be able to complete practical tasks without installing specialist software.',
                        'Our tools are free because common digital utilities should be available without a subscription or mandatory account. We support the service through responsible business methods, including advertising where applicable, while keeping access to the core tools open.',
                        'Privacy is part of the product design. We do not ask for information that a tool does not need, and we prefer local browser processing when technically appropriate. Toolexa does not require users to create profiles simply to calculate, convert or learn.',
                    ],
                    'items' => [
                        'Useful tools for students, developers, businesses, online sellers and everyday users',
                        'Free access without unnecessary signup barriers',
                        'Clear interfaces that work across desktop, tablet and mobile screens',
                        'Privacy-conscious processing and no unnecessary personal data collection',
                    ],
                ],
                [
                    'id' => 'build-process',
                    'heading' => 'How We Build Our Tools',
                    'intro' => 'Each tool follows a repeatable workflow designed to catch calculation, usability and compatibility problems before publication.',
                    'steps' => [
                        ['title' => 'Research', 'text' => 'We define the user need, expected inputs, output format and authoritative conventions relevant to the tool.'],
                        ['title' => 'Formula Verification', 'text' => 'For calculators, formulas, assumptions, units and sample results are reviewed before implementation.'],
                        ['title' => 'Development', 'text' => 'The tool is built within Toolexa’s reusable Laravel and browser-side architecture with clear validation and accessible controls.'],
                        ['title' => 'Testing', 'text' => 'Normal values, invalid inputs, boundaries and representative real-world examples are tested.'],
                        ['title' => 'Cross-Browser Testing', 'text' => 'Where practical, behavior is checked in modern Chrome, Edge, Firefox, Safari and mobile browsers.'],
                        ['title' => 'Performance Optimization', 'text' => 'Assets, rendering and interactions are reviewed to keep pages responsive and efficient.'],
                        ['title' => 'Publishing', 'text' => 'The tool is published with instructions, limitations, FAQs and relevant internal links.'],
                        ['title' => 'Regular Updates', 'text' => 'Tools are revisited when requirements, browser behavior, formulas or user feedback indicate a change is needed.'],
                    ],
                ],
                [
                    'id' => 'content-review',
                    'heading' => 'Content Review Process',
                    'paragraphs' => [
                        'Articles, tool instructions and supporting descriptions are reviewed as part of the product, not treated as filler around a utility. The goal is to help a user understand what a tool does, how to use it and how to interpret its output.',
                    ],
                    'items' => [
                        'Accuracy: claims, formulas and explanations are checked for consistency',
                        'Grammar: copy is reviewed for readability, spelling and clear sentence structure',
                        'Examples: practical examples are used where they improve understanding',
                        'Internal linking: related tools, guides, categories and comparisons are connected contextually',
                        'SEO: titles and descriptions are written to describe the page accurately, not to mislead',
                        'User experience: instructions, headings and results are organized for quick scanning',
                        'Periodic review: content is revisited when a correction, product update or changing standard requires it',
                    ],
                ],
                [
                    'id' => 'accuracy',
                    'heading' => 'Accuracy Policy',
                    'paragraphs' => [
                        'Calculator formulas and representative test cases are checked before publication. Input validation and result formatting are also reviewed so that common mistakes are identified clearly.',
                        'Financial calculations are estimates. Rates, fees, taxes, lender rules, government policies, dates and rounding methods may differ from the assumptions used by an online calculator. Important financial, tax, legal or business decisions should always be verified through official documentation or an appropriately qualified professional.',
                    ],
                    'link' => ['label' => 'Read the complete Accuracy Policy', 'route' => 'trust.accuracy-policy'],
                ],
                [
                    'id' => 'editorial',
                    'heading' => 'Editorial Standards',
                    'intro' => 'Every Toolexa article aims to be:',
                    'items' => [
                        'Original and created for the needs of Toolexa users',
                        'Easy to understand without removing important context',
                        'Fact checked against reliable or official material where appropriate',
                        'Updated when the subject, tool or referenced standard changes',
                        'Written independently and not copied from other websites',
                        'Transparent about estimates, limitations and professional-advice boundaries',
                    ],
                    'link' => ['label' => 'Read the Editorial Policy', 'route' => 'trust.editorial-policy'],
                ],
                [
                    'id' => 'privacy',
                    'heading' => 'Privacy First',
                    'paragraphs' => [
                        'Toolexa minimizes the information required to use its tools. Many compatible utilities process inputs directly in the browser. When a tool requires file handling, files are processed only for the requested operation and are never intentionally retained permanently by Toolexa.',
                        'We do not sell personal information. Hosting, analytics or advertising providers may process limited technical data according to their own policies and applicable consent settings. Users should avoid uploading confidential material to any online service unless they understand and accept the relevant risk.',
                    ],
                ],
                [
                    'id' => 'browsers',
                    'heading' => 'Supported Browsers',
                    'intro' => 'Toolexa is designed for current versions of widely used browsers:',
                    'badges' => ['Google Chrome', 'Microsoft Edge', 'Mozilla Firefox', 'Apple Safari', 'Modern Mobile Browsers'],
                    'paragraphs' => [
                        'Older or unsupported browsers may lack APIs required by advanced file, image, PDF or browser utilities. Updating the browser generally provides the best security, compatibility and performance.',
                    ],
                ],
                [
                    'id' => 'standards',
                    'heading' => 'Open Source & Standards',
                    'paragraphs' => [
                        'Toolexa is built with established web technologies and common standards such as semantic HTML, responsive CSS, JavaScript browser APIs, structured data and accessible form patterns. The application uses the Laravel ecosystem and other established libraries where they provide a suitable, maintainable solution.',
                        'Using modern standards helps tools behave consistently across supported browsers. Third-party or open-source components are evaluated for their role in the product and remain subject to their respective licenses.',
                    ],
                ],
                [
                    'id' => 'feedback',
                    'heading' => 'Feedback and Corrections',
                    'paragraphs' => [
                        'Real user reports are an important part of maintaining quality. Please contact us if you find an incorrect calculation, broken tool, unclear explanation, missing feature or an opportunity to improve an existing workflow.',
                        'Include the page URL, the inputs you used, the result you expected and your browser or device when relevant. These details help us reproduce and review the issue efficiently.',
                    ],
                    'items' => [
                        'Incorrect calculations or unexpected results',
                        'Broken controls, downloads or conversions',
                        'Suggestions for clearer content or user experience',
                        'Missing features and requests for new tools',
                    ],
                    'cta' => ['label' => 'Contact Us', 'route' => 'page.show', 'parameters' => ['page' => 'contact']],
                ],
            ],
            'faqs' => [
                ['question' => 'Are Toolexa tools guaranteed to be completely accurate?', 'answer' => 'Tools are tested with representative inputs before publishing, but results can still depend on user input, assumptions, rounding and changing external rules. Important results should be independently verified.'],
                ['question' => 'Are financial calculator results official quotes?', 'answer' => 'No. Financial calculator outputs are planning estimates and are not offers, statements or official calculations from a bank, government department, tax authority or financial advisor.'],
                ['question' => 'Does Toolexa store files uploaded to its tools?', 'answer' => 'Many compatible tools work locally in the browser. When processing requires temporary file handling, Toolexa does not intentionally retain the uploaded file permanently.'],
                ['question' => 'Does Toolexa sell personal information?', 'answer' => 'No. Toolexa does not sell personal information. Limited technical information may be processed by hosting, analytics or advertising providers under their applicable policies.'],
                ['question' => 'How often are tools and articles updated?', 'answer' => 'Pages are reviewed when formulas, standards, browser behavior, product features or reliable user feedback indicate that an update is required.'],
                ['question' => 'Which browsers does Toolexa support?', 'answer' => 'Toolexa targets current versions of Chrome, Edge, Firefox, Safari and modern mobile browsers. Some advanced tools require newer browser APIs.'],
                ['question' => 'Why does Toolexa display advertisements?', 'answer' => 'Advertising may help fund hosting, development and maintenance while allowing the core tools to remain free. Advertisements do not determine calculation results or editorial conclusions.'],
                ['question' => 'How can I report a bug or incorrect calculation?', 'answer' => 'Use the Contact page and include the tool URL, inputs, expected result and browser or device details. This information helps reproduce the issue.'],
                ['question' => 'Can I request a new tool or feature?', 'answer' => 'Yes. Tool ideas, missing features and workflow suggestions can be submitted through the Contact page for consideration.'],
                ['question' => 'Is Toolexa educational content professional advice?', 'answer' => 'No. Articles and explanations are general educational information and do not replace financial, legal, tax, medical or other professional advice.'],
                ['question' => 'Can Toolexa tools be used for commercial work?', 'answer' => 'The tools can generally be used for lawful personal, educational or commercial tasks, subject to the Terms and Conditions. Users remain responsible for verifying outputs and complying with applicable requirements.'],
                ['question' => 'How are corrections handled?', 'answer' => 'Reported issues are reviewed against the tool logic, content and relevant source material. Confirmed errors are corrected and related pages may also be reviewed for consistency.'],
            ],
        ],

        'editorial-policy' => [
            'name' => 'Editorial Policy',
            'heading' => 'Editorial Policy',
            'eyebrow' => 'Trust Center',
            'meta_title' => 'Editorial Policy | Toolexa Content Standards',
            'meta_description' => 'Read how Toolexa researches, writes, reviews, updates and corrects educational articles and tool content.',
            'keywords' => 'Toolexa editorial policy, content review, fact checking, corrections policy',
            'introduction' => [
                'This Editorial Policy explains how Toolexa plans, creates, reviews and maintains educational articles, tool descriptions, instructions and supporting content.',
                'Our objective is useful, original information that helps readers complete a task or understand a tool. Search visibility and advertising considerations do not override accuracy, clarity or user safety.',
            ],
            'sections' => [
                ['id' => 'creation', 'heading' => 'Content Creation Workflow', 'paragraphs' => ['A topic begins with a defined user need. We identify the question being answered, the relevant Toolexa tools and the level of detail needed for a reader to act confidently. Drafts are structured with descriptive headings, plain language, practical examples and clear limitations.'], 'items' => ['Define the search intent and user problem', 'Research the topic and relevant terminology', 'Create an original outline and draft', 'Add practical examples and links to relevant tools', 'Review accuracy, readability and page experience before publishing']],
                ['id' => 'review', 'heading' => 'Review Process', 'paragraphs' => ['Content is reviewed for internal consistency, grammar, factual accuracy and usefulness. Formula explanations are compared with the behavior of the corresponding tool. Links, examples, headings and metadata are checked before publication.'], 'items' => ['Claims and calculations match the described method', 'Examples are realistic and clearly labeled', 'Professional-advice boundaries are stated when relevant', 'Internal links provide a useful next step', 'The page is readable on mobile and desktop']],
                ['id' => 'updates', 'heading' => 'Updating Articles', 'paragraphs' => ['Articles may be updated when a connected tool changes, an external standard is revised, a reliable correction is received or the explanation can be made more useful. The review date reflects a meaningful content review rather than an automatic date change.']],
                ['id' => 'sources', 'heading' => 'Source Verification', 'paragraphs' => ['Where a topic depends on laws, official rates, technical standards or product documentation, we prefer primary sources such as government publications, standards organizations and official technical documentation. Sources are evaluated for authority, relevance and recency. General concepts may be explained from established knowledge without reproducing another publisher’s text.']],
                ['id' => 'corrections', 'heading' => 'Corrections Policy', 'paragraphs' => ['Users can report a suspected error through the Contact page. We review the claim, reproduce the relevant example where possible and compare it with the tool logic or source material. Confirmed errors are corrected promptly in proportion to their impact, and connected pages are checked when the issue may be broader.'], 'cta' => ['label' => 'Report a Content Issue', 'route' => 'page.show', 'parameters' => ['page' => 'contact']]],
                ['id' => 'independence', 'heading' => 'Editorial Independence', 'paragraphs' => ['Advertising, partnerships and commercial considerations do not determine a tool’s result or the conclusion of educational content. Sponsored material, if introduced, should be identified clearly. Toolexa does not accept payment to present an inaccurate formula, misleading claim or undisclosed recommendation.']],
            ],
        ],

        'accuracy-policy' => [
            'name' => 'Accuracy Policy',
            'heading' => 'Accuracy Policy',
            'eyebrow' => 'Trust Center',
            'meta_title' => 'Accuracy Policy | Toolexa Calculator Standards',
            'meta_description' => 'Understand Toolexa calculation limits, rounding, formula validation and financial-result disclaimers.',
            'keywords' => 'Toolexa accuracy policy, calculator limitations, formula validation, rounding',
            'introduction' => [
                'Toolexa aims to produce dependable results for the inputs and assumptions shown by each tool. This policy explains how calculations are checked and why some real-world results can differ.',
                'An online calculator is useful for planning and comparison, but it cannot account for every contract term, regulation, institution-specific method or professional judgment.',
            ],
            'sections' => [
                ['id' => 'validation', 'heading' => 'Formula Validation', 'paragraphs' => ['Before publication, calculator formulas are reviewed against their stated mathematical method and tested with representative examples. Where practical, known results or independently calculated samples are used to confirm the implementation. Input validation is added to reduce common mistakes such as missing values or invalid ranges.']],
                ['id' => 'limitations', 'heading' => 'Calculation Limitations', 'paragraphs' => ['Results are only as reliable as the inputs and assumptions provided. A tool may use a simplified model, fixed period convention or commonly accepted formula that differs from the method used by a particular institution. Dates, compounding schedules, fees, taxes and policy changes can affect actual outcomes.'], 'items' => ['Incorrect or incomplete inputs produce misleading outputs', 'Real providers may use different schedules or conventions', 'External rates and rules can change after publication', 'A general calculator cannot evaluate individual legal or financial circumstances']],
                ['id' => 'rounding', 'heading' => 'Rounding and Display Precision', 'paragraphs' => ['Tools may round intermediate or final values to make results readable. Small differences can occur when another system retains more decimal places, rounds at a different stage or uses a different day-count convention. Displayed currency values should not be treated as exact settlement amounts unless an official source confirms them.']],
                ['id' => 'financial', 'heading' => 'Financial Disclaimer', 'paragraphs' => ['EMI, interest, investment, tax, GST, loan and other financial results are estimates for general information. They are not financial advice, approval decisions, tax filings or official quotes. Verify significant decisions with the relevant bank, government authority, accountant, financial adviser or other qualified professional.']],
                ['id' => 'reporting', 'heading' => 'Reporting an Accuracy Concern', 'paragraphs' => ['If a result appears incorrect, send the page URL, all input values, the displayed result, the expected result and the source or method used for comparison. We will review reproducible reports and correct confirmed issues.'], 'cta' => ['label' => 'Report an Accuracy Issue', 'route' => 'page.show', 'parameters' => ['page' => 'contact']]],
            ],
        ],

        'how-we-test-tools' => [
            'name' => 'How We Test Tools',
            'heading' => 'How We Test Toolexa Tools',
            'eyebrow' => 'Trust Center',
            'meta_title' => 'How We Test Tools | Toolexa Quality Process',
            'meta_description' => 'See how Toolexa manually tests edge cases, browsers, responsive layouts, performance and regressions.',
            'keywords' => 'Toolexa testing, tool quality, browser testing, responsive testing, regression tests',
            'introduction' => [
                'Testing is part of the Toolexa publishing workflow. The exact checks vary by tool because a finance calculator, text utility and browser-based file converter have different risks and technical requirements.',
                'Our goal is to verify the primary task, handle common mistakes clearly and ensure the page remains usable across supported screen sizes and modern browsers.',
            ],
            'sections' => [
                ['id' => 'manual', 'heading' => 'Manual Testing', 'paragraphs' => ['A reviewer follows the tool workflow as a user would: entering representative inputs, submitting or processing them, checking the output and repeating the task with changed values. Labels, validation messages, copy actions, downloads and reset behavior are checked where applicable.']],
                ['id' => 'edge-cases', 'heading' => 'Edge-Case Testing', 'paragraphs' => ['Testing includes boundaries and unexpected inputs that could expose incorrect assumptions or broken interfaces. The relevant cases depend on the tool.'], 'items' => ['Empty, zero, negative or unusually large numeric values', 'Whitespace, symbols, Unicode and long text', 'Unsupported, damaged or unusually large files', 'Invalid formats and missing required fields', 'Repeated actions and changes after a result is displayed']],
                ['id' => 'browsers', 'heading' => 'Cross-Browser Testing', 'paragraphs' => ['Core workflows are checked in modern browser engines where practical, including Chrome, Edge, Firefox and Safari. Browser-specific APIs and file-processing features are reviewed for graceful error handling when a capability is unavailable.']],
                ['id' => 'responsive', 'heading' => 'Responsive Testing', 'paragraphs' => ['Pages are reviewed at desktop, tablet and mobile widths. We check that forms remain readable, controls are touch friendly, tables and long outputs do not break the viewport, and result actions remain accessible without overlapping content.']],
                ['id' => 'performance', 'heading' => 'Performance Testing', 'paragraphs' => ['We review asset size, rendering work and interaction behavior to reduce unnecessary delays. Images and below-the-fold content are optimized where appropriate, scripts are deferred or route-specific when practical, and expensive reusable data is cached.']],
                ['id' => 'regression', 'heading' => 'Regression Testing', 'paragraphs' => ['Automated feature and unit tests protect shared routes, SEO metadata, search, internal links, category pages, comparisons, topic hubs, workspace behavior and performance requirements. The broader test suite is run before a release so a change to one feature is less likely to break another.']],
                ['id' => 'feedback', 'heading' => 'Testing Continues After Publishing', 'paragraphs' => ['No test process can reproduce every device, file or real-world scenario. User reports are reviewed as additional test cases. Confirmed bugs are corrected and regression coverage may be added to prevent the same issue from returning.'], 'cta' => ['label' => 'Report a Tool Problem', 'route' => 'page.show', 'parameters' => ['page' => 'contact']]],
            ],
        ],
    ],
];

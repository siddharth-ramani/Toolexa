<?php

return [
    'version' => 2,
    'words_per_minute' => 220,

    'category_profiles' => [
        'Finance' => ['audience' => 'individuals, students, households, accountants and business owners', 'application' => 'budgeting, financial planning, invoice checks and scenario comparison'],
        'Shopping' => ['audience' => 'shoppers, retailers and online sellers', 'application' => 'price comparison, purchase planning and transparent discount checks'],
        'Math' => ['audience' => 'students, teachers, analysts and everyday users', 'application' => 'study, reporting, measurement and quick numerical checks'],
        'Utility' => ['audience' => 'students, professionals and everyday browser users', 'application' => 'routine personal tasks, record checking and productivity'],
        'Utility Tools' => ['audience' => 'students, professionals and everyday browser users', 'application' => 'routine personal tasks, record checking and productivity'],
        'Text Tools' => ['audience' => 'writers, students, editors, marketers and developers', 'application' => 'drafting, editing, cleanup and publishing workflows'],
        'Developer Tools' => ['audience' => 'web developers, software engineers, testers and technical students', 'application' => 'debugging, data exchange, code review and development workflows'],
        'Color Tools' => ['audience' => 'designers, developers, marketers and content creators', 'application' => 'brand design, interface work and accessible color decisions'],
        'Business Tools' => ['audience' => 'business owners, analysts, operations teams and freelancers', 'application' => 'planning, reporting and day-to-day business decisions'],
        'Image Tools' => ['audience' => 'designers, sellers, students, marketers and website owners', 'application' => 'publishing, ecommerce, social media and document preparation'],
        'SEO Tools' => ['audience' => 'website owners, SEO specialists, writers and developers', 'application' => 'technical audits, content optimization and search visibility'],
        'Security Tools' => ['audience' => 'developers, administrators and privacy-conscious users', 'application' => 'safer account practices, validation and security checks'],
        'Date & Time Tools' => ['audience' => 'remote teams, travelers, developers and planners', 'application' => 'scheduling, timestamp checks and international coordination'],
        'PDF Tools' => ['audience' => 'students, offices, sellers and document professionals', 'application' => 'document preparation, inspection and sharing'],
        'Seller Tools' => ['audience' => 'marketplace sellers, ecommerce operators and fulfillment teams', 'application' => 'label preparation, order processing and shipment workflows'],
        'Web Tools' => ['audience' => 'web developers, site owners, QA teams and technical users', 'application' => 'browser diagnostics, website maintenance and implementation checks'],
    ],

    'references' => [
        'gst' => [
            ['title' => 'GST Portal', 'url' => 'https://www.gst.gov.in/', 'publisher' => 'Goods and Services Tax Council'],
            ['title' => 'Central Board of Indirect Taxes and Customs', 'url' => 'https://www.cbic.gov.in/', 'publisher' => 'Government of India'],
        ],
        'epf' => [['title' => 'Employees’ Provident Fund Organisation', 'url' => 'https://www.epfindia.gov.in/', 'publisher' => 'EPFO']],
        'nps' => [['title' => 'National Pension System', 'url' => 'https://www.npscra.nsdl.co.in/', 'publisher' => 'NPS CRA']],
        'finance' => [['title' => 'Reserve Bank of India', 'url' => 'https://www.rbi.org.in/', 'publisher' => 'RBI']],
        'web' => [
            ['title' => 'MDN Web Docs', 'url' => 'https://developer.mozilla.org/', 'publisher' => 'Mozilla'],
            ['title' => 'W3C Web Standards', 'url' => 'https://www.w3.org/standards/', 'publisher' => 'World Wide Web Consortium'],
        ],
        'security' => [['title' => 'OWASP Foundation', 'url' => 'https://owasp.org/', 'publisher' => 'OWASP']],
        'pdf' => [['title' => 'PDF Reference and Resources', 'url' => 'https://opensource.adobe.com/dc-acrobat-sdk-docs/', 'publisher' => 'Adobe']],
    ],

    'overrides' => [
        'age-calculator' => [
            'steps' => [
                [
                    'title' => 'Select your date of birth',
                    'description' => 'Open the Date of Birth field and choose the correct day, month and year from the calendar.',
                ],
                [
                    'title' => 'Check the selected date',
                    'description' => 'Confirm that the birth date is accurate and is not a future date before calculating.',
                ],
                [
                    'title' => 'Calculate your exact age',
                    'description' => 'Press Calculate Age. The calculator compares your birth date with today’s date automatically.',
                ],
                [
                    'title' => 'Read the complete result',
                    'description' => 'Review your age in completed years, remaining months and days. You can then share the result if needed.',
                ],
            ],
            'steps_note' => 'For an accurate result, enter the date exactly as shown on the relevant birth or identity record. The calculation uses today’s date.',
        ],
        'gst-calculator' => [
            'examples' => [
                ['title' => 'Standard 18% GST purchase', 'input' => 'Base price: ₹1,000 · GST rate: 18%', 'action' => 'Multiply ₹1,000 by 18 ÷ 100.', 'outcome' => 'GST: ₹180 · Total price: ₹1,180'],
                ['title' => 'Reduced 5% GST rate', 'input' => 'Base price: ₹2,500 · GST rate: 5%', 'action' => 'Multiply ₹2,500 by 5 ÷ 100.', 'outcome' => 'GST: ₹125 · Total price: ₹2,625'],
                ['title' => 'Invoice check at 12%', 'input' => 'Taxable value: ₹8,000 · GST rate: 12%', 'action' => 'Calculate the tax before adding it to the taxable value.', 'outcome' => 'GST: ₹960 · Invoice total: ₹8,960'],
                ['title' => 'High-value 28% example', 'input' => 'Base price: ₹50,000 · GST rate: 28%', 'action' => 'Apply 28% to the amount before tax.', 'outcome' => 'GST: ₹14,000 · Total price: ₹64,000'],
            ],
            'formula_variables' => [
                ['symbol' => 'Base Amount', 'meaning' => 'The taxable price before GST is added.'],
                ['symbol' => 'GST Rate', 'meaning' => 'The applicable tax percentage, such as 5%, 12%, 18% or 28%.'],
                ['symbol' => 'GST Amount', 'meaning' => 'The tax calculated from the base amount and rate.'],
                ['symbol' => 'Total Amount', 'meaning' => 'The base amount plus the GST amount.'],
            ],
        ],
        'percentage-calculator' => [
            'examples' => [
                ['title' => 'Exam score', 'input' => 'Scored marks: 72 · Total marks: 80', 'action' => 'Divide 72 by 80 and multiply by 100.', 'outcome' => 'Percentage: 90%'],
                ['title' => 'Completed tasks', 'input' => 'Completed: 45 · Total: 60', 'action' => 'Calculate 45 as a share of 60.', 'outcome' => 'Completion rate: 75%'],
                ['title' => 'Survey response', 'input' => 'Positive responses: 84 · Responses: 120', 'action' => 'Divide the positive count by the total.', 'outcome' => 'Positive response rate: 70%'],
            ],
        ],
        'discount-calculator' => [
            'examples' => [
                ['title' => 'Season sale', 'input' => 'Original price: ₹2,000 · Discount: 25%', 'action' => 'Calculate 25% of ₹2,000 and subtract it.', 'outcome' => 'Savings: ₹500 · Sale price: ₹1,500'],
                ['title' => 'Electronics offer', 'input' => 'Original price: ₹45,000 · Discount: 10%', 'action' => 'Calculate the discount before checkout.', 'outcome' => 'Savings: ₹4,500 · Sale price: ₹40,500'],
                ['title' => 'Book purchase', 'input' => 'Original price: ₹800 · Discount: 15%', 'action' => 'Subtract 15% of the listed price.', 'outcome' => 'Savings: ₹120 · Sale price: ₹680'],
            ],
        ],
    ],
];

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
        'how-to-calculate-marketplace-profit-per-order' => [
            'manually_edited' => true,
            'append_generated_faqs' => false,
            'content_version' => '2.0',
            'summary' => [
                'Marketplace payout is not profit: product cost and seller-funded expenses still need to be deducted.',
                'The Toolexa calculator taxes the entered commission, shipping fee and other marketplace fees as one fee base; change the inputs if your statement taxes them differently.',
                'With the worked inputs used below, estimated payout is ₹698.28, profit is ₹248.28 and margin is 24.85%.',
                'Reconcile the estimate against an actual settlement because fee names, tax treatment and return deductions vary by platform and category.',
            ],
            'introduction' => [
                'A marketplace order can show ₹999 in sales without putting ₹999 in the seller’s bank account. Commission, logistics charges, fixed fees and tax on platform services reduce the settlement. Product cost then reduces what remains. Keeping those two stages separate—platform payout first, business profit second—makes the calculation easier to audit.',
                'This guide follows the exact inputs and output used by Toolexa’s Marketplace Profit Calculator. It includes one fully reconciled example, explains the calculator’s fee-tax assumption, and shows how to compare the estimate with a real settlement statement. It does not invent a universal marketplace fee rate: sellers must use the rates and taxable fee lines shown in their current platform documents.',
            ],
            'steps' => [
                'Enter the actual customer selling price after any seller-funded discount, not the printed MRP.',
                'Enter landed product cost, including the purchase or manufacturing cost you want the order to recover.',
                'Copy the commission rate, shipping charge and other marketplace fees from the applicable fee card or settlement.',
                'Enter the tax rate applied to those platform fees. If only some fee lines are taxable, combine the taxable and non-taxable amounts outside the calculator or verify the result line by line.',
                'Calculate, then compare Estimated Payout with the settlement amount and Estimated Profit with your own order-cost record.',
            ],
            'sections' => [
                [
                    'heading' => 'The Two Calculations: Payout and Profit',
                    'paragraphs' => [
                        'Estimated payout is the selling price minus commission, shipping, other entered marketplace fees and tax on those fees. It represents what remains after the deductions modeled by the calculator; it is not a promise of the amount a platform will remit.',
                        'Estimated profit subtracts product cost from that payout. Packaging, advertising, return-to-origin loss, warehousing and seller-funded promotions are not separate inputs in this calculator. Add them to Product Cost or Other Marketplace Fees when they belong to the order and you want them reflected in the estimate.',
                    ],
                ],
                [
                    'heading' => 'One Reconciled Worked Example',
                    'paragraphs' => [
                        'Use these inputs: selling price ₹999, product cost ₹450, commission 15%, shipping fee ₹80, other marketplace fees ₹25 and tax on marketplace fees 18%. Commission is ₹999 × 15% = ₹149.85. The calculator’s fee base is ₹149.85 + ₹80 + ₹25 = ₹254.85.',
                        'Tax on that fee base is ₹254.85 × 18% = ₹45.87 after display rounding. Total modeled platform deductions are ₹300.72, so estimated payout is ₹999 − ₹300.72 = ₹698.28. Estimated profit is ₹698.28 − ₹450 = ₹248.28, and profit margin is ₹248.28 ÷ ₹999 × 100 = 24.85%. The displayed values reconcile to the calculator output within normal two-decimal rounding.',
                    ],
                ],
                [
                    'heading' => 'The Fee-tax Assumption You Must Check',
                    'paragraphs' => [
                        'The calculator applies the entered tax rate to the sum of commission, shipping fee and other marketplace fees. A real tax invoice may apply tax to a different set of service lines. For example, a platform may list taxable fulfilment charges separately or include a fee that should not be placed in this combined base.',
                        'Before relying on the estimate, compare the calculator’s Total Marketplace Fees with the fee and tax lines on one completed settlement. If the tax base differs, calculate those lines separately and use Other Marketplace Fees for the resulting amount, or keep a spreadsheet for platform-specific reconciliation.',
                    ],
                ],
                [
                    'heading' => 'From One Order to a Product Decision',
                    'paragraphs' => [
                        'Profit amount answers how many rupees remain in this scenario. Profit margin divides that amount by selling price, making products at different price points easier to compare. Margin is still not the same as monthly business profit because shared overhead and return losses may sit outside the order calculation.',
                        'Run a baseline price, a discounted price and a higher-cost shipping scenario. Change one input per run so the reason for the difference remains visible. A product with a comfortable baseline but a negative promotional scenario may need a smaller discount or a different campaign structure.',
                    ],
                ],
                [
                    'heading' => 'Reconcile Against a Settlement Statement',
                    'paragraphs' => [
                        'Choose one delivered order and record the selling price, each platform deduction, tax on service fees and the net settlement. Match those lines to the calculator inputs rather than forcing unrelated deductions into commission. Then compare estimated payout with the statement’s order-level amount.',
                        'Investigate differences instead of hiding them in a percentage. Common causes include weight adjustments, collection fees, seller-funded coupons, withholding entries, prior-period corrections and return charges. Update the scenario when the platform changes its fee card or the product moves to another price or weight band.',
                    ],
                ],
                [
                    'heading' => 'Limitations of the Calculator',
                    'paragraphs' => [
                        'The calculator models the values entered; it does not fetch current marketplace fees, read a settlement file, calculate input-tax credit or predict return rates. It also does not determine whether a deduction is legally or contractually correct.',
                        'Use the result for planning and a first reconciliation. For tax filing, contractual disputes or accounting treatment, retain the platform invoices and consult the relevant official guidance or a qualified professional.',
                    ],
                ],
            ],
            'mistakes' => [
                'Entering MRP when the order was sold at a lower customer price.',
                'Treating estimated payout as profit before subtracting product and operating costs.',
                'Applying a commission rate from another product category or price band.',
                'Assuming every platform fee belongs to the same taxable fee base.',
                'Omitting seller-funded discounts, packaging, advertising or expected return losses from the scenario.',
                'Comparing the estimate with a settlement that contains adjustments from other orders or periods.',
            ],
            'blocks' => [
                ['type' => 'important', 'label' => 'Calculator assumption', 'text' => 'Tax is calculated on commission plus the entered shipping and other marketplace fees. Check this combined base against the service-fee tax lines on your statement.'],
                ['type' => 'practice', 'label' => 'Verified example', 'text' => 'For ₹999 selling price and the inputs shown in the worked example, the calculator displays ₹698.28 payout, ₹248.28 profit and 24.85% margin.'],
                ['type' => 'tip', 'label' => 'Settlement check', 'text' => 'Reconcile one delivered order line by line before applying the same assumptions across a catalog.'],
            ],
            'faqs' => [
                ['question' => 'Is marketplace payout the same as profit?', 'answer' => 'No. Payout is what remains after the modeled platform deductions. Product cost and any operating costs not entered still need to be subtracted.'],
                ['question' => 'What selling price should I enter?', 'answer' => 'Enter the actual order selling price after seller-funded discounts, unless your marketplace fee document explicitly uses another base.'],
                ['question' => 'Which fees does the calculator tax?', 'answer' => 'It applies the entered tax rate to commission, shipping fee and other marketplace fees combined. Verify that assumption against your platform invoice.'],
                ['question' => 'Where should packaging or advertising cost go?', 'answer' => 'Add order-specific packaging to Product Cost or Other Marketplace Fees consistently. Advertising is better tracked separately unless you can allocate a reliable amount to the order.'],
                ['question' => 'Does the calculator include return or RTO losses?', 'answer' => 'Not automatically. Add an expected per-order return allowance to the relevant cost input or evaluate it with the Return & RTO Cost Calculator.'],
                ['question' => 'Why does my settlement differ from the estimate?', 'answer' => 'Possible causes include different taxable fee lines, weight adjustments, collection fees, coupons, withholding entries, returns or prior-period corrections.'],
                ['question' => 'How is profit margin calculated?', 'answer' => 'Estimated profit is divided by the entered selling price and multiplied by 100.'],
                ['question' => 'Can I use the result for tax filing?', 'answer' => 'Use it as a planning or reconciliation aid, not as a replacement for platform tax invoices, official records or professional advice.'],
            ],
            'references' => [
                ['name' => 'GST Portal', 'url' => 'https://www.gst.gov.in/'],
            ],
        ],
    ],
];

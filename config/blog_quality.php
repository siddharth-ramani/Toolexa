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
        'cod-charges-seller-payout-explained' => [
            'manually_edited' => true,
            'append_generated_faqs' => false,
            'content_version' => '2.0',
            'summary' => [
                'A COD agreement may charge a percentage of order value, a fixed collection fee, or both.',
                'Toolexa calculates tax on the percentage fee plus fixed fee; verify that fee base against the current courier or marketplace invoice.',
                'For a ₹1,200 order, 2% fee, ₹30 fixed fee and 18% tax, the calculator returns ₹63.72 total COD cost and ₹1,136.28 estimated payout.',
                'Estimated payout after COD cost is not profit because product cost, shipping, marketplace fees, returns and other deductions are excluded.',
            ],
            'introduction' => [
                'Cash on delivery adds a collection and remittance service to an ecommerce shipment. Depending on the courier or marketplace agreement, the seller may pay a percentage of the order value, a fixed fee, or both. Tax can then be added to those service fees. These deductions reduce the amount remaining from a successful COD order, even before product cost, shipping and marketplace charges are considered.',
                'This guide follows the exact calculation used by Toolexa’s COD Fee Calculator. It reconciles one example line by line, identifies the calculator’s tax-base assumption and explains why estimated payout is not the same as profit. Use the rates on your current fee card or invoice—there is no single COD rate that applies to every platform, courier, category, order value or seller contract.',
            ],
            'steps' => [
                'Enter the amount on which your provider calculates the COD percentage charge. Use the actual eligible order value, not MRP, unless the agreement says otherwise.',
                'Enter the COD percentage from the current courier or marketplace fee card.',
                'Enter the fixed COD or collection fee separately; do not assume it is included in the percentage rate.',
                'Enter the tax rate applied to the COD service-fee base. Confirm the taxable lines on the invoice.',
                'Calculate, then compare each output with a completed settlement before using the assumption for more orders.',
            ],
            'sections' => [
                [
                    'heading' => 'What a COD Charge Covers',
                    'paragraphs' => [
                        'A COD fee pays for collecting money from the customer and remitting it through the courier or marketplace process. It is separate from the product price and can also be separate from forward shipping, marketplace commission, closing fees and return-to-origin charges.',
                        'Some providers quote only a percentage, some quote a fixed amount per delivered order, and some apply both. A minimum COD fee may also override a low percentage result. The current agreement and settlement statement are the source of truth for the structure that applies to your account.',
                    ],
                ],
                [
                    'heading' => 'The Formula Used by the Calculator',
                    'paragraphs' => [
                        'Percentage COD fee equals order value multiplied by COD rate divided by 100. Base COD fee equals the percentage fee plus the fixed fee. The calculator then multiplies that combined base by the entered tax rate.',
                        'Total COD cost equals base COD fee plus tax on that base. Estimated payout equals order value minus total COD cost. This last figure removes only the modeled COD charges; it does not remove product cost, freight, marketplace commission, withholding, advertising or return losses.',
                    ],
                ],
                [
                    'heading' => 'One Reconciled ₹1,200 Example',
                    'paragraphs' => [
                        'Use order value ₹1,200, COD charge 2%, fixed COD fee ₹30 and tax on COD fees 18%. The percentage fee is ₹1,200 × 2 ÷ 100 = ₹24. Adding the fixed fee creates a taxable fee base of ₹24 + ₹30 = ₹54.',
                        'Tax is ₹54 × 18 ÷ 100 = ₹9.72. Total COD cost is ₹54 + ₹9.72 = ₹63.72. The calculator therefore displays an estimated payout of ₹1,200 − ₹63.72 = ₹1,136.28. These figures have been checked against the calculator’s current formula.',
                    ],
                ],
                [
                    'heading' => 'Check the Tax Base Before Relying on the Result',
                    'paragraphs' => [
                        'Toolexa applies the entered tax rate to both the percentage COD fee and the fixed COD fee. A real invoice may name, group or tax service lines differently. If your provider taxes another base, the calculator result will not match until the inputs or separate working reflect that invoice treatment.',
                        'Compare the Percentage COD Fee, Fixed COD Fee and Tax on COD Fees outputs with one successfully delivered order. If a minimum fee, collection slab or extra service charge appears on the statement, record it separately rather than hiding the difference in an invented percentage.',
                    ],
                ],
                [
                    'heading' => 'Payout Is Not Seller Profit',
                    'paragraphs' => [
                        'The estimated payout on this calculator means order value after the entered COD cost only. It is not the final remittance promised by a marketplace and it is not profit. Other platform deductions can still reduce settlement, while product and operating costs reduce profit after settlement.',
                        'Use Marketplace Profit Calculator when you need to include product cost, commission, shipping and other marketplace fees. Track COD delivery success and return-to-origin cost separately because a failed COD shipment can incur logistics cost without generating collected revenue.',
                    ],
                ],
                [
                    'heading' => 'How to Use COD Cost in a Seller Decision',
                    'paragraphs' => [
                        'Calculate the fee for a normal order, a low-value order and a high-value order. Fixed fees weigh more heavily on inexpensive products, while percentage fees grow with order value. Compare COD and prepaid contribution using the same product and shipping assumptions.',
                        'Review actual settlements regularly instead of treating one calculation as permanent. Fee cards, tax treatment, service slabs and remittance terms can change. Preserve the fee document used for an important pricing decision so the result can be reproduced later.',
                    ],
                ],
                [
                    'heading' => 'Limitations of This Estimate',
                    'paragraphs' => [
                        'The calculator does not fetch provider rates, apply minimum-fee rules automatically, predict COD delivery success or calculate return-to-origin loss. It also does not validate whether a provider’s tax treatment or deduction is contractually correct.',
                        'Use the result for planning and line-by-line checking. For accounting, tax filing or a disputed settlement, retain the provider invoice and agreement and use the appropriate official guidance or qualified professional advice.',
                    ],
                ],
            ],
            'mistakes' => [
                'Using MRP when the provider applies its COD percentage to the actual order value.',
                'Entering only the percentage fee even though the agreement also adds a fixed collection fee.',
                'Applying tax to the full product value when the invoice taxes only COD service fees.',
                'Calling estimated payout “profit” before other marketplace and business costs are deducted.',
                'Ignoring a minimum COD charge, fee slab or provider-specific condition.',
                'Using an old fee card without reconciling the calculation against a recent delivered-order settlement.',
            ],
            'blocks' => [
                ['type' => 'important', 'label' => 'Calculator assumption', 'text' => 'Tax is applied to the percentage COD fee plus the fixed COD fee. Verify this combined fee base against your invoice.'],
                ['type' => 'note', 'label' => 'Formula check', 'text' => 'Percentage fee = order value × rate ÷ 100; total COD cost = percentage fee + fixed fee + tax on those fees.'],
                ['type' => 'practice', 'label' => 'Verified example', 'text' => '₹1,200 at 2% plus ₹30 fixed fee and 18% tax produces ₹63.72 COD cost and ₹1,136.28 estimated payout.'],
                ['type' => 'tip', 'label' => 'Settlement check', 'text' => 'Test the assumptions against one successfully delivered order before applying them across a catalog.'],
            ],
            'faqs' => [
                ['question' => 'How is the percentage COD fee calculated?', 'answer' => 'Multiply the applicable order value by the COD percentage rate and divide by 100.'],
                ['question' => 'Can a percentage fee and fixed COD fee both apply?', 'answer' => 'Yes. The Toolexa calculator supports both because many agreements can contain two separate charge components. Check your current fee card.'],
                ['question' => 'What amount does the calculator tax?', 'answer' => 'It applies the entered tax rate to the percentage COD fee plus the fixed COD fee. Verify that base against the service-fee lines on your invoice.'],
                ['question' => 'What is the total COD cost for the ₹1,200 example?', 'answer' => 'At 2% plus a ₹30 fixed fee and 18% tax on both fees, percentage charge is ₹24, tax is ₹9.72 and total COD cost is ₹63.72.'],
                ['question' => 'Is estimated payout the same as profit?', 'answer' => 'No. This payout subtracts only the modeled COD cost. Product cost, shipping, marketplace fees, advertising, returns and other expenses may still apply.'],
                ['question' => 'Does the calculator include return-to-origin loss?', 'answer' => 'No. Use the Return & RTO Cost Calculator or your operating data to estimate failed-delivery and reverse-logistics cost separately.'],
                ['question' => 'Why does my settlement show a different COD charge?', 'answer' => 'Possible causes include a different fee base, minimum fee, rate slab, additional service line, tax treatment, rounding or an outdated rate. Compare each statement line.'],
                ['question' => 'How often should sellers review COD rates?', 'answer' => 'Review them whenever the provider changes its fee card and periodically reconcile assumptions against recent settlements.'],
            ],
            'references' => [
                ['name' => 'GST Portal', 'url' => 'https://www.gst.gov.in/'],
            ],
            'version_history' => [
                ['date' => '2026-10-30', 'note' => 'Reconciled the worked example with the COD Fee Calculator and documented payout exclusions and the tax-base assumption.'],
            ],
        ],
        'gst-inclusive-vs-gst-exclusive-explained' => [
            'manually_edited' => true,
            'append_generated_faqs' => false,
            'content_version' => '2.0',
            'summary' => [
                'A GST-exclusive price is the taxable value before GST; the tax is added to reach the final total.',
                'A GST-inclusive price is already the final total, so its taxable value must be calculated by division—not by subtracting the headline rate.',
                'At 18%, ₹1,000 exclusive becomes ₹1,180 total; ₹1,180 inclusive contains ₹1,000 taxable value and ₹180 GST.',
                'The calculator separates arithmetic only. Confirm the applicable rate, classification, place-of-supply treatment and invoice rounding from current official requirements.',
            ],
            'introduction' => [
                '“₹1,000 plus GST” and “₹1,000 including GST” do not mean the same thing. In the first quote, ₹1,000 is the taxable value and GST is added on top. In the second, ₹1,000 is already the customer total, so the tax component must be extracted from inside that amount. Using the wrong formula can overstate tax, understate the taxable value or make two supplier quotes look comparable when they are not.',
                'This guide uses the same two modes now available in Toolexa’s GST Calculator. It reconciles a single 18% example in both directions, explains why simply taking 18% of an inclusive total is wrong, and identifies what the calculator cannot decide. The arithmetic examples are educational; use the rate and tax treatment applicable to the actual supply and official invoice requirements.',
            ],
            'steps' => [
                'Read the quote or invoice label and decide whether the entered amount is before GST or already includes GST.',
                'For an exclusive price, choose “GST exclusive — add GST” and enter the taxable value.',
                'For an inclusive price, choose “GST inclusive — extract GST” and enter the final total paid or quoted.',
                'Enter the applicable GST rate and calculate the taxable value, GST amount and final total.',
                'Check the result against the invoice’s rate, rounding and applicable CGST/SGST or IGST presentation before official use.',
            ],
            'sections' => [
                [
                    'heading' => 'GST-exclusive Price: Add Tax to the Base',
                    'paragraphs' => [
                        'An exclusive amount is the taxable value before GST. GST amount equals taxable value multiplied by the GST rate divided by 100. Final total equals taxable value plus the GST amount.',
                        'For ₹1,000 at 18%, GST is ₹1,000 × 18 ÷ 100 = ₹180. The final total is ₹1,000 + ₹180 = ₹1,180. The customer pays more than the displayed base because the quote did not yet contain tax.',
                    ],
                ],
                [
                    'heading' => 'GST-inclusive Price: Extract Tax from the Total',
                    'paragraphs' => [
                        'An inclusive amount already contains both taxable value and GST. Taxable value equals inclusive total divided by 1 plus the GST rate expressed as a decimal. GST equals inclusive total minus that taxable value.',
                        'For ₹1,180 including 18% GST, taxable value is ₹1,180 ÷ 1.18 = ₹1,000. GST is ₹1,180 − ₹1,000 = ₹180. The final total stays ₹1,180 because no extra tax is added.',
                    ],
                ],
                [
                    'heading' => 'Why 18% of an Inclusive Total Is Wrong',
                    'paragraphs' => [
                        'Calculating 18% of ₹1,180 gives ₹212.40, but that treats ₹1,180 as a pre-tax base. It effectively calculates tax for a new exclusive transaction instead of finding the tax already contained in the total.',
                        'The direct extraction formula is inclusive total × rate ÷ (100 + rate). For ₹1,180 at 18%, that is ₹1,180 × 18 ÷ 118 = ₹180. This is mathematically equivalent to first dividing by 1.18 to find the taxable value.',
                    ],
                ],
                [
                    'heading' => 'Compare Supplier Quotes on the Same Basis',
                    'paragraphs' => [
                        'A ₹10,000 exclusive quote becomes ₹11,800 at 18%, while a ₹10,000 inclusive quote already ends at ₹10,000 and contains a lower taxable value. Comparing only the displayed amounts without reading the tax basis can lead to the wrong purchasing decision.',
                        'Convert every quote to either final payable total or pre-tax taxable value before comparing it. Also check delivery, discounts and other charges because an inclusive label does not prove that every invoice component is included.',
                    ],
                ],
                [
                    'heading' => 'What the Calculator Does Not Decide',
                    'paragraphs' => [
                        'The calculator performs the entered-rate arithmetic. It does not identify the correct GST classification or rate, decide place of supply, determine whether a supply is exempt, create a compliant tax invoice or calculate input-tax credit.',
                        'It also displays two-decimal results, while accounting systems can apply prescribed line-level or invoice-level rounding. For filing, legal interpretation or a material transaction, use current GST Portal and CBIC information and consult a qualified tax professional when necessary.',
                    ],
                ],
                [
                    'heading' => 'A Reliable Invoice-check Workflow',
                    'paragraphs' => [
                        'First identify whether each quoted line is inclusive or exclusive. Enter the amount and stated rate in the matching calculator mode. Compare the taxable value plus GST with the invoice total and investigate any difference rather than changing the rate to force a match.',
                        'Differences may come from discounts, multiple rate lines, cess, freight treatment or rounding. Keep the source invoice with the calculation. A matching arithmetic result confirms the formula, not the correctness of the underlying tax classification.',
                    ],
                ],
            ],
            'mistakes' => [
                'Adding GST again to a price that already includes GST.',
                'Taking the headline GST percentage directly from an inclusive total.',
                'Comparing one supplier’s exclusive quote with another supplier’s inclusive quote.',
                'Using a familiar GST rate without confirming the actual classification and current treatment.',
                'Assuming a matching calculation proves that the invoice is legally or tax-wise correct.',
                'Ignoring discounts, additional charges, multiple tax-rate lines or rounding differences.',
            ],
            'blocks' => [
                ['type' => 'important', 'label' => 'Choose the correct starting point', 'text' => 'Exclusive mode starts with taxable value. Inclusive mode starts with the final total that already contains GST.'],
                ['type' => 'practice', 'label' => 'Exclusive check', 'text' => '₹1,000 exclusive at 18% produces ₹180 GST and a ₹1,180 final total.'],
                ['type' => 'practice', 'label' => 'Inclusive check', 'text' => '₹1,180 inclusive at 18% contains ₹1,000 taxable value and ₹180 GST.'],
                ['type' => 'note', 'label' => 'Scope', 'text' => 'The calculator verifies arithmetic; it does not select the legally applicable GST rate or invoice treatment.'],
            ],
            'faqs' => [
                ['question' => 'What is the difference between GST inclusive and exclusive?', 'answer' => 'An exclusive price is before GST and tax is added to it. An inclusive price already contains GST within the displayed final total.'],
                ['question' => 'How do I add 18% GST to ₹1,000?', 'answer' => 'Multiply ₹1,000 by 18 ÷ 100 to get ₹180 GST, then add it to reach ₹1,180.'],
                ['question' => 'How much GST is included in ₹1,180 at 18%?', 'answer' => 'The taxable value is ₹1,180 ÷ 1.18 = ₹1,000, so the included GST is ₹180.'],
                ['question' => 'Why should I not calculate 18% of an inclusive total?', 'answer' => 'Because the inclusive total already contains tax. Taking 18% of the total treats it as a pre-tax base and overstates the embedded GST.'],
                ['question' => 'What is the direct formula to remove GST?', 'answer' => 'GST component equals inclusive total × GST rate ÷ (100 + GST rate). Taxable value is the total minus that component.'],
                ['question' => 'Can the Toolexa GST Calculator handle both price types?', 'answer' => 'Yes. Choose exclusive mode to add GST or inclusive mode to extract taxable value and GST from a final total.'],
                ['question' => 'Does the calculator decide whether CGST/SGST or IGST applies?', 'answer' => 'No. It separates the total GST amount only. The correct tax type depends on the transaction and applicable rules.'],
                ['question' => 'Can I use the result for GST filing?', 'answer' => 'Use it to check arithmetic, not as a substitute for compliant invoices, official records or professional tax advice.'],
            ],
            'references' => [
                ['name' => 'GST Portal', 'url' => 'https://www.gst.gov.in/'],
                ['name' => 'Central Board of Indirect Taxes and Customs — GST', 'url' => 'https://cbic-gst.gov.in/'],
            ],
            'version_history' => [
                ['date' => '2026-10-30', 'note' => 'Replaced generic examples with reconciled ₹1,000 exclusive and ₹1,180 inclusive calculations.'],
            ],
        ],
        'uuid-explained-what-it-is-and-why-developers-use-it' => [
            'manually_edited' => true,
            'append_generated_faqs' => false,
            'content_version' => '2.0',
            'summary' => [
                'A UUID is a 128-bit identifier; its common text form contains 32 hexadecimal digits in an 8-4-4-4-12 layout.',
                'UUIDv4 uses random or pseudorandom data. After its fixed version and variant bits, 122 bits remain random.',
                'UUIDs avoid dependence on a central sequential counter, but applications should still enforce uniqueness when duplicates would cause harm.',
                'A UUID is an identifier—not encryption, authentication, authorization or proof that a record exists.',
            ],
            'introduction' => [
                'A universally unique identifier (UUID) gives software a 128-bit identifier that can be created without asking one central database for the next number. That property is useful when mobile apps, services, import jobs or offline clients must create records independently and merge them later. The familiar text form looks like 550e8400-e29b-41d4-a716-446655440000: 32 hexadecimal digits displayed in five groups separated by hyphens.',
                'This guide follows RFC 9562, the current IETF UUID standard that replaced RFC 4122 in May 2024. It explains what the version and variant positions mean, why random UUIDv4 values have an extremely low—but not mathematically impossible—collision probability, when UUIDv7 can be useful, and why an identifier must never be treated as a password or access-control check. The examples also match Toolexa’s browser-based UUID tools.',
            ],
            'steps' => [
                'Decide whether the system needs identifiers that can be generated independently or whether a database sequence is sufficient.',
                'Choose a UUID version supported by the application and storage layer; use UUIDv4 for random identifiers or consider UUIDv7 when time ordering is a defined requirement.',
                'Generate UUIDs with a standards-compliant library or a cryptographically secure platform API—not a handwritten Math.random pattern.',
                'Store and compare one consistent representation, and enforce a unique constraint when duplicate records would be damaging.',
                'Validate format at input boundaries, but authorize access using authenticated identity and permissions rather than possession of a UUID.',
            ],
            'sections' => [
                [
                    'heading' => 'The 128-bit and 8-4-4-4-12 Structure',
                    'paragraphs' => [
                        'RFC 9562 defines a UUID as 16 octets, or 128 bits. The canonical text representation contains groups of 8, 4, 4, 4 and 12 hexadecimal characters. Hyphens make the value easier to read; they are not additional identifier bits.',
                        'In 550e8400-e29b-41d4-a716-446655440000, the first character of the third group is 4, identifying UUID version 4. The first character of the fourth group is a; its high bits match the RFC variant used by modern UUID versions. Case does not change the value, though lowercase is a common storage convention.',
                    ],
                ],
                [
                    'heading' => 'How UUIDv4 Is Generated',
                    'paragraphs' => [
                        'UUIDv4 begins with random or pseudorandom data, then sets the version and variant bits to their required values. Those structural fields leave 122 random bits. A compliant browser can generate UUIDv4 through crypto.randomUUID(), which uses a cryptographically secure random-number generator.',
                        'Toolexa’s UUID Generator and Batch Generator use the browser Crypto API and run locally. The generated value is not uploaded to Toolexa. For production application code, prefer the language or framework’s maintained UUID implementation and test the storage path as well as generation.',
                    ],
                ],
                [
                    'heading' => 'Collision Probability Is Tiny, Not a Guarantee',
                    'paragraphs' => [
                        'The 122 random bits in UUIDv4 create an enormous space, so accidental collision is negligible for ordinary workloads when generation is sound. “Universally unique” is an engineering property based on that space and generation method, not a mathematical promise that two values can never match.',
                        'If a duplicate would overwrite data, break an idempotency rule or assign an object to the wrong owner, add a database unique constraint and handle a rare conflict safely. The constraint protects the business invariant even if a generator, import or test fixture is defective.',
                    ],
                ],
                [
                    'heading' => 'UUID Versions Serve Different Purposes',
                    'paragraphs' => [
                        'UUIDv1 is time-based and can carry timestamp and node-related fields. UUIDv3 and UUIDv5 deterministically derive a value from a namespace and name, using different hash algorithms. UUIDv4 is random. RFC 9562 also defines UUIDv6 and UUIDv7 for time-ordered use cases and reserves UUIDv8 for custom formats.',
                        'Version choice affects privacy, ordering and reproducibility. Do not switch formats only because a newer number exists. Confirm library support, database indexing behavior, interoperability and whether exposing time order is acceptable for the system.',
                    ],
                ],
                [
                    'heading' => 'Database and API Use',
                    'paragraphs' => [
                        'UUIDs are useful for records created across services, public API resource identifiers, sync workflows, message IDs and test fixtures. They avoid exposing a simple record count in the way sequential public IDs can, but that does not make a UUID confidential.',
                        'Databases may offer a native UUID type or compact binary storage. Text storage is readable but larger. Whatever representation is selected, normalize input consistently, index the column appropriately and measure write performance for the chosen UUID version and database engine.',
                    ],
                ],
                [
                    'heading' => 'A UUID Is Not a Security Boundary',
                    'paragraphs' => [
                        'Knowing a UUID must not be enough to read, modify or delete a protected object. UUIDs can appear in logs, browser history, analytics, copied links or leaked datasets. Even a hard-to-guess UUID is only an identifier.',
                        'Authenticate the caller and check authorization for the requested object. Use purpose-built, expiring and revocable tokens for password resets, invitations or sensitive sharing. Do not put secrets or personal information into custom UUID fields.',
                    ],
                ],
                [
                    'heading' => 'Generate, Validate and Inspect with Toolexa',
                    'paragraphs' => [
                        'Use UUID Generator for up to 100 browser-generated UUIDv4 values or UUID Batch Generator for up to 1,000 newline-separated values. UUID Validator checks canonical structure, version and RFC variant placement; a valid format does not prove that the identifier exists in another system.',
                        'UUID Decoder & Inspector separates the fields and reports the version and variant. For UUIDv1 it can reconstruct the embedded timestamp. For UUIDv4 it cannot reveal an owner or creation time because those fields are random data rather than an encoded account record.',
                    ],
                ],
            ],
            'mistakes' => [
                'Treating a valid UUID as proof that a corresponding database record exists.',
                'Using a UUID in a URL as a replacement for authentication and object-level authorization.',
                'Generating production UUIDs with Math.random or another unsuitable random source.',
                'Assuming collision is impossible and omitting a uniqueness constraint where integrity matters.',
                'Mixing text, binary, uppercase and lowercase representations without a normalization rule.',
                'Choosing a time-based version without considering metadata exposure and database behavior.',
            ],
            'blocks' => [
                ['type' => 'important', 'label' => 'Current standard', 'text' => 'RFC 9562, published in May 2024, defines current UUID formats and obsoletes RFC 4122.'],
                ['type' => 'note', 'label' => 'Read a UUIDv4', 'text' => 'In 550e8400-e29b-41d4-a716-446655440000, the 4 marks version 4 and the a begins the RFC variant field.'],
                ['type' => 'practice', 'label' => 'Integrity check', 'text' => 'Use a database unique constraint when a duplicate identifier would damage data, even when UUIDv4 collision risk is negligible.'],
                ['type' => 'tip', 'label' => 'Security rule', 'text' => 'Authorize the user for the object; never grant access merely because the request contains a valid UUID.'],
            ],
            'faqs' => [
                ['question' => 'What does UUID stand for?', 'answer' => 'UUID stands for universally unique identifier. It is a 128-bit identifier defined by RFC 9562.'],
                ['question' => 'How many characters are in a UUID?', 'answer' => 'The canonical text form has 36 characters: 32 hexadecimal digits plus four hyphens in an 8-4-4-4-12 layout.'],
                ['question' => 'How can I identify a UUIDv4?', 'answer' => 'In canonical text form, the first character of the third group is 4. The fourth group also begins with a character matching the RFC variant, commonly 8, 9, a or b.'],
                ['question' => 'Can two UUIDv4 values collide?', 'answer' => 'Yes in theory, but with a sound generator the probability is negligible for typical systems. Enforce uniqueness when the business consequence of a duplicate is serious.'],
                ['question' => 'What is the difference between UUIDv4 and UUIDv7?', 'answer' => 'UUIDv4 is random, while UUIDv7 begins with a Unix-epoch timestamp and is designed for time-ordered use cases. Choose based on system requirements and library support.'],
                ['question' => 'Is a UUID a secure access token?', 'answer' => 'No. A UUID identifies something; it does not authenticate a user or grant permission. Use proper authorization and purpose-built tokens.'],
                ['question' => 'Does a UUIDv4 reveal when it was created?', 'answer' => 'No. UUIDv4 uses random data and does not encode a creation timestamp. Some time-based versions have different properties.'],
                ['question' => 'Does UUID validation prove the ID exists?', 'answer' => 'No. Validation confirms structure, version and variant placement only. Existence must be checked in the relevant application or database.'],
                ['question' => 'Are Toolexa-generated UUIDs uploaded?', 'answer' => 'No. UUID generation uses browser JavaScript and the browser Crypto API locally.'],
            ],
            'references' => [
                ['name' => 'RFC 9562 — Universally Unique IDentifiers', 'url' => 'https://www.rfc-editor.org/rfc/rfc9562.html'],
                ['name' => 'MDN — Crypto.randomUUID()', 'url' => 'https://developer.mozilla.org/en-US/docs/Web/API/Crypto/randomUUID'],
            ],
            'version_history' => [
                ['date' => '2026-10-30', 'note' => 'Updated from generic UUID guidance to RFC 9562 structure, version selection, collision handling and security boundaries.'],
            ],
        ],
        'how-inflation-affects-your-savings' => [
            'manually_edited' => true,
            'append_generated_faqs' => false,
            'content_version' => '2.0',
            'summary' => [
                'Inflation reduces purchasing power: the same rupee amount can buy less when prices rise.',
                'Compare a saving return with inflation using real return, not nominal return alone.',
                'Future-cost projections are scenarios, not predictions; category-specific costs may move differently from headline CPI.',
                'Emergency liquidity and long-term growth solve different jobs, so one account need not hold every goal.',
            ],
            'introduction' => [
                'Inflation affects savings through purchasing power. If a household keeps ₹1,00,000 unchanged while the prices of the goods and services it needs rise, the account balance is still ₹1,00,000 but it buys less. India’s Consumer Price Index tracks changes in retail prices for a selected household-consumption basket; it is broader than the price movement of one product and may not match an individual family’s exact spending pattern.',
                'Planning therefore needs two separate numbers: the nominal amount shown in an account and the real value after allowing for inflation. This guide works through an exact future-cost example, explains the real-return relationship, and shows why education, healthcare, rent or food may need different assumptions. Toolexa’s Inflation Calculator compounds one assumed annual rate to estimate a future cost. It does not forecast CPI, fetch current inflation data or recommend an investment.',
            ],
            'steps' => [
                'Write down the current cost of a specific goal in today’s rupees.',
                'Choose a documented inflation assumption appropriate to that goal rather than copying a random headline rate.',
                'Enter current cost, annual rate and years in the Inflation Calculator.',
                'Compare the projected future cost with the future value of the saving plan using consistent dates.',
                'Revisit both assumptions at least annually and whenever the goal, price data or contribution changes.',
            ],
            'sections' => [
                ['heading' => 'Purchasing Power, CPI and Personal Inflation', 'paragraphs' => ['CPI measures price change for a defined basket and is widely used as a macroeconomic inflation indicator. Personal inflation can differ because households spend different shares on rent, travel, food, education and healthcare.', 'A single tuition fee or grocery item is a price change, not the economy-wide inflation rate. It can still be useful for a personal goal when the same service and quality are compared consistently.']],
                ['heading' => 'Verified Future-Cost Example', 'paragraphs' => ['For a goal costing ₹5,00,000 today, a 6% annual assumption over 10 years gives ₹5,00,000 × 1.06^10 = ₹8,95,423.85. The projected increase is ₹3,95,423.85.', 'Multiplying 6% by 10 and adding 60% would produce ₹8,00,000, which understates this scenario because annual price increases compound on the prior year’s higher cost.']],
                ['heading' => 'Nominal Return and Real Return', 'paragraphs' => ['An exact real-return calculation is (1 + nominal return) ÷ (1 + inflation) − 1. If a deposit earns 7% while inflation is 6%, the real return is about 0.94%, not simply 1%. Taxes, fees and account rules can reduce the saver’s realised result further.', 'A negative real return does not automatically make an accessible emergency fund a mistake: liquidity and capital stability have value. It does mean that the account may not preserve purchasing power for a long-dated goal.']],
                ['heading' => 'Build a Reviewable Plan', 'paragraphs' => ['Separate short-term emergency money from long-term goals, record the rate used for each projection and run a lower and higher scenario. Change one assumption per run so the effect remains explainable.', 'The calculator cannot select a suitable product or risk level. For regulated products, taxation or personalised investment decisions, use current official information and a qualified adviser.']],
            ],
            'mistakes' => ['Treating one product’s price rise as the official inflation rate.', 'Using nominal account growth without checking purchasing power.', 'Multiplying an annual rate by years instead of compounding future cost.', 'Using one rate for every goal without reviewing category-specific prices.', 'Mixing today’s rupees and future rupees in the same comparison.', 'Presenting a projection as a guaranteed future price.'],
            'blocks' => [
                ['type' => 'practice', 'label' => 'Verified example', 'text' => '₹5,00,000 compounded at 6% for 10 years becomes an estimated ₹8,95,423.85.'],
                ['type' => 'important', 'label' => 'Calculator boundary', 'text' => 'The Inflation Calculator projects one assumed rate; it does not retrieve or predict official CPI.'],
                ['type' => 'tip', 'label' => 'Planning check', 'text' => 'Compare the goal’s future cost and the saving plan on the same future date.'],
            ],
            'faqs' => [
                ['question' => 'How does inflation reduce savings?', 'answer' => 'It raises the amount needed to buy the same basket, so an unchanged balance can lose purchasing power.'],
                ['question' => 'What does Toolexa’s Inflation Calculator calculate?', 'answer' => 'It compounds a current cost at one assumed annual rate for the entered number of years.'],
                ['question' => 'Does the calculator predict India’s CPI?', 'answer' => 'No. It is a scenario calculator and does not retrieve or forecast official CPI releases.'],
                ['question' => 'What is the exact real-return formula?', 'answer' => 'Real return = (1 + nominal return) ÷ (1 + inflation) − 1, using both rates as decimals.'],
                ['question' => 'Why not subtract inflation directly from return?', 'answer' => 'Subtraction is only an approximation. The ratio formula accounts for the interaction between the two rates.'],
                ['question' => 'Can personal inflation differ from CPI?', 'answer' => 'Yes. Household spending weights and category-specific price movements can differ from the official basket.'],
                ['question' => 'Should emergency money always beat inflation?', 'answer' => 'Liquidity and safety may be more important for emergency funds; evaluate long-term purchasing-power needs separately.'],
                ['question' => 'How often should I update an inflation projection?', 'answer' => 'Review it at least annually and when the goal cost, time horizon or source price data changes.'],
            ],
            'references' => [
                ['name' => 'MoSPI Consumer Price Index catalogue', 'url' => 'https://esankhyiki.mospi.gov.in/catalogue-main/catalogue?product=CPI'],
                ['name' => 'Reserve Bank of India', 'url' => 'https://www.rbi.org.in/'],
            ],
            'version_history' => [['date' => '2026-10-30', 'note' => 'Added a reconciled future-cost example, exact real-return formula and clear calculator boundaries.']],
        ],
        'how-to-calculate-inflation-rate-easily' => [
            'manually_edited' => true,
            'append_generated_faqs' => false,
            'content_version' => '2.0',
            'summary' => ['Inflation between two comparable index values is (new index − old index) ÷ old index × 100.', 'A single product’s price change is not the official CPI inflation rate.', 'The Toolexa Inflation Calculator projects future cost; use the Percentage Calculator for a two-value historical change.', 'Multi-year annualisation and forward cost projection answer different questions.'],
            'introduction' => ['An inflation rate expresses how a comparable price index changed between two periods. The calculation is simple, but the inputs determine whether the answer is meaningful. Official CPI uses a defined basket, weights, base and reference periods; comparing the price of one unrelated product in each year does not reproduce that measure.', 'This guide separates three tasks that are often mixed together: calculating a one-period percentage change, annualising a change across several years, and projecting a future cost at an assumed rate. Toolexa’s Inflation Calculator performs the third task only. For an old and new index or price, use the Percentage Calculator’s percentage-change mode and keep the source periods with the result.'],
            'steps' => ['Use two values for the same index, basket or item definition.', 'Record the old and new reference periods and whether values are revised or provisional.', 'Calculate (new − old) ÷ old × 100.', 'For a span longer than one year, decide whether total change or an annualised rate is required.', 'State the source and never label a personal item-price change as official CPI.'],
            'sections' => [
                ['heading' => 'One-Period Formula and Verified Example', 'paragraphs' => ['If an index rises from 125.0 to 132.5, the change is (132.5 − 125.0) ÷ 125.0 × 100 = 6.0%. The old value is the denominator.', 'Reversing the values answers a different question. A fall from 132.5 to 125.0 is about −5.66%, not −6%, because the baseline has changed.']],
                ['heading' => 'Price Change Is Not Automatically CPI', 'paragraphs' => ['A rent, fuel or grocery price can be tracked for a household, but it represents that item or basket only. Official CPI combines selected goods and services using defined weights.', 'Use the same specification, quantity, location and time basis where possible. Product substitution or quality change can make a simple price comparison misleading.']],
                ['heading' => 'Total Change, Annualised Rate and Projection', 'paragraphs' => ['Total percentage change describes the whole interval. An annualised compound rate for multiple years is (new ÷ old)^(1 ÷ years) − 1. Multiplying one observed annual rate by years is not compound annualisation.', 'Future-cost projection reverses the perspective: current cost × (1 + assumed rate)^years. It is a scenario, not a measured historical inflation rate.']],
                ['heading' => 'Use the Correct Toolexa Tool', 'paragraphs' => ['Use Percentage Calculator for old-to-new percentage change. Use CAGR Calculator only when you deliberately need a compound annualised rate across a multi-year interval.', 'Use Inflation Calculator to project what today’s cost could become under one annual assumption. It does not accept two index observations or fetch government data.']],
            ],
            'mistakes' => ['Using the new value as the denominator.', 'Calling one item’s price change the national inflation rate.', 'Comparing different product quantities or index series.', 'Multiplying an annual rate by years when compounding is required.', 'Using a future-cost projection as if it were measured historical data.', 'Omitting the source period and revision status.'],
            'blocks' => [['type' => 'practice', 'label' => 'Verified example', 'text' => 'An index move from 125.0 to 132.5 is a 6.0% increase.'], ['type' => 'important', 'label' => 'Tool distinction', 'text' => 'Use Percentage Calculator for two historical values; Inflation Calculator projects one assumed rate forward.']],
            'faqs' => [
                ['question' => 'What is the basic inflation-rate formula?', 'answer' => '(New index − old index) ÷ old index × 100.'],
                ['question' => 'Which value is the denominator?', 'answer' => 'Use the earlier or baseline value.'],
                ['question' => 'Can inflation be negative?', 'answer' => 'A negative comparable index change indicates falling prices for that measure and period.'],
                ['question' => 'Is one product’s price increase inflation?', 'answer' => 'It is that product’s price change, not automatically the official CPI inflation rate.'],
                ['question' => 'How do I annualise a multi-year change?', 'answer' => 'Use (new ÷ old)^(1 ÷ years) − 1, then multiply by 100.'],
                ['question' => 'Does Toolexa fetch current CPI?', 'answer' => 'No. Use the official MoSPI source for published CPI data.'],
                ['question' => 'Which tool compares old and new values?', 'answer' => 'Use Percentage Calculator’s percentage-change mode.'],
                ['question' => 'Which tool projects a future cost?', 'answer' => 'Use Inflation Calculator with a stated annual assumption.'],
            ],
            'references' => [['name' => 'MoSPI Consumer Price Index catalogue', 'url' => 'https://esankhyiki.mospi.gov.in/catalogue-main/catalogue?product=CPI']],
            'version_history' => [['date' => '2026-10-30', 'note' => 'Separated historical rate calculation, annualisation and future-cost projection with exact examples.']],
        ],
        'how-to-choose-the-right-color-palette' => [
            'manually_edited' => true,
            'append_generated_faqs' => false,
            'content_version' => '2.0',
            'summary' => ['Assign colors to roles before judging isolated swatches.', 'Test text/background pairs at their actual font size and weight.', 'WCAG contrast is a threshold, not a palette-generation rule or guarantee of overall accessibility.', 'Keep semantic states distinguishable without relying on color alone.'],
            'introduction' => ['A useful interface palette is a system of roles, not merely five attractive swatches. It needs dependable colors for page backgrounds, surfaces, body text, headings, borders, primary actions, focus indicators and semantic states. The same accent that looks balanced in a palette strip can make button text unreadable or overwhelm an entire page.', 'This guide starts from a real interface rather than abstract color theory. It explains how to choose an anchor, build neutrals, reserve accents, test states and verify text contrast. Under WCAG 2.1 Success Criterion 1.4.3, ordinary text needs at least 4.5:1 contrast and large-scale text at least 3:1, with defined exceptions. Do not round a failing ratio up. Toolexa’s palette generator helps explore colors, but it does not certify accessibility or replace testing in the final design.'],
            'steps' => ['List interface roles before selecting hex values.', 'Choose one anchor and a neutral scale for backgrounds and text.', 'Assign an accent sparingly to actions or emphasis.', 'Test every text/background and interactive-state pair in the real component.', 'Check focus, hover, disabled, error and success states without relying on color alone.', 'Document approved tokens and re-test after any color change.'],
            'sections' => [
                ['heading' => 'Start with Roles, Not a Color Count', 'paragraphs' => ['Define background, surface, primary text, muted text, border, action and status roles. One color may serve more than one role only when contrast and meaning remain clear.', 'A brand anchor does not need to become body text or fill every component. Neutrals often carry most of the interface while the anchor provides recognition.']],
                ['heading' => 'Contrast Is Pair-Specific', 'paragraphs' => ['A hex color has no accessibility ratio by itself; the ratio belongs to a foreground/background pair. The same gray may pass on white and fail on a tinted surface.', 'Test actual font size and weight. WCAG’s 3:1 large-text threshold does not apply merely because a heading feels visually important.']],
                ['heading' => 'Build Semantic and Interactive States', 'paragraphs' => ['Error, warning, success and information states need labels, icons or text in addition to hue. People with color-vision differences may not distinguish red and green reliably.', 'Hover and focus should remain visible against surrounding surfaces. A focus ring that disappears on one card color makes keyboard navigation harder even if body text passes.']],
                ['heading' => 'Example Interface Palette Workflow', 'paragraphs' => ['For a deep teal anchor, first choose near-white and dark neutral surfaces and establish readable body text. Then test teal as a button fill with white or dark text rather than assuming the brand pairing works.', 'Add one warm accent only where it has a defined job. Preview a real header, form, alert and disabled button; remove colors that have no role instead of expanding the palette.']],
            ],
            'mistakes' => ['Choosing swatches without assigning interface roles.', 'Testing contrast on white while the real component uses another background.', 'Rounding 4.49:1 up to 4.5:1.', 'Using color as the only error or success signal.', 'Applying the saturated accent to large areas until hierarchy disappears.', 'Ignoring hover, focus and disabled states.'],
            'blocks' => [['type' => 'important', 'label' => 'WCAG threshold', 'text' => 'Ordinary text needs at least 4.5:1 contrast and large-scale text at least 3:1 under SC 1.4.3, subject to its defined exceptions.'], ['type' => 'tip', 'label' => 'Test components', 'text' => 'Judge the palette on buttons, inputs, alerts and content—not only on a row of swatches.']],
            'faqs' => [
                ['question' => 'How many colors should an interface palette contain?', 'answer' => 'Use as many role-based tokens as needed, but keep distinct accent hues limited and purposeful.'],
                ['question' => 'What contrast does normal text need?', 'answer' => 'WCAG SC 1.4.3 specifies at least 4.5:1 for ordinary text, subject to defined exceptions.'],
                ['question' => 'What contrast does large text need?', 'answer' => 'Large-scale text requires at least 3:1 under SC 1.4.3; verify the standard’s size and weight definition.'],
                ['question' => 'Can I round a contrast ratio?', 'answer' => 'No. W3C guidance says 4.499:1 does not meet a 4.5:1 threshold.'],
                ['question' => 'Does a palette generator guarantee accessibility?', 'answer' => 'No. Test each real foreground/background pair and component state.'],
                ['question' => 'Should error and success rely only on red and green?', 'answer' => 'No. Add text, icons or another non-color cue.'],
                ['question' => 'Can I build a palette from a photo?', 'answer' => 'Yes, but sampled colors still need role assignment and contrast testing.'],
                ['question' => 'Why should I test focus states?', 'answer' => 'Keyboard users need a visible indicator on every surrounding surface.'],
            ],
            'references' => [['name' => 'W3C Understanding SC 1.4.3 Contrast (Minimum)', 'url' => 'https://www.w3.org/WAI/WCAG21/Understanding/contrast-minimum'], ['name' => 'W3C WCAG overview', 'url' => 'https://www.w3.org/WAI/standards-guidelines/wcag/']],
            'version_history' => [['date' => '2026-10-30', 'note' => 'Rebuilt the guide around interface roles, exact WCAG thresholds and interactive-state testing.']],
        ],
        'gold-loan-explained-benefits-risks-emi-calculation' => [
            'manually_edited' => true,
            'append_generated_faqs' => false,
            'content_version' => '2.0',
            'summary' => ['A gold loan is secured by pledged jewellery, so repayment failure can put the asset at risk.', 'Loan amount depends on eligible collateral value and the lender’s current LTV and valuation rules.', 'Toolexa’s Gold Loan Calculator models reducing-balance EMI only; it does not calculate LTV, appraisal value, fees or bullet repayment.', 'Compare total repayment and written terms, not interest rate alone.'],
            'introduction' => ['A gold loan exchanges temporary liquidity for a security interest in pledged gold jewellery. The lender values eligible gold, applies its lending and regulatory rules, and sanctions an amount that can be lower than the customer’s estimate of retail jewellery value. Purity, net gold weight and valuation method matter; stones, making charges and sentimental value do not become repayable collateral value merely because they increased the purchase price.', 'This guide focuses on the borrower decisions that can be checked before pledging an asset: appraisal record, sanctioned amount, annual interest rate, repayment structure, charges, due dates, release process and auction terms. Toolexa’s Gold Loan Calculator uses the standard monthly reducing-balance EMI formula. It does not appraise gold, calculate loan-to-value, support bullet repayment or retrieve lender rates.'],
            'steps' => ['Ask for the eligible net gold weight, purity and valuation basis in writing.', 'Record the sanctioned amount separately from the jewellery’s purchase bill or emotional value.', 'Confirm whether repayment is EMI, periodic interest with principal later, or another schedule.', 'For an EMI offer, enter amount, annual rate and tenure in the Gold Loan Calculator.', 'Add processing, appraisal, storage, penal and closure charges from the written offer.', 'Read default notice, auction and surplus-refund terms before accepting.'],
            'sections' => [
                ['heading' => 'Valuation and LTV Are Not the Same as Retail Price', 'paragraphs' => ['A lender considers eligible gold content under its policy rather than the original jewellery invoice. Ask how stones and other non-gold parts are excluded and retain the appraisal receipt.', 'LTV compares the outstanding loan with eligible collateral value. Current limits and application can depend on lender category, loan purpose and rules in force, so verify the lender’s written disclosure and current RBI requirements.']],
                ['heading' => 'Verified EMI Example', 'paragraphs' => ['For a ₹1,00,000 loan at 12% per year for 12 months, the monthly rate is 1%. The reducing-balance EMI is approximately ₹8,884.88.', 'Total of 12 EMIs is about ₹1,06,618.55 and modeled interest is about ₹6,618.55. Rounding by a lender can change the final instalment slightly, and fees are not included.']],
                ['heading' => 'Repayment Structures Need Separate Calculations', 'paragraphs' => ['An EMI repays principal and interest each month. A bullet structure can leave principal due near the end, while some products collect interest periodically. These cash flows are not interchangeable.', 'The Toolexa tool calculates EMI only. Do not use its result as the repayment figure for a bullet or interest-only offer; use the lender’s schedule and calculate the stated structure separately.']],
                ['heading' => 'Default and Auction Risk', 'paragraphs' => ['Because gold secures the loan, missed obligations can lead to enforcement and auction under the contract and applicable process. Read notice periods, overdue interest and auction terms before pledging irreplaceable jewellery.', 'RBI has highlighted irregular practices in valuation, monitoring and auction processes. Keep receipts, repayment records and lender communication, and raise discrepancies promptly through the lender’s grievance channel.']],
            ],
            'mistakes' => ['Assuming jewellery invoice value equals eligible collateral value.', 'Comparing only the annual rate while ignoring fees and repayment structure.', 'Using an EMI calculator for a bullet-repayment offer.', 'Borrowing the maximum available without checking monthly cash flow.', 'Ignoring penal charges, due dates and auction provisions.', 'Failing to retain appraisal, pledge and payment records.'],
            'blocks' => [['type' => 'practice', 'label' => 'Verified EMI', 'text' => '₹1,00,000 at 12% for 12 months gives an estimated EMI of ₹8,884.88 and total interest of ₹6,618.55 before fees.'], ['type' => 'important', 'label' => 'Asset risk', 'text' => 'Default can put pledged jewellery at risk; read notice and auction clauses before accepting the loan.']],
            'faqs' => [
                ['question' => 'What does the Gold Loan Calculator calculate?', 'answer' => 'It estimates reducing-balance monthly EMI, total payment and interest from amount, annual rate and tenure.'],
                ['question' => 'Does it calculate gold value or LTV?', 'answer' => 'No. It does not appraise gold or calculate a permissible loan amount.'],
                ['question' => 'Can it calculate bullet repayment?', 'answer' => 'No. Use the lender’s documented bullet or interest-payment schedule separately.'],
                ['question' => 'Are stones and making charges part of gold value?', 'answer' => 'Do not assume so. Ask the lender for eligible net gold weight, purity and valuation details.'],
                ['question' => 'Are processing charges included in EMI?', 'answer' => 'No. Add lender fees separately when comparing total borrowing cost.'],
                ['question' => 'What happens after missed payments?', 'answer' => 'Consequences depend on the contract and applicable process and can include enforcement against the pledged jewellery.'],
                ['question' => 'Why can lender EMI differ slightly?', 'answer' => 'Rounding, exact dates, rate convention, fees or a different repayment structure can change the schedule.'],
                ['question' => 'What records should I retain?', 'answer' => 'Keep appraisal, pledge receipt, sanction terms, repayment schedule, payment receipts and release documentation.'],
            ],
            'references' => [['name' => 'RBI notice on gold-loan practices', 'url' => 'https://rbi.org.in/scripts/NotificationUser.aspx?Id=12735'], ['name' => 'Reserve Bank of India', 'url' => 'https://www.rbi.org.in/']],
            'version_history' => [['date' => '2026-10-30', 'note' => 'Added a verified EMI example, calculator boundaries, valuation checks and default-risk guidance.']],
        ],
    ],
];

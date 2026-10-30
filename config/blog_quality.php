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
    ],
];

<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    public static function popularSlugs(): array
    {
        return [
            'gst-calculator',
            'emi-calculator',
            'sip-calculator',
            'fd-calculator',
            'ppf-calculator',
            'age-calculator',
            'qr-generator',
            'password-generator',
        ];
    }

    public static function tools(): array
    {
        $tools = [
            [
                'name' => 'GST Calculator',
                'slug' => 'gst-calculator',
                'view' => 'gst',
                'icon' => 'GST',
                'desc' => 'Calculate GST amount and final price instantly.',
                'category' => 'Finance',
                'seo_title' => 'GST Calculator India - Free GST Amount and Total Price Tool',
                'seo_description' => 'Calculate GST amount, final total price and tax value quickly with this free GST calculator for India.',
                'keywords' => 'gst calculator india, calculate gst online, gst amount calculator, gst rate calculator',
                'formula' => [
                    'title' => 'GST Formula',
                    'items' => [
                        'GST Amount = Amount x GST Rate / 100',
                        'Total Amount = Amount + GST Amount',
                    ],
                ],
                'how_to' => [
                    'Enter the base amount before GST.',
                    'Enter the GST percentage rate.',
                    'Click calculate to see GST amount and total amount.',
                ],
                'faq' => [
                    ['q' => 'What is a GST calculator?', 'a' => 'A GST calculator helps you find tax amount and final price from a base amount and GST rate.'],
                    ['q' => 'Can I use it for Indian GST rates?', 'a' => 'Yes, you can enter any Indian GST rate such as 5%, 12%, 18% or 28%.'],
                ],
                'related' => ['percentage-calculator', 'discount-calculator', 'simple-interest-calculator', 'emi-calculator'],
            ],
            [
                'name' => 'EMI Calculator',
                'slug' => 'emi-calculator',
                'view' => 'emi',
                'icon' => 'EMI',
                'desc' => 'Plan loan EMI, total payment, and interest.',
                'category' => 'Finance',
                'seo_title' => 'EMI Calculator India - Free Loan EMI Calculator',
                'seo_description' => 'Calculate monthly EMI, total interest and total payment for loans with this free EMI calculator.',
                'keywords' => 'emi calculator, loan emi calculator, emi calculator india, monthly emi calculator',
                'formula' => [
                    'title' => 'EMI Formula',
                    'items' => [
                        'EMI = P x R x (1 + R)^N / ((1 + R)^N - 1)',
                        'P = Loan amount, R = monthly interest rate, N = number of months',
                    ],
                ],
                'how_to' => [
                    'Enter loan amount.',
                    'Enter annual interest rate.',
                    'Enter loan tenure in months and calculate EMI.',
                ],
                'faq' => [
                    ['q' => 'What does EMI mean?', 'a' => 'EMI means Equated Monthly Installment, the fixed monthly payment for a loan.'],
                    ['q' => 'Does this show total interest?', 'a' => 'Yes, the calculator shows monthly EMI, total interest and total payment.'],
                ],
                'related' => ['simple-interest-calculator', 'gst-calculator', 'percentage-calculator', 'discount-calculator'],
            ],
            [
                'name' => 'Simple Interest Calculator',
                'slug' => 'simple-interest-calculator',
                'view' => 'interest',
                'icon' => 'SI',
                'desc' => 'Find simple interest and total amount quickly.',
                'category' => 'Finance',
                'seo_title' => 'Simple Interest Calculator - Free Interest Tool',
                'seo_description' => 'Calculate simple interest and total amount from principal, rate and time period.',
                'keywords' => 'simple interest calculator, interest calculator, calculate simple interest',
                'formula' => [
                    'title' => 'Simple Interest Formula',
                    'items' => [
                        'Simple Interest = Principal x Rate x Time / 100',
                        'Total Amount = Principal + Simple Interest',
                    ],
                ],
                'how_to' => [
                    'Enter principal amount.',
                    'Enter interest rate per year.',
                    'Enter time in years and calculate interest.',
                ],
                'faq' => [
                    ['q' => 'What is simple interest?', 'a' => 'Simple interest is calculated only on the original principal amount.'],
                    ['q' => 'Can I calculate total amount?', 'a' => 'Yes, the result includes both interest and total amount.'],
                ],
                'related' => ['emi-calculator', 'percentage-calculator', 'gst-calculator', 'discount-calculator'],
            ],
            [
                'name' => 'Discount Calculator',
                'slug' => 'discount-calculator',
                'view' => 'discount',
                'icon' => 'OFF',
                'desc' => 'Check sale price and savings before buying.',
                'category' => 'Shopping',
                'seo_title' => 'Discount Calculator - Sale Price and Savings Tool',
                'seo_description' => 'Calculate final sale price and savings after discount percentage using this free discount calculator.',
                'keywords' => 'discount calculator, sale price calculator, discount percentage calculator',
                'formula' => [
                    'title' => 'Discount Formula',
                    'items' => [
                        'Discount Amount = Original Price x Discount % / 100',
                        'Final Price = Original Price - Discount Amount',
                    ],
                ],
                'how_to' => [
                    'Enter original price.',
                    'Enter discount percentage.',
                    'Click calculate to see savings and final price.',
                ],
                'faq' => [
                    ['q' => 'What does discount calculator show?', 'a' => 'It shows the amount saved and the final price after discount.'],
                    ['q' => 'Can I use it for shopping offers?', 'a' => 'Yes, it is useful for sale, coupon and offer calculations.'],
                ],
                'related' => ['percentage-calculator', 'gst-calculator', 'emi-calculator', 'unit-converter'],
            ],
            [
                'name' => 'Percentage Calculator',
                'slug' => 'percentage-calculator',
                'view' => 'percentage',
                'icon' => '%',
                'desc' => 'Calculate percentage from any value and total.',
                'category' => 'Math',
                'seo_title' => 'Percentage Calculator - Free Percent Calculator Online',
                'seo_description' => 'Calculate what percentage a value is of a total number with this free percentage calculator.',
                'keywords' => 'percentage calculator, percent calculator, calculate percentage online',
                'formula' => [
                    'title' => 'Percentage Formula',
                    'items' => [
                        'Percentage = Value / Total x 100',
                    ],
                ],
                'how_to' => [
                    'Enter the value.',
                    'Enter the total number.',
                    'Click calculate to get the percentage.',
                ],
                'faq' => [
                    ['q' => 'How is percentage calculated?', 'a' => 'Percentage is calculated by dividing value by total and multiplying by 100.'],
                    ['q' => 'Can the value be zero?', 'a' => 'Yes, value can be zero, but total must be greater than zero.'],
                ],
                'related' => ['discount-calculator', 'gst-calculator', 'simple-interest-calculator', 'emi-calculator'],
            ],
            [
                'name' => 'Age Calculator',
                'slug' => 'age-calculator',
                'view' => 'age',
                'icon' => 'AGE',
                'desc' => 'Find exact age in years, months, and days.',
                'category' => 'Utility',
                'seo_title' => 'Age Calculator - Calculate Exact Age Online',
                'seo_description' => 'Calculate exact age in years, months and days from date of birth using this free age calculator.',
                'keywords' => 'age calculator, calculate age online, date of birth age calculator',
                'formula' => null,
                'how_to' => [
                    'Select your date of birth.',
                    'Click calculate age.',
                    'See your exact age in years, months and days.',
                ],
                'faq' => [
                    ['q' => 'Can I calculate age from date of birth?', 'a' => 'Yes, select date of birth and the tool calculates exact age.'],
                    ['q' => 'Does it show months and days?', 'a' => 'Yes, it shows years, months and days.'],
                ],
                'related' => ['unit-converter', 'password-generator', 'qr-generator', 'text-case-converter'],
            ],
            [
                'name' => 'Password Generator',
                'slug' => 'password-generator',
                'view' => 'password',
                'icon' => 'KEY',
                'desc' => 'Create secure random passwords for accounts.',
                'category' => 'Utility',
                'seo_title' => 'Password Generator - Create Strong Random Passwords',
                'seo_description' => 'Generate strong random passwords for accounts, apps and websites using this free password generator.',
                'keywords' => 'password generator, strong password generator, random password tool',
                'formula' => null,
                'how_to' => [
                    'Enter password length.',
                    'Click generate password.',
                    'Copy and use the generated password securely.',
                ],
                'faq' => [
                    ['q' => 'What length should I choose?', 'a' => 'A password length of 12 or more characters is usually stronger.'],
                    ['q' => 'Does it include symbols?', 'a' => 'Yes, generated passwords can include letters, numbers and symbols.'],
                ],
                'related' => ['qr-generator', 'text-case-converter', 'age-calculator', 'unit-converter'],
            ],
            [
                'name' => 'Unit Converter',
                'slug' => 'unit-converter',
                'view' => 'unit',
                'icon' => 'UNIT',
                'desc' => 'Convert distance and weight units in seconds.',
                'category' => 'Utility',
                'seo_title' => 'Unit Converter - Convert KM, Meter, KG and Gram',
                'seo_description' => 'Convert kilometer to meter, meter to kilometer, kilogram to gram and gram to kilogram.',
                'keywords' => 'unit converter, km to meter, kg to gram, meter to km converter',
                'formula' => [
                    'title' => 'Unit Conversion Formula',
                    'items' => [
                        '1 km = 1000 m',
                        '1 kg = 1000 g',
                    ],
                ],
                'how_to' => [
                    'Enter the value.',
                    'Select the conversion type.',
                    'Click convert to see the converted value.',
                ],
                'faq' => [
                    ['q' => 'Which units are supported?', 'a' => 'It supports kilometer, meter, kilogram and gram conversions.'],
                    ['q' => 'Can I enter decimal values?', 'a' => 'Yes, decimal values are supported.'],
                ],
                'related' => ['age-calculator', 'percentage-calculator', 'discount-calculator', 'text-case-converter'],
            ],
            [
                'name' => 'QR Code Generator',
                'slug' => 'qr-generator',
                'view' => 'qr',
                'icon' => 'QR',
                'desc' => 'Generate QR codes for text, links, and data.',
                'category' => 'Utility',
                'seo_title' => 'QR Code Generator - Create Free QR Codes Online',
                'seo_description' => 'Generate QR codes instantly for URLs, text and simple data with this free QR code generator.',
                'keywords' => 'qr code generator, free qr generator, create qr code online',
                'formula' => null,
                'how_to' => [
                    'Enter text or URL.',
                    'Click generate QR.',
                    'Scan the generated QR code with a phone camera.',
                ],
                'faq' => [
                    ['q' => 'Can I create QR for a link?', 'a' => 'Yes, paste any URL and generate a QR code.'],
                    ['q' => 'Can it generate QR for text?', 'a' => 'Yes, it can generate QR codes for plain text too.'],
                ],
                'related' => ['password-generator', 'text-case-converter', 'unit-converter', 'age-calculator'],
            ],
            [
                'name' => 'Text Case Converter',
                'slug' => 'text-case-converter',
                'view' => 'text',
                'icon' => 'Aa',
                'desc' => 'Convert text into uppercase, lowercase, and title case.',
                'category' => 'Text',
                'seo_title' => 'Text Case Converter - Uppercase, Lowercase and Title Case',
                'seo_description' => 'Convert text to uppercase, lowercase and title case instantly with this free text case converter.',
                'keywords' => 'text case converter, uppercase converter, lowercase converter, title case tool',
                'formula' => null,
                'how_to' => [
                    'Enter or paste your text.',
                    'Click convert text.',
                    'Copy the uppercase, lowercase or title case output.',
                ],
                'faq' => [
                    ['q' => 'Which cases are supported?', 'a' => 'The tool creates uppercase, lowercase and title case versions.'],
                    ['q' => 'Can I convert long text?', 'a' => 'Yes, you can convert text up to the configured input limit.'],
                ],
                'related' => ['qr-generator', 'password-generator', 'unit-converter', 'age-calculator'],
            ],
        ];

        return self::enrichTools(array_merge($tools, self::financeTools()));
    }

    public static function financeTools(): array
    {
        $loanFields = [
            ['name' => 'amount', 'label' => 'Loan Amount (₹)', 'type' => 'number', 'default' => 500000, 'step' => '0.01'],
            ['name' => 'rate', 'label' => 'Interest Rate (% per year)', 'type' => 'number', 'default' => 10, 'step' => '0.01'],
            ['name' => 'tenure_years', 'label' => 'Tenure (years)', 'type' => 'number', 'default' => 5, 'step' => '0.1'],
        ];

        return [
            self::financeTool('Compound Interest Calculator', 'compound-interest-calculator', 'CI', 'Calculate compound interest and maturity amount.', 'compound_interest', [
                ['name' => 'principal', 'label' => 'Principal Amount (₹)', 'type' => 'number', 'default' => 100000, 'step' => '0.01'],
                ['name' => 'rate', 'label' => 'Interest Rate (% per year)', 'type' => 'number', 'default' => 8, 'step' => '0.01'],
                ['name' => 'years', 'label' => 'Time (years)', 'type' => 'number', 'default' => 5, 'step' => '0.1'],
                ['name' => 'frequency', 'label' => 'Compounding Frequency', 'type' => 'select', 'default' => 4, 'options' => [1 => 'Yearly', 2 => 'Half Yearly', 4 => 'Quarterly', 12 => 'Monthly']],
            ], 'Compound Interest = P x (1 + r/n)^(n x t) - P'),
            self::financeTool('SIP Calculator', 'sip-calculator', 'SIP', 'Estimate SIP maturity value and wealth gain.', 'sip', [
                ['name' => 'monthly_investment', 'label' => 'Monthly Investment (₹)', 'type' => 'number', 'default' => 5000, 'step' => '0.01'],
                ['name' => 'rate', 'label' => 'Expected Return (% per year)', 'type' => 'number', 'default' => 12, 'step' => '0.01'],
                ['name' => 'years', 'label' => 'Investment Period (years)', 'type' => 'number', 'default' => 10, 'step' => '0.1'],
            ], 'SIP FV = P x [((1 + i)^n - 1) / i] x (1 + i)'),
            self::financeTool('FD Calculator', 'fd-calculator', 'FD', 'Calculate fixed deposit maturity amount.', 'fd', [
                ['name' => 'principal', 'label' => 'Deposit Amount (₹)', 'type' => 'number', 'default' => 100000, 'step' => '0.01'],
                ['name' => 'rate', 'label' => 'Interest Rate (% per year)', 'type' => 'number', 'default' => 7, 'step' => '0.01'],
                ['name' => 'years', 'label' => 'Tenure (years)', 'type' => 'number', 'default' => 5, 'step' => '0.1'],
                ['name' => 'frequency', 'label' => 'Compounding Frequency', 'type' => 'select', 'default' => 4, 'options' => [1 => 'Yearly', 4 => 'Quarterly', 12 => 'Monthly']],
            ], 'FD Maturity = P x (1 + r/n)^(n x t)'),
            self::financeTool('RD Calculator', 'rd-calculator', 'RD', 'Estimate recurring deposit maturity value.', 'rd', [
                ['name' => 'monthly_deposit', 'label' => 'Monthly Deposit (₹)', 'type' => 'number', 'default' => 5000, 'step' => '0.01'],
                ['name' => 'rate', 'label' => 'Interest Rate (% per year)', 'type' => 'number', 'default' => 7, 'step' => '0.01'],
                ['name' => 'years', 'label' => 'Tenure (years)', 'type' => 'number', 'default' => 5, 'step' => '0.1'],
            ], 'RD FV = Monthly Deposit x [((1 + i)^n - 1) / i]'),
            self::financeTool('Loan Calculator', 'loan-calculator', 'LOAN', 'Calculate loan EMI, interest and total payment.', 'loan', $loanFields, 'EMI = P x R x (1 + R)^N / ((1 + R)^N - 1)'),
            self::financeTool('Home Loan EMI Calculator', 'home-loan-emi-calculator', 'HOME', 'Calculate home loan EMI and total interest.', 'loan', $loanFields, 'Home Loan EMI uses the standard EMI formula.'),
            self::financeTool('Car Loan EMI Calculator', 'car-loan-emi-calculator', 'CAR', 'Calculate car loan EMI and repayment amount.', 'loan', $loanFields, 'Car Loan EMI uses the standard EMI formula.'),
            self::financeTool('Personal Loan EMI Calculator', 'personal-loan-emi-calculator', 'PL', 'Calculate personal loan EMI instantly.', 'loan', $loanFields, 'Personal Loan EMI uses the standard EMI formula.'),
            self::financeTool('Education Loan EMI Calculator', 'education-loan-emi-calculator', 'EDU', 'Calculate education loan EMI and total payment.', 'loan', $loanFields, 'Education Loan EMI uses the standard EMI formula.'),
            self::financeTool('Gold Loan Calculator', 'gold-loan-calculator', 'GOLD', 'Estimate gold loan EMI and repayment value.', 'loan', $loanFields, 'Gold Loan EMI uses the standard EMI formula.'),
            self::financeTool('PPF Calculator', 'ppf-calculator', 'PPF', 'Estimate PPF maturity value over time.', 'ppf', [
                ['name' => 'yearly_investment', 'label' => 'Yearly Investment (₹)', 'type' => 'number', 'default' => 150000, 'step' => '0.01'],
                ['name' => 'rate', 'label' => 'Interest Rate (% per year)', 'type' => 'number', 'default' => 7.1, 'step' => '0.01'],
                ['name' => 'years', 'label' => 'Investment Period (years)', 'type' => 'number', 'default' => 15, 'step' => '1'],
            ], 'PPF maturity is calculated with annual compounding.'),
            self::financeTool('EPF Calculator', 'epf-calculator', 'EPF', 'Estimate EPF corpus from monthly contribution.', 'epf', [
                ['name' => 'monthly_salary', 'label' => 'Monthly Basic Salary (₹)', 'type' => 'number', 'default' => 30000, 'step' => '0.01'],
                ['name' => 'employee_rate', 'label' => 'Employee Contribution (%)', 'type' => 'number', 'default' => 12, 'step' => '0.01'],
                ['name' => 'employer_rate', 'label' => 'Employer Contribution (%)', 'type' => 'number', 'default' => 3.67, 'step' => '0.01'],
                ['name' => 'rate', 'label' => 'EPF Interest Rate (% per year)', 'type' => 'number', 'default' => 8.25, 'step' => '0.01'],
                ['name' => 'years', 'label' => 'Time (years)', 'type' => 'number', 'default' => 20, 'step' => '1'],
            ], 'EPF corpus is estimated with monthly contribution and monthly compounding.'),
            self::financeTool('NPS Calculator', 'nps-calculator', 'NPS', 'Estimate NPS corpus, lump sum and annuity value.', 'nps', [
                ['name' => 'monthly_investment', 'label' => 'Monthly Investment (₹)', 'type' => 'number', 'default' => 5000, 'step' => '0.01'],
                ['name' => 'rate', 'label' => 'Expected Return (% per year)', 'type' => 'number', 'default' => 10, 'step' => '0.01'],
                ['name' => 'years', 'label' => 'Investment Period (years)', 'type' => 'number', 'default' => 25, 'step' => '1'],
                ['name' => 'annuity_percent', 'label' => 'Annuity Allocation (%)', 'type' => 'number', 'default' => 40, 'step' => '0.01'],
            ], 'NPS corpus is estimated using monthly compounding.'),
            self::financeTool('CAGR Calculator', 'cagr-calculator', 'CAGR', 'Calculate compound annual growth rate.', 'cagr', [
                ['name' => 'start_value', 'label' => 'Starting Value (₹)', 'type' => 'number', 'default' => 100000, 'step' => '0.01'],
                ['name' => 'end_value', 'label' => 'Ending Value (₹)', 'type' => 'number', 'default' => 200000, 'step' => '0.01'],
                ['name' => 'years', 'label' => 'Time (years)', 'type' => 'number', 'default' => 5, 'step' => '0.1'],
            ], 'CAGR = (Ending Value / Starting Value)^(1 / Years) - 1'),
            self::financeTool('Inflation Calculator', 'inflation-calculator', 'INF', 'Estimate future cost after inflation.', 'inflation', [
                ['name' => 'current_cost', 'label' => 'Current Cost (₹)', 'type' => 'number', 'default' => 100000, 'step' => '0.01'],
                ['name' => 'rate', 'label' => 'Inflation Rate (% per year)', 'type' => 'number', 'default' => 6, 'step' => '0.01'],
                ['name' => 'years', 'label' => 'Time (years)', 'type' => 'number', 'default' => 10, 'step' => '0.1'],
            ], 'Future Cost = Current Cost x (1 + Inflation Rate)^Years'),
        ];
    }

    private static function financeTool(string $name, string $slug, string $icon, string $desc, string $calculator, array $fields, string $formula): array
    {
        $related = ['emi-calculator', 'sip-calculator', 'fd-calculator', 'compound-interest-calculator'];

        return [
            'name' => $name,
            'slug' => $slug,
            'view' => 'finance-calculator',
            'icon' => $icon,
            'desc' => $desc,
            'category' => 'Finance',
            'calculator' => $calculator,
            'fields' => $fields,
            'seo_title' => $name.' - Free Online Finance Tool',
            'seo_description' => $desc.' Use this free online '.$name.' with instant results and mobile friendly design.',
            'keywords' => Str::lower($name).', finance calculator, online calculator, free calculator',
            'formula' => [
                'title' => $name.' Formula',
                'items' => [$formula],
            ],
            'how_to' => [
                'Enter the required finance values.',
                'Click calculate to get instant results.',
                'Review the result summary and share or copy the tool link.',
            ],
            'faq' => [
                ['q' => 'Is '.$name.' free to use?', 'a' => 'Yes, this tool is free and works directly in your browser.'],
                ['q' => 'Are results exact?', 'a' => 'Results are estimates based on the values entered and common finance formulas.'],
            ],
            'related' => array_values(array_filter($related, fn ($item) => $item !== $slug)),
        ];
    }

    private static function enrichTools(array $tools): array
    {
        $slugs = array_column($tools, 'slug');

        return array_map(function ($tool) use ($tools, $slugs) {
            $tool['introduction'] = $tool['introduction'] ?? self::introFor($tool);
            $tool['how_to'] = self::stepsFor($tool);
            $tool['features'] = $tool['features'] ?? self::featuresFor($tool);
            $tool['faq'] = self::faqFor($tool);
            $tool['related'] = self::relatedFor($tool, $tools, $slugs);

            if (! empty($tool['formula'])) {
                $tool['formula']['explanation'] = $tool['formula']['explanation'] ?? self::formulaExplanationFor($tool);
                $tool['formula']['example'] = $tool['formula']['example'] ?? self::formulaExampleFor($tool);
            }

            return $tool;
        }, $tools);
    }

    private static function introFor(array $tool): string
    {
        $name = $tool['name'];
        $desc = rtrim($tool['desc'], '.');
        $category = strtolower($tool['category']);
        $inputs = collect($tool['fields'] ?? [])->pluck('label')->map(fn ($label) => strtolower(str_replace(['(₹)', '(%)'], '', $label)))->take(4)->implode(', ');
        $inputSentence = $inputs
            ? "It is especially useful when you want to compare values such as {$inputs} before making a decision."
            : "It is especially useful when you need a quick answer without opening a spreadsheet or doing manual calculations.";
        $formulaSentence = ! empty($tool['formula'])
            ? 'The page also explains the formula so you can understand how the result is produced instead of only seeing a number.'
            : 'The tool focuses on practical output and keeps the interface simple so you can complete the task quickly.';

        return "{$name} is a free online {$category} tool designed to help you {$desc}. You should use this tool whenever you need a fast, clean and mobile-friendly way to complete the task with fewer manual steps. {$inputSentence} The calculator area is built for everyday use, so the fields are easy to scan, the result appears clearly, and the page works well on desktop, tablet and mobile screens. {$formulaSentence} It is helpful for students, professionals, shoppers, small business owners and anyone who wants a reliable estimate before planning the next step. No registration is required, and the page is structured with supporting information, FAQs and related tools so users can understand the result and continue to the next useful calculation.";
    }

    private static function stepsFor(array $tool): array
    {
        $steps = $tool['how_to'] ?? [];
        $defaults = [
            'Open the tool page and read the input labels carefully.',
            'Enter the required values in the form fields.',
            'Review the values once to avoid typing mistakes.',
            'Click the calculate or convert button.',
            'Check the result summary and use copy or share options if needed.',
        ];

        return array_slice(array_values(array_unique(array_merge($steps, $defaults))), 0, 6);
    }

    private static function featuresFor(array $tool): array
    {
        $features = [
            'Free to use with no registration required',
            'Instant results after submitting values',
            'Mobile friendly layout for all screen sizes',
            'Clean interface with easy-to-read results',
            'SEO-friendly help content and FAQs',
            'Copy and share options for quick access',
        ];

        if (! empty($tool['formula'])) {
            $features[] = 'Formula explanation included for better understanding';
        }

        if ($tool['category'] === 'Finance') {
            $features[] = 'Useful for planning, comparison and estimates';
        }

        return array_slice($features, 0, 8);
    }

    private static function faqFor(array $tool): array
    {
        $name = $tool['name'];
        $category = strtolower($tool['category']);
        $faq = $tool['faq'] ?? [];
        $extra = [
            ['q' => 'When should I use '.$name.'?', 'a' => 'Use '.$name.' when you need a quick '.$category.' result without manual calculation or a spreadsheet.'],
            ['q' => 'Is '.$name.' mobile friendly?', 'a' => 'Yes, the tool is fully responsive and works on desktop, tablet and mobile devices.'],
            ['q' => 'Do I need to create an account?', 'a' => 'No account or registration is required to use this tool.'],
            ['q' => 'Is my input data stored?', 'a' => 'No personal record is stored by this page; the result is generated from the values you enter.'],
            ['q' => 'Can I share this tool?', 'a' => 'Yes, you can use the copy or share button to send the tool link to someone else.'],
        ];

        return array_slice(array_merge($faq, $extra), 0, 5);
    }

    private static function relatedFor(array $tool, array $tools, array $slugs): array
    {
        $related = array_values(array_filter($tool['related'] ?? [], fn ($slug) => in_array($slug, $slugs, true) && $slug !== $tool['slug']));
        $sameCategory = collect($tools)
            ->filter(fn ($item) => $item['category'] === $tool['category'] && $item['slug'] !== $tool['slug'])
            ->pluck('slug')
            ->all();
        $fallback = collect($tools)
            ->filter(fn ($item) => $item['slug'] !== $tool['slug'])
            ->pluck('slug')
            ->all();

        return array_slice(array_values(array_unique(array_merge($related, $sameCategory, $fallback))), 0, 6);
    }

    private static function formulaExplanationFor(array $tool): string
    {
        return 'This formula uses the values entered in the form to calculate the main result shown by '.$tool['name'].'. The result is an estimate, so it is best used for planning, comparison and quick decision-making.';
    }

    private static function formulaExampleFor(array $tool): string
    {
        $name = $tool['name'];

        return "For example, if you enter realistic values in {$name}, the tool applies the formula step by step and displays the calculated result instantly. You can change the inputs and calculate again to compare different scenarios.";
    }

    public static function toolBySlug(string $slug): ?array
    {
        foreach (self::tools() as $tool) {
            if ($tool['slug'] === $slug) {
                return $tool;
            }
        }

        return null;
    }

    public static function toolsBySlugs(array $slugs): array
    {
        $tools = [];

        foreach ($slugs as $slug) {
            $tool = self::toolBySlug($slug);

            if ($tool) {
                $tools[] = $tool;
            }
        }

        return $tools;
    }

    public static function recentTools(int $limit = 4): array
    {
        return array_slice(array_reverse(self::tools()), 0, $limit);
    }

    public static function categories(): array
    {
        return collect(self::tools())
            ->pluck('category')
            ->unique()
            ->values()
            ->map(fn ($category) => [
                'name' => $category,
                'slug' => Str::slug($category),
                'count' => collect(self::tools())->where('category', $category)->count(),
            ])
            ->all();
    }

    public static function homepageCategories(): array
    {
        $counts = collect(self::categories())->keyBy('slug');

        return [
            ['name' => 'Finance', 'slug' => 'finance', 'icon' => 'FIN', 'count' => $counts['finance']['count'] ?? 0, 'status' => null],
            ['name' => 'Text Tools', 'slug' => 'text', 'icon' => 'TXT', 'count' => $counts['text']['count'] ?? 0, 'status' => null],
            ['name' => 'Utility', 'slug' => 'utility', 'icon' => 'UTL', 'count' => $counts['utility']['count'] ?? 0, 'status' => null],
            ['name' => 'Developer', 'slug' => 'developer', 'icon' => 'DEV', 'count' => $counts['developer']['count'] ?? 0, 'status' => 'Coming Soon'],
            ['name' => 'Image', 'slug' => 'image', 'icon' => 'IMG', 'count' => 0, 'status' => 'Coming Soon'],
            ['name' => 'PDF', 'slug' => 'pdf', 'icon' => 'PDF', 'count' => 0, 'status' => 'Coming Soon'],
        ];
    }

    public function index()
    {
        $tools = self::tools();
        $popularSlugs = self::popularSlugs();
        $popular = self::toolsBySlugs($popularSlugs);
        $homepageCategories = self::homepageCategories();
        $recentTools = self::recentTools(8);

        return view('home', compact('tools', 'popular', 'popularSlugs', 'homepageCategories', 'recentTools'));
    }
}

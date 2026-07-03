<?php

namespace App\Http\Controllers\Tools;

use App\Support\BlogRepository;
use App\Http\Controllers\Controller;
use App\Support\Discover\DiscoverFeature;
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
                'category' => 'Text Tools',
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
                'related' => ['word-counter', 'character-counter', 'remove-extra-spaces', 'qr-generator'],
            ],
        ];

        return self::enrichTools(array_merge($tools, self::textTools(), self::developerTools(), self::utilityTools(), self::imageTools(), self::pdfTools(), self::sellerTools(), self::financeTools()));
    }

    public static function textTools(): array
    {
        return [
            self::textTool(
                'Word Counter',
                'word-counter',
                'WC',
                'Count words, characters, sentences, paragraphs, reading time and speaking time.',
                'Word Counter is a free online text analysis tool for checking the size and readability of any draft. Paste an article, assignment, product description, caption, email, script or note, and the tool instantly shows word count, total characters, characters without spaces, sentence count, paragraph count, estimated reading time and estimated speaking time. It is useful for writers, students, editors, marketers, bloggers and anyone preparing content for platforms with length limits. The result is shown in a clean summary so you can quickly decide whether your text is too short, too long or ready to publish. Reading time is estimated from a practical average reading speed, while speaking time helps when preparing scripts, presentations, reels, videos or podcasts. The tool works directly in your browser, does not require registration and keeps the workflow simple on mobile and desktop. You can copy the result summary or share it when you need to report content length to a client, teacher, teammate or editor.',
                [
                    'Word count with Unicode-friendly matching',
                    'Total character count',
                    'Characters without spaces',
                    'Sentence and paragraph counts',
                    'Reading time estimate',
                    'Speaking time estimate',
                    'Copy result summary',
                ],
                [
                    'Paste or type your text in the input box.',
                    'Click the Count Text button.',
                    'Review word, character, sentence and paragraph counts.',
                    'Check estimated reading and speaking time.',
                    'Copy or share the result summary if needed.',
                ],
                [
                    ['q' => 'How does the word counter calculate reading time?', 'a' => 'Reading time is estimated using an average reading speed of about 200 words per minute.'],
                    ['q' => 'Does Word Counter count paragraphs?', 'a' => 'Yes, paragraphs are counted from blocks of text separated by blank lines.'],
                    ['q' => 'Can I use it for social media captions?', 'a' => 'Yes, it is useful for captions, posts, descriptions and any short or long text.'],
                    ['q' => 'What is characters without spaces?', 'a' => 'It is the character count after removing spaces, tabs and line breaks.'],
                    ['q' => 'Can I copy the result?', 'a' => 'Yes, use the Copy Result button after generating the text statistics.'],
                ]
            ),
            self::textTool(
                'Character Counter',
                'character-counter',
                'CHR',
                'Count total characters, letters, digits, spaces and special characters.',
                'Character Counter is a simple online text utility for understanding exactly what your text contains. It counts total characters, characters without spaces, letters, digits, spaces and special characters in one clean result panel. This is helpful when writing titles, meta descriptions, SMS messages, form entries, usernames, bios, ad copy, database fields or content for platforms with strict length requirements. Instead of manually checking every symbol, paste the text and let the tool separate the details for you. The letters and digits counts make it easier to inspect mixed content such as codes, coupon text, IDs, file names and short technical strings. The spaces and special character counts help when formatting problems or hidden punctuation may affect how text is accepted by another website or app. The tool runs quickly, works on mobile and desktop, and keeps the result easy to copy. It is designed for everyday writing, editing, validation and cleanup tasks where exact character information matters.',
                [
                    'Total character count',
                    'Characters without spaces',
                    'Letter count',
                    'Digit count',
                    'Space count',
                    'Special character count',
                    'Copy result summary',
                ],
                [
                    'Enter or paste text in the box.',
                    'Click Count Text.',
                    'Check total characters and characters without spaces.',
                    'Review letters, digits, spaces and special characters.',
                    'Copy the result summary if you need to save it.',
                ],
                [
                    ['q' => 'What does total characters include?', 'a' => 'Total characters include letters, numbers, spaces, punctuation and line breaks.'],
                    ['q' => 'Are spaces counted separately?', 'a' => 'Yes, spaces and other whitespace characters are counted in the Spaces result.'],
                    ['q' => 'Does it count numbers as digits?', 'a' => 'Yes, numeric characters are counted under Digits.'],
                    ['q' => 'What are special characters?', 'a' => 'Special characters include punctuation, symbols and characters that are not letters, digits or spaces.'],
                    ['q' => 'Can I use it for SEO titles and descriptions?', 'a' => 'Yes, it is useful for checking length before publishing SEO titles, descriptions and snippets.'],
                ]
            ),
            self::textTool(
                'Remove Duplicate Lines',
                'remove-duplicate-lines',
                'DUP',
                'Remove duplicate lines while keeping the original order.',
                'Remove Duplicate Lines is a practical cleanup tool for turning messy lists into clean, unique lists without losing the original order. Paste keywords, URLs, names, emails, product codes, notes or any line-based content, and the tool removes repeated lines while keeping the first occurrence exactly where it appeared. This is useful for SEO keyword lists, spreadsheet exports, contact cleanup, inventory notes, redirects, sitemap checks, research lists and development notes. You can choose case-sensitive matching when uppercase and lowercase versions should be treated as different entries. When the option is off, lines such as Apple and apple are treated as duplicates, which is often better for human-readable lists. The output is easy to copy and reuse in documents, spreadsheets, CMS fields or code editors. The tool avoids unnecessary formatting changes so your list remains predictable. It works fully in the browser and helps reduce manual cleanup work when duplicate rows keep sneaking into your text.',
                [
                    'Remove repeated lines',
                    'Case-sensitive matching option',
                    'Keep original order',
                    'Show original, unique and removed line counts',
                    'Copy cleaned output',
                ],
                [
                    'Paste your line-based list into the text area.',
                    'Enable case-sensitive matching if uppercase and lowercase lines should be different.',
                    'Click Process Text.',
                    'Review the unique output and removal summary.',
                    'Copy the cleaned text for reuse.',
                ],
                [
                    ['q' => 'Does this tool keep the first duplicate line?', 'a' => 'Yes, the first occurrence is kept and later duplicate lines are removed.'],
                    ['q' => 'Will the original order change?', 'a' => 'No, unique lines stay in the same order as the original input.'],
                    ['q' => 'What does case-sensitive mean?', 'a' => 'Case-sensitive matching treats Text and text as different lines.'],
                    ['q' => 'Can I clean URL or keyword lists?', 'a' => 'Yes, it works well for URLs, keywords, names, emails and other line-based lists.'],
                    ['q' => 'Does it remove blank lines?', 'a' => 'Blank lines are treated like normal lines, so repeated blank lines are reduced to one blank line.'],
                ]
            ),
            self::textTool(
                'Remove Extra Spaces',
                'remove-extra-spaces',
                'SPC',
                'Remove multiple spaces, trim lines and optionally remove empty lines.',
                'Remove Extra Spaces helps clean text that has uneven spacing, copied formatting or accidental blank areas. Paste your content and the tool trims leading and trailing spaces from each line, converts multiple spaces or tabs into a single space, and can optionally remove empty lines. It is useful after copying text from PDFs, websites, spreadsheets, chat apps, OCR output, email threads or documents where formatting often becomes inconsistent. Clean spacing makes text easier to read, paste into forms, publish in articles, use in descriptions or process with other tools. The optional empty-line removal is helpful when preparing compact lists, product descriptions, CSV-like content or notes that should not contain large gaps. The output keeps line breaks unless you choose to remove empty lines, so the structure remains familiar. This browser-based tool is quick, mobile-friendly and avoids changing the actual words in your text. Copy the cleaned result and continue working without slow manual find-and-replace steps.',
                [
                    'Remove repeated spaces and tabs',
                    'Trim leading spaces',
                    'Trim trailing spaces',
                    'Optional empty line removal',
                    'Show before and after character counts',
                    'Copy cleaned output',
                ],
                [
                    'Paste text with extra spaces into the input box.',
                    'Select Remove empty lines if you want compact output.',
                    'Click Process Text.',
                    'Review the cleaned text and summary.',
                    'Copy the output for your document, form or editor.',
                ],
                [
                    ['q' => 'Will this tool remove all spaces?', 'a' => 'No, it keeps normal single spaces between words and removes only extra spacing.'],
                    ['q' => 'Can it remove empty lines?', 'a' => 'Yes, enable the Remove empty lines option before processing.'],
                    ['q' => 'Does it trim each line?', 'a' => 'Yes, leading and trailing spaces are removed from every line.'],
                    ['q' => 'Is punctuation changed?', 'a' => 'No, punctuation and words are preserved while spacing is cleaned.'],
                    ['q' => 'Can I use it after copying from a PDF?', 'a' => 'Yes, it is useful for cleaning spacing problems from PDFs, websites and documents.'],
                ]
            ),
            self::textTool(
                'Text Repeater',
                'text-repeater',
                'REP',
                'Repeat text multiple times with space, new line, comma or custom separators.',
                'Text Repeater is a flexible online tool for repeating any word, phrase, sentence or block of text a selected number of times. Enter your text, choose how many repetitions you need, and pick a separator such as space, new line, comma or a custom separator. It is useful for creating test data, placeholder content, repeated labels, social captions, formatting samples, classroom examples, pattern checks, simple lists and quick copy-paste snippets. The custom separator option gives more control when you need output joined by symbols, words, HTML fragments or other short separators. The tool shows output character count so you can understand how large the repeated text becomes before copying it. A sensible maximum repeat count keeps the page responsive while still supporting practical everyday uses. The interface works well on mobile and desktop, and the final text can be copied instantly. It is a small but useful utility when repeating text by hand would be slow, boring or error-prone.',
                [
                    'Repeat text N times',
                    'Space separator',
                    'New line separator',
                    'Comma separator',
                    'Custom separator',
                    'Output character count',
                    'Copy repeated output',
                ],
                [
                    'Enter the text you want to repeat.',
                    'Choose the repeat count.',
                    'Select Space, New Line, Comma or Custom separator.',
                    'Enter a custom separator if that option is selected.',
                    'Click Process Text and copy the output.',
                ],
                [
                    ['q' => 'What is the maximum repeat count?', 'a' => 'The tool allows up to 500 repetitions to keep output practical and responsive.'],
                    ['q' => 'Can I repeat multiple lines?', 'a' => 'Yes, you can paste multi-line text and repeat it with your selected separator.'],
                    ['q' => 'What does custom separator do?', 'a' => 'It joins each repeated copy using the custom text or symbol you enter.'],
                    ['q' => 'Can I create one item per line?', 'a' => 'Yes, choose the New Line separator for line-by-line output.'],
                    ['q' => 'Can I copy the repeated text?', 'a' => 'Yes, use the Copy Result button after generating the output.'],
                ]
            ),
            self::textTool(
                'Lorem Ipsum Generator',
                'lorem-ipsum-generator',
                'LOR',
                'Generate lorem ipsum paragraphs, sentences or words with custom quantity.',
                'Lorem Ipsum Generator is a fast text utility for creating placeholder copy when a design, layout, page section or content block needs realistic text volume before final writing is ready. Choose whether you want paragraphs, sentences or words, enter the quantity, and generate clean lorem ipsum output that can be copied into mockups, websites, presentations, CMS fields, forms or test pages. It is useful for designers, developers, writers, students and marketers who need draft text that looks natural enough to test spacing, line breaks and page balance. The generated text uses familiar lorem ipsum wording, so it is easy to recognize as temporary content and less likely to be mistaken for final copy. Custom quantity controls help you create a short label, a few sentences or multiple paragraphs without repeating manual copy-paste work. The tool runs directly in the browser, keeps the interface simple and works well on mobile and desktop. Copy the generated output and continue building your layout quickly.',
                [
                    'Generate paragraphs',
                    'Generate sentences',
                    'Generate words',
                    'Custom quantity control',
                    'Output character count',
                    'Copy generated output',
                ],
                [
                    'Choose whether to generate paragraphs, sentences or words.',
                    'Enter the quantity you need.',
                    'Click Generate Text.',
                    'Review the generated placeholder text.',
                    'Copy or share the output if needed.',
                ],
                [
                    ['q' => 'What is lorem ipsum used for?', 'a' => 'Lorem ipsum is placeholder text used to test layouts before final content is ready.'],
                    ['q' => 'Can I choose paragraphs or words?', 'a' => 'Yes, you can generate paragraphs, sentences or words.'],
                    ['q' => 'What is the maximum quantity?', 'a' => 'You can generate up to 100 units at a time.'],
                    ['q' => 'Is the generated text final content?', 'a' => 'No, it is temporary placeholder copy for testing layout and spacing.'],
                    ['q' => 'Can I copy the generated lorem ipsum?', 'a' => 'Yes, use the Copy Result button after generating the output.'],
                ]
            ),
        ];
    }

    public static function developerTools(): array
    {
        return [
            self::textTool(
                'Base64 Encoder',
                'base64-encoder',
                'B64',
                'Encode plain text to Base64 instantly.',
                'Base64 Encoder is a free developer tool for converting plain text into a Base64 encoded string. Paste any text, token, small JSON snippet, configuration value or test message, and the tool produces Base64 output that can be copied immediately. Base64 is commonly used when text needs to be represented safely in environments that expect ASCII characters, such as APIs, headers, basic test payloads, data URLs, configuration examples and debugging workflows. This tool is designed for quick everyday use rather than heavy file encoding, so the interface stays lightweight and easy to scan. It is helpful for developers, QA testers, students and technical writers who need to encode small text values without opening a terminal or writing a script. The result panel shows input and output length so you can quickly inspect the conversion size. No registration is required, the page works on mobile and desktop, and the copy button makes it simple to move the encoded output into your editor, API client or documentation.',
                [
                    'Encode plain text to Base64',
                    'Instant conversion workflow',
                    'Input and output character summary',
                    'Copy output',
                    'Clear button',
                    'Mobile friendly interface',
                ],
                [
                    'Paste plain text into the input box.',
                    'Click Convert Instantly.',
                    'Review the Base64 output.',
                    'Copy the encoded value if needed.',
                    'Use Clear to reset the form.',
                ],
                [
                    ['q' => 'What does Base64 Encoder do?', 'a' => 'It converts normal plain text into a Base64 encoded string.'],
                    ['q' => 'Can I encode JSON text?', 'a' => 'Yes, you can encode small JSON snippets and other plain text values.'],
                    ['q' => 'Is Base64 encryption?', 'a' => 'No, Base64 is encoding, not encryption, so it should not be used to secure secrets.'],
                    ['q' => 'Can I copy the encoded output?', 'a' => 'Yes, use the Copy Result button after conversion.'],
                    ['q' => 'Does this tool upload my text?', 'a' => 'The tool is designed for direct browser use and does not require account registration.'],
                ],
                'Developer Tools'
            ),
            self::textTool(
                'Base64 Decoder',
                'base64-decoder',
                'B64',
                'Decode Base64 strings back to plain text.',
                'Base64 Decoder is a practical developer utility for converting Base64 encoded text back into readable plain text. Paste an encoded value from an API response, header, sample payload, configuration field, documentation example or test case, and the tool decodes it into a clean output area. It is useful when debugging integrations, checking encoded snippets, reviewing examples or learning how Base64 transforms text. The page validates the input and shows a clear message if the value is not valid Base64, which helps avoid confusing output. A small summary shows input and decoded character counts so you can quickly compare the conversion. This tool is intended for text values rather than large binary files, keeping the workflow simple and responsive. Developers, testers, students and support teams can use it whenever they need a quick decode without terminal commands. The output is easy to copy, the clear button resets the form, and the responsive layout works comfortably on mobile and desktop screens.',
                [
                    'Decode Base64 to plain text',
                    'Validation for invalid Base64 input',
                    'Input and output character summary',
                    'Copy output',
                    'Clear button',
                    'Responsive developer workflow',
                ],
                [
                    'Paste a Base64 string into the input box.',
                    'Click Convert Instantly.',
                    'Review the decoded plain text.',
                    'Copy the decoded output if needed.',
                    'Use Clear before decoding another value.',
                ],
                [
                    ['q' => 'What does Base64 Decoder do?', 'a' => 'It converts a valid Base64 encoded string back into plain text.'],
                    ['q' => 'What happens with invalid Base64?', 'a' => 'The tool shows a validation error asking for a valid Base64 string.'],
                    ['q' => 'Can Base64 decode passwords securely?', 'a' => 'Base64 is not secure encryption, so do not treat decoded values as protected.'],
                    ['q' => 'Can I copy decoded text?', 'a' => 'Yes, use the Copy Result button after decoding.'],
                    ['q' => 'Is this suitable for large files?', 'a' => 'This page is intended for text snippets, not large binary file decoding.'],
                ],
                'Developer Tools'
            ),
            self::textTool(
                'URL Encoder & Decoder',
                'url-encoder-decoder',
                'URL',
                'Encode or decode URL text for safe links and query strings.',
                'URL Encoder & Decoder is a free developer tool for preparing URL text and reading encoded URL values. Use encode mode when a query parameter, redirect URL, search term, path value or special character needs to be safely placed inside a link. Use decode mode when you want to turn encoded sequences such as %20 back into readable text. This is useful while working with APIs, tracking links, redirects, form submissions, analytics URLs, campaign parameters and debugging browser requests. The tool keeps both operations in one page so you can switch between encoding and decoding without opening separate utilities. Output appears in a clear copyable field with a small summary of operation type and character counts. Developers, marketers, QA testers and students can use it to quickly understand or prepare URL strings. The interface follows Toolexa’s responsive tool layout, includes copy and clear controls, and avoids unnecessary distractions so you can finish the conversion and continue with your task.',
                [
                    'Encode URL text',
                    'Decode URL text',
                    'Operation selector',
                    'Input and output character summary',
                    'Copy output',
                    'Clear button',
                ],
                [
                    'Paste the URL text or encoded value.',
                    'Choose Encode URL or Decode URL.',
                    'Click Convert Instantly.',
                    'Review the converted output.',
                    'Copy the result or clear the form.',
                ],
                [
                    ['q' => 'When should I encode a URL?', 'a' => 'Encode text when special characters need to be safely included in a URL or query string.'],
                    ['q' => 'What does URL decode mean?', 'a' => 'URL decode converts encoded sequences such as %20 into readable characters.'],
                    ['q' => 'Can I decode full links?', 'a' => 'Yes, you can paste full URLs or individual URL components.'],
                    ['q' => 'Does this tool change the original input?', 'a' => 'No, it creates a separate output that you can copy.'],
                    ['q' => 'Can I switch between encode and decode?', 'a' => 'Yes, choose the operation from the dropdown before converting.'],
                ],
                'Developer Tools'
            ),
            self::textTool(
                'MD5 Hash Generator',
                'md5-hash-generator',
                'MD5',
                'Generate an MD5 hash from any text input.',
                'MD5 Hash Generator is a quick developer utility for generating the 32-character MD5 hash of a text value. Enter a word, phrase, test payload, sample identifier or configuration string, and the tool returns the MD5 digest in a copyable output field. MD5 is commonly seen in legacy checksums, examples, database comparisons, cache keys, demos and non-security integrity checks. This page is useful when you need a fast hash for testing or comparison without using a command line. It also shows input character count and hash length so the result is easy to inspect. MD5 is not recommended for password storage or modern cryptographic security, and the FAQ calls that out clearly so users understand the right context. The tool is built for small text snippets, loads quickly, works on mobile and desktop, and includes copy and clear buttons for repeated use. It fits common developer workflows where a quick deterministic text hash is needed for debugging or documentation.',
                [
                    'Generate MD5 hash from text',
                    'Instant generation workflow',
                    '32-character hash output',
                    'Copy hash',
                    'Clear button',
                    'Input character summary',
                ],
                [
                    'Enter the text you want to hash.',
                    'Click Convert Instantly.',
                    'Review the generated MD5 hash.',
                    'Copy the hash if needed.',
                    'Use Clear to reset the form.',
                ],
                [
                    ['q' => 'What is an MD5 hash?', 'a' => 'MD5 is a deterministic 32-character hash generated from input text.'],
                    ['q' => 'Is MD5 safe for passwords?', 'a' => 'No, MD5 is not recommended for password storage or modern security.'],
                    ['q' => 'Will the same text create the same hash?', 'a' => 'Yes, the same input text produces the same MD5 hash.'],
                    ['q' => 'Can I copy the hash?', 'a' => 'Yes, use the Copy Result button after generating it.'],
                    ['q' => 'What can I use MD5 for?', 'a' => 'It is useful for legacy checksums, examples, comparisons and non-security testing.'],
                ],
                'Developer Tools'
            ),
            self::browserTool(
                'UUID Generator',
                'uuid-generator',
                'UID',
                'Generate version 4 UUIDs locally in your browser.',
                'UUID Generator is a browser-based developer tool for creating random version 4 UUIDs without sending data to a server. Choose how many UUIDs you need, from one to one hundred, and generate a clean list instantly. UUIDs are useful for test records, mock API payloads, database seed data, sample identifiers, temporary keys, documentation examples and development workflows where globally unique-looking values are needed. The generated values follow the standard UUID v4 format and are produced locally with browser crypto support when available. You can copy an individual UUID, copy the full list, or download the generated results as a TXT file for later use. The tool is designed for quick utility work, not account management or secret generation, so the interface stays simple and focused. Since the processing happens in your browser, Toolexa does not store generated UUIDs or user input. It works on desktop and mobile and fits the same clean Toolexa layout as the rest of the tools.',
                [
                    'Generate version 4 UUIDs',
                    'Generate 1 to 100 UUIDs',
                    'Copy individual UUID',
                    'Copy all UUIDs',
                    'Download as TXT',
                    'Local browser processing',
                ],
                [
                    'Enter how many UUIDs you want to generate.',
                    'Click Generate UUIDs.',
                    'Review the generated UUID v4 list.',
                    'Copy one UUID, copy all UUIDs or download a TXT file.',
                    'Use Clear to reset the result.',
                ],
                [
                    ['q' => 'What UUID version does this tool generate?', 'a' => 'It generates version 4 UUIDs.'],
                    ['q' => 'How many UUIDs can I generate?', 'a' => 'You can generate between 1 and 100 UUIDs at a time.'],
                    ['q' => 'Are UUIDs stored on the server?', 'a' => 'No, UUID generation runs locally in your browser.'],
                    ['q' => 'Can I copy a single UUID?', 'a' => 'Yes, every generated UUID has its own copy button.'],
                    ['q' => 'Can I download the UUID list?', 'a' => 'Yes, use the Download TXT button after generation.'],
                ],
                'Developer Tools'
            ),
            self::browserTool(
                'Random String Generator',
                'random-string-generator',
                'STR',
                'Generate random strings with custom length and character options.',
                'Random String Generator is a local developer tool for creating random text strings with flexible character settings. Choose the length, then include uppercase letters, lowercase letters, numbers and symbols depending on the type of output you need. You can also exclude similar-looking characters such as O, 0, I and l to make generated strings easier to read and type. This is useful for sample tokens, test values, placeholder IDs, QA data, temporary labels, demo credentials, mock API examples and other development tasks where random-looking text is needed quickly. The generator runs entirely in the browser and does not send the generated string to Toolexa servers. It uses browser crypto support when available and falls back to standard random generation when necessary. The result appears in a copyable output area with a clear button for repeated use. It is designed for practical development and testing workflows, not for storing production passwords or high-security secrets.',
                [
                    'Custom string length',
                    'Uppercase letters option',
                    'Lowercase letters option',
                    'Numbers option',
                    'Symbols option',
                    'Exclude similar characters',
                    'Copy result',
                ],
                [
                    'Enter the string length.',
                    'Choose which character groups to include.',
                    'Enable exclude similar characters if readability matters.',
                    'Click Generate String.',
                    'Copy the generated result or clear the tool.',
                ],
                [
                    ['q' => 'Can I choose the string length?', 'a' => 'Yes, choose any length from 1 to 1000 characters.'],
                    ['q' => 'Which characters can be included?', 'a' => 'You can include uppercase, lowercase, numbers and symbols.'],
                    ['q' => 'What does exclude similar characters mean?', 'a' => 'It removes characters that can be confused visually, such as O and 0.'],
                    ['q' => 'Is the generated string stored?', 'a' => 'No, generation happens locally in your browser.'],
                    ['q' => 'Can I use this for passwords?', 'a' => 'It can create random strings, but use a dedicated password manager for important accounts.'],
                ],
                'Developer Tools'
            ),
            self::browserTool(
                'UUID Validator',
                'uuid-validator',
                'VAL',
                'Validate UUID format and detect the UUID version.',
                'UUID Validator is a simple developer utility for checking whether a value is a valid UUID and identifying its version. Paste a UUID from an API response, database record, log file, test fixture, configuration value or documentation example, and the tool checks the format locally in your browser. When the value is valid, it shows the UUID version so you can tell whether it is version 1, 3, 4, 5 or another valid variant. When the value is invalid, the result clearly explains that the format does not match the standard UUID pattern. This is useful for debugging integrations, validating imported data, reviewing identifiers, checking test payloads and confirming that generated values match expected structure. The tool does not upload or store the UUID you enter. It provides a copyable result summary and a clear button so you can validate multiple values quickly. The interface is intentionally clean and responsive, matching the rest of Toolexa’s utility pages.',
                [
                    'Validate UUID format',
                    'Detect UUID version',
                    'Show validation status',
                    'Copy result',
                    'Clear button',
                    'Local browser validation',
                ],
                [
                    'Paste a UUID into the input field.',
                    'Click Validate UUID.',
                    'Review the validation status and detected version.',
                    'Copy the result summary if needed.',
                    'Clear the input before checking another UUID.',
                ],
                [
                    ['q' => 'What does UUID Validator check?', 'a' => 'It checks whether the input matches the standard UUID format.'],
                    ['q' => 'Can it detect UUID version?', 'a' => 'Yes, valid UUIDs show their detected version.'],
                    ['q' => 'Does validation happen on the server?', 'a' => 'No, validation runs locally in your browser.'],
                    ['q' => 'Can I validate UUID v4 values?', 'a' => 'Yes, UUID v4 is supported along with other common UUID versions.'],
                    ['q' => 'Can I copy the validation result?', 'a' => 'Yes, use the Copy Result button after validation.'],
                ],
                'Developer Tools'
            ),
            self::browserTool(
                'Binary ↔ Decimal Converter',
                'binary-decimal-converter',
                'BIN',
                'Convert binary to decimal and decimal to binary locally.',
                'Binary ↔ Decimal Converter is a browser-based developer and learning tool for converting values between binary and decimal formats. Choose binary to decimal when you need to turn a base-2 value such as 101010 into a normal decimal number. Choose decimal to binary when you want to see how a base-10 integer is represented in binary. This is useful for students, developers, networking learners, electronics basics, programming practice, bitwise operation examples and quick debugging notes. The conversion runs instantly in your browser and does not store the value you enter. Input validation helps prevent invalid binary digits or unsupported decimal values from producing confusing results. The output appears in a clean result panel that can be copied with one button, and Clear resets the tool for another conversion. It is intentionally focused on integer conversions so the page remains predictable, fast and easy to use on both desktop and mobile screens.',
                [
                    'Binary to decimal conversion',
                    'Decimal to binary conversion',
                    'Instant browser conversion',
                    'Input validation',
                    'Copy result',
                    'Clear button',
                ],
                [
                    'Choose Binary to Decimal or Decimal to Binary.',
                    'Enter the value you want to convert.',
                    'Click Convert Instantly.',
                    'Review the converted output.',
                    'Copy the result or clear the tool.',
                ],
                [
                    ['q' => 'Can I convert binary to decimal?', 'a' => 'Yes, select Binary to Decimal and enter a value containing only 0 and 1.'],
                    ['q' => 'Can I convert decimal to binary?', 'a' => 'Yes, select Decimal to Binary and enter a non-negative integer.'],
                    ['q' => 'Does conversion happen locally?', 'a' => 'Yes, all conversion runs in your browser.'],
                    ['q' => 'Are fractional numbers supported?', 'a' => 'No, this tool is focused on whole-number integer conversion.'],
                    ['q' => 'Can I copy the converted value?', 'a' => 'Yes, use the Copy Result button after conversion.'],
                ],
                'Developer Tools'
            ),
        ];
    }

    private static function textTool(string $name, string $slug, string $icon, string $desc, string $introduction, array $features, array $howTo, array $faq, string $category = 'Text Tools'): array
    {
        $related = $category === 'Developer Tools'
            ? ['base64-encoder', 'base64-decoder', 'url-encoder-decoder', 'md5-hash-generator', 'text-case-converter']
            : ['text-case-converter', 'word-counter', 'character-counter', 'remove-duplicate-lines', 'remove-extra-spaces', 'text-repeater', 'lorem-ipsum-generator'];

        return [
            'name' => $name,
            'slug' => $slug,
            'view' => 'text-utility',
            'icon' => $icon,
            'desc' => $desc,
            'category' => $category,
            'seo_title' => $name.' - Free Online '.Str::singular($category),
            'seo_description' => $desc.' Use this free Toolexa '.Str::lower($category).' online with instant results, copy output and mobile friendly design.',
            'keywords' => Str::lower($name).', '.Str::lower($category).', online tool, free tool',
            'formula' => null,
            'introduction' => $introduction,
            'features' => $features,
            'how_to' => $howTo,
            'faq' => $faq,
            'related' => $related,
        ];
    }

    public static function utilityTools(): array
    {
        return [
            self::browserTool(
                'Random Number Generator',
                'random-number-generator',
                'RNG',
                'Generate random numbers from a custom minimum and maximum range.',
                'Random Number Generator is a browser-based utility for creating one or more random numbers within a range you control. Enter the minimum value, maximum value and how many numbers you need, then choose whether duplicates are allowed. This is useful for classroom activities, giveaways, test data, sampling, quick decisions, practice problems, games, mock records and everyday situations where a fair random number is helpful. When duplicates are disabled, the tool checks the available range so it does not produce impossible results. The generated numbers appear in a clean output area that can be copied for use in spreadsheets, documents, forms, chats or development notes. Processing happens locally in your browser, and Toolexa does not store the range or generated results. The page is designed for quick repeated use with clear controls, responsive layout and a simple reset button. It gives practical random-number output without requiring a spreadsheet formula or coding snippet.',
                [
                    'Minimum and maximum range',
                    'Generate multiple random numbers',
                    'Allow or disallow duplicates',
                    'Range validation',
                    'Copy results',
                    'Clear button',
                    'Local browser processing',
                ],
                [
                    'Enter the minimum and maximum values.',
                    'Enter how many random numbers you want.',
                    'Choose whether duplicates are allowed.',
                    'Click Generate Numbers.',
                    'Copy the result or clear the tool.',
                ],
                [
                    ['q' => 'Can I set my own number range?', 'a' => 'Yes, enter any minimum and maximum integer range.'],
                    ['q' => 'Can I generate more than one number?', 'a' => 'Yes, choose how many random numbers you need.'],
                    ['q' => 'Can I prevent duplicates?', 'a' => 'Yes, disable Allow duplicates before generating.'],
                    ['q' => 'Does the tool store generated numbers?', 'a' => 'No, generation happens locally in your browser.'],
                    ['q' => 'What happens if the range is too small?', 'a' => 'The tool shows a message when unique output is impossible for the selected range and count.'],
                ],
                'Utility Tools'
            ),
        ];
    }

    private static function browserTool(string $name, string $slug, string $icon, string $desc, string $introduction, array $features, array $howTo, array $faq, string $category): array
    {
        $related = $category === 'Developer Tools'
            ? ['uuid-generator', 'uuid-validator', 'random-string-generator', 'binary-decimal-converter', 'base64-encoder', 'md5-hash-generator']
            : ['random-number-generator', 'age-calculator', 'unit-converter', 'password-generator', 'qr-generator'];

        return [
            'name' => $name,
            'slug' => $slug,
            'view' => 'browser-utility',
            'icon' => $icon,
            'desc' => $desc,
            'category' => $category,
            'seo_title' => $name.' - Free Online '.Str::singular($category),
            'seo_description' => $desc.' Use this free Toolexa tool locally in your browser with copy, clear and responsive controls.',
            'keywords' => Str::lower($name).', '.Str::lower($category).', browser tool, free online tool',
            'formula' => null,
            'introduction' => $introduction,
            'features' => $features,
            'how_to' => $howTo,
            'faq' => $faq,
            'related' => $related,
        ];
    }

    public static function imageTools(): array
    {
        return [
            self::imageTool(
                'Image Resizer',
                'image-resizer',
                'RSZ',
                'Resize JPG, PNG and WebP images by width and height in your browser.',
                'Image Resizer is a browser-based image tool for changing image dimensions without uploading files to a server. Choose a JPG, JPEG, PNG or WebP image, enter the target width and height, and resize it directly with JavaScript canvas. The maintain aspect ratio option helps prevent stretched or squeezed results by automatically adjusting the paired dimension when you change one side. This is useful for preparing images for websites, forms, social posts, thumbnails, profile pictures, product previews, blog images and lightweight design tasks. The original image preview lets you confirm the selected file, while the output preview shows the resized result before you download it. Because processing happens locally in the browser, your image stays on your device and is not stored by Toolexa. The tool is designed for quick everyday resizing rather than complex photo editing, so the controls stay clean, responsive and easy to use on desktop or mobile screens.',
                [
                    'Resize by width and height',
                    'Maintain aspect ratio option',
                    'Preview original image',
                    'Preview resized output',
                    'Download resized image',
                    'Clear image controls',
                    'Local browser canvas processing',
                ],
                [
                    'Choose a JPG, PNG or WebP image from your device.',
                    'Enter the target width and height.',
                    'Keep Maintain aspect ratio enabled if you want proportional resizing.',
                    'Click Resize Image and check the output preview.',
                    'Download the resized image or clear the tool.',
                ],
                [
                    ['q' => 'Does Image Resizer upload my image?', 'a' => 'No, resizing happens locally in your browser using canvas.'],
                    ['q' => 'Which image formats are supported?', 'a' => 'The tool accepts JPG, JPEG, PNG and WebP images.'],
                    ['q' => 'What does maintain aspect ratio do?', 'a' => 'It keeps the original image proportions when width or height changes.'],
                    ['q' => 'Can I preview before downloading?', 'a' => 'Yes, the resized output appears in the preview area before download.'],
                    ['q' => 'Will resizing reduce file size?', 'a' => 'It often can, especially when reducing dimensions, but file size also depends on format and image content.'],
                ]
            ),
            self::imageTool(
                'Image Compressor',
                'image-compressor',
                'CMP',
                'Compress JPG, PNG and WebP images with adjustable quality.',
                'Image Compressor helps reduce image file size directly in the browser without sending the file anywhere. Select a JPG, JPEG, PNG or WebP image, adjust the compression quality, and generate a smaller output using canvas. This is useful when preparing images for websites, blogs, forms, email attachments, online listings or pages where lighter files improve loading speed. The before and after preview makes it easier to compare visual quality, while the file summary shows the original and compressed sizes so you can decide whether the result is acceptable. Lower quality values usually create smaller files with more visible compression, while higher values preserve more detail. The tool is intentionally simple and responsive, giving you a fast way to optimize individual images without installing photo software. All processing runs locally in your browser, so Toolexa does not upload, store or inspect your image. Download the compressed file when you are happy with the preview.',
                [
                    'Compress JPEG, PNG and WebP',
                    'Adjustable compression quality',
                    'Preview before and after',
                    'Original and compressed size summary',
                    'Download compressed image',
                    'Clear image controls',
                    'No server upload',
                ],
                [
                    'Choose a supported image from your device.',
                    'Move the quality slider to set compression level.',
                    'Click Compress Image.',
                    'Compare the before and after previews and file sizes.',
                    'Download the compressed image or clear the tool.',
                ],
                [
                    ['q' => 'Does Image Compressor store my file?', 'a' => 'No, compression is performed locally in the browser.'],
                    ['q' => 'Which formats can I compress?', 'a' => 'You can select JPG, JPEG, PNG and WebP images.'],
                    ['q' => 'What quality value should I use?', 'a' => 'Start around 80% and lower it if you need a smaller file.'],
                    ['q' => 'Can PNG compression change transparency?', 'a' => 'PNG output can preserve transparency, but some browser canvas conversions may vary by format.'],
                    ['q' => 'Can I preview the compressed image?', 'a' => 'Yes, the output preview is shown before download.'],
                ]
            ),
            self::imageTool(
                'JPG to PNG Converter',
                'jpg-to-png-converter',
                'PNG',
                'Convert JPG or JPEG images to PNG locally in your browser.',
                'JPG to PNG Converter is a simple browser tool for converting JPEG images into PNG format without uploading the image to a server. Select a JPG or JPEG file, preview the original image, and convert it with canvas into a downloadable PNG. PNG can be useful when you need a lossless-style format for screenshots, design assets, documentation, editing workflows or platforms that specifically request PNG files. This conversion does not magically restore detail already lost by JPEG compression, but it does create a PNG output that can be easier to use in some design and publishing contexts. The tool keeps the workflow direct: choose the file, convert, preview and download. Your image remains on your device because all processing runs in the browser. The responsive layout works on desktop and mobile, and the clear button resets the selected image and output. It is a practical one-page utility for quick format conversion when opening a full image editor would be unnecessary.',
                [
                    'Convert JPG and JPEG to PNG',
                    'Preview selected image',
                    'Preview PNG output',
                    'Download PNG file',
                    'Clear image controls',
                    'Local canvas conversion',
                ],
                [
                    'Choose a JPG or JPEG image.',
                    'Check the original image preview.',
                    'Click Convert to PNG.',
                    'Review the PNG output preview.',
                    'Download the PNG file or clear the tool.',
                ],
                [
                    ['q' => 'Does JPG to PNG Converter upload images?', 'a' => 'No, conversion happens locally in your browser.'],
                    ['q' => 'Can I convert JPEG files?', 'a' => 'Yes, both .jpg and .jpeg files are supported.'],
                    ['q' => 'Will PNG improve image quality?', 'a' => 'No, converting to PNG does not restore detail lost in the original JPG.'],
                    ['q' => 'Can I preview the converted PNG?', 'a' => 'Yes, the converted output is shown before download.'],
                    ['q' => 'Why is the PNG file larger?', 'a' => 'PNG files can be larger because they use a different format and compression style.'],
                ]
            ),
            self::imageTool(
                'PNG to JPG Converter',
                'png-to-jpg-converter',
                'JPG',
                'Convert PNG images to JPG with a selectable background color.',
                'PNG to JPG Converter lets you turn PNG images into downloadable JPG files directly in your browser. Choose a PNG, JPG or WebP source image, select a background color, and convert the canvas output to JPG. The background color is especially useful for PNG images with transparent areas because JPG does not support transparency. You can choose white for a clean document-style result, black for dark designs, or any custom color that fits the final use. This tool is helpful for websites, product images, email attachments, forms, social platforms and systems that prefer or require JPG files. The preview area shows the original image and the converted output so you can check the result before downloading. All processing happens locally through JavaScript canvas, which means your image is not uploaded or stored on the server. The interface is intentionally lightweight, mobile-friendly and focused on quick format conversion without opening heavier image editing software.',
                [
                    'Convert PNG to JPG',
                    'Select background color',
                    'Preview original image',
                    'Preview JPG output',
                    'Download JPG file',
                    'Clear image controls',
                    'Local browser processing',
                ],
                [
                    'Choose a PNG, JPG or WebP image.',
                    'Select the background color for transparent areas.',
                    'Click Convert to JPG.',
                    'Review the output preview.',
                    'Download the JPG file or clear the tool.',
                ],
                [
                    ['q' => 'Why do I need a background color?', 'a' => 'JPG does not support transparency, so transparent PNG areas need a solid background.'],
                    ['q' => 'Does this upload my PNG?', 'a' => 'No, conversion happens locally in your browser.'],
                    ['q' => 'Can I choose white background?', 'a' => 'Yes, white is the default background color.'],
                    ['q' => 'Can I preview the JPG before download?', 'a' => 'Yes, the converted JPG appears in the output preview area.'],
                    ['q' => 'Will JPG reduce file size?', 'a' => 'It often can, depending on the source image and image content.'],
                ]
            ),
            self::imageTool(
                'Image Cropper',
                'image-cropper',
                'CRP',
                'Crop images with free selection or fixed 1:1, 4:3 and 16:9 ratios.',
                'Image Cropper is a local browser tool for cutting an image down to the exact area you need. Choose a JPG, JPEG, PNG or WebP image, then drag on the preview canvas to select the crop area. You can use free crop selection for custom shapes or choose fixed aspect ratio options such as 1:1, 4:3 and 16:9 for profile images, thumbnails, banners and presentation-friendly crops. The selected area is processed with canvas and shown as a downloadable preview before you save it. This is useful for trimming screenshots, product photos, social media images, blog graphics, documentation visuals and quick design assets. Because the crop operation runs locally in your browser, the image is not uploaded, stored or processed on Toolexa servers. The tool is designed for fast everyday cropping rather than advanced photo editing, so it keeps the controls focused: upload, select, crop, preview and download. Clear resets the canvas whenever you want to start over.',
                [
                    'Crop JPG, PNG and WebP images',
                    'Free crop selection',
                    'Fixed aspect ratio options',
                    '1:1, 4:3 and 16:9 ratios',
                    'Preview cropped output',
                    'Download cropped image',
                    'Clear image controls',
                ],
                [
                    'Choose an image from your device.',
                    'Select Free, 1:1, 4:3 or 16:9 aspect ratio.',
                    'Drag on the image preview to mark the crop area.',
                    'Click Crop Image and review the output preview.',
                    'Download the cropped image or clear the tool.',
                ],
                [
                    ['q' => 'Does Image Cropper upload my image?', 'a' => 'No, cropping happens locally in your browser with canvas.'],
                    ['q' => 'How do I select the crop area?', 'a' => 'Drag over the preview image to draw the crop selection.'],
                    ['q' => 'Which aspect ratios are available?', 'a' => 'You can use free crop, 1:1, 4:3 or 16:9.'],
                    ['q' => 'Can I crop PNG and WebP images?', 'a' => 'Yes, JPG, JPEG, PNG and WebP inputs are supported where the browser can read them.'],
                    ['q' => 'Can I preview before downloading?', 'a' => 'Yes, the cropped image appears in the output preview area.'],
                ]
            ),
        ];
    }

    private static function imageTool(string $name, string $slug, string $icon, string $desc, string $introduction, array $features, array $howTo, array $faq): array
    {
        return [
            'name' => $name,
            'slug' => $slug,
            'view' => 'image-utility',
            'icon' => $icon,
            'desc' => $desc,
            'category' => 'Image Tools',
            'seo_title' => $name.' - Free Online Image Tool',
            'seo_description' => $desc.' Process images locally in your browser with preview, download and no server upload.',
            'keywords' => Str::lower($name).', image tool, online image editor, browser image tool',
            'formula' => null,
            'introduction' => $introduction,
            'features' => $features,
            'how_to' => $howTo,
            'faq' => $faq,
            'related' => ['image-resizer', 'image-compressor', 'jpg-to-png-converter', 'png-to-jpg-converter', 'image-cropper'],
        ];
    }

    public static function pdfTools(): array
    {
        return [
            self::pdfTool('Image to PDF Converter', 'image-to-pdf-converter', 'I2P', 'Convert JPG, PNG and WebP images into one downloadable PDF.', 'Image to PDF Converter is a browser-based PDF tool for turning multiple images into a single PDF file without uploading anything to the server. Select JPG, PNG or WebP images, review the selected order, drag files into the sequence you want, and generate one PDF for download. It is useful for receipts, homework photos, scanned notes, product images, document snapshots, certificates and quick image collections that need to be shared as a single file. The tool uses browser-side PDF generation, so your images remain on your device during processing. Preview rows show each selected image name and size before conversion, helping you catch mistakes before downloading. The workflow is intentionally simple: choose images, reorder, convert and download. It is not meant to replace advanced scanning software, but it is a fast everyday utility when you need clean PDF output from common image formats. Clear all removes the selected files and output so you can start again.', ['Convert JPG, PNG and WebP images into a single PDF', 'Support multiple image upload', 'Drag and drop image reordering', 'Preview selected images before download', 'Download PDF', 'Clear all files'], ['Choose one or more JPG, PNG or WebP images.', 'Drag files in the preview list to reorder them.', 'Click Convert to PDF.', 'Review the generated file status.', 'Download the PDF or clear all files.'], [['q' => 'Are images uploaded to Toolexa?', 'a' => 'No, images are processed locally in your browser.'], ['q' => 'Can I add multiple images?', 'a' => 'Yes, you can select multiple supported image files.'], ['q' => 'Can I reorder images before creating the PDF?', 'a' => 'Yes, drag items in the preview list to change their order.'], ['q' => 'Which image formats are supported?', 'a' => 'JPG, JPEG, PNG and WebP are supported where the browser can read them.'], ['q' => 'Can I download the final PDF?', 'a' => 'Yes, use the Download PDF button after conversion.']]),
            self::pdfTool('PDF Page Counter', 'pdf-page-counter', 'PGS', 'Count pages and show basic PDF file information locally.', 'PDF Page Counter is a simple browser tool for checking how many pages a PDF contains before you share, upload or archive it. Choose a PDF file and the tool reads the document locally to display total page count, file size and the PDF version when it can be detected from the file header. This is useful for students, office teams, freelancers, administrators and anyone who needs to confirm document length quickly. You can check forms, reports, invoices, assignments, contracts, ebooks and exported files without opening a full PDF editor. The file is not uploaded to Toolexa and is not permanently stored on the server; the browser reads it only for the current session. If a PDF is encrypted or damaged, the tool shows a clear message instead of pretending the result is complete. The layout follows the same Toolexa result panel style, making it easy to copy the summary or clear the file and check another document.', ['Upload PDF', 'Display total page count', 'Display file size', 'Display PDF version if available', 'Copy result', 'Clear file'], ['Choose a PDF file from your device.', 'Click Count Pages.', 'Review page count, file size and version details.', 'Copy the result if needed.', 'Clear the file before checking another PDF.'], [['q' => 'Does PDF Page Counter upload my PDF?', 'a' => 'No, the PDF is read locally in your browser.'], ['q' => 'Can it show PDF version?', 'a' => 'Yes, the tool reads the PDF header when available.'], ['q' => 'Can it count encrypted PDFs?', 'a' => 'Encrypted PDFs may not be accessible for page counting.'], ['q' => 'Can I copy the page count result?', 'a' => 'Yes, use the Copy Result button.'], ['q' => 'What file information is shown?', 'a' => 'It shows page count, file size and PDF version when available.']]),
            self::pdfTool('PDF Metadata Viewer', 'pdf-metadata-viewer', 'META', 'View PDF title, author, dates, producer and page count.', 'PDF Metadata Viewer helps inspect common document information stored inside a PDF file. Upload a PDF in the browser and the tool attempts to show title, author, subject, creator, producer, creation date, modification date, page count and file size. This is useful when checking exported reports, ebooks, invoices, resumes, forms, design proofs or documents received from another person. Metadata can reveal how a file was created, which software produced it and whether basic document fields were filled correctly before publishing or sharing. The tool performs analysis locally in the browser, so your PDF is not uploaded or stored by Toolexa. Some PDFs may have missing metadata fields, and encrypted or malformed files may limit what can be read. Results appear in a structured panel that can be copied for review, support notes or documentation. Clear file resets the page so another PDF can be inspected quickly.', ['Display Title', 'Display Author', 'Display Subject', 'Display Creator', 'Display Producer', 'Display Creation Date', 'Display Modification Date', 'Display Page Count', 'Display File Size'], ['Choose a PDF file.', 'Click View Metadata.', 'Review the available document fields.', 'Copy the metadata summary if needed.', 'Clear the file to inspect another PDF.'], [['q' => 'Does this viewer store PDF metadata?', 'a' => 'No, the PDF is inspected locally in your browser.'], ['q' => 'Why are some fields blank?', 'a' => 'Some PDFs do not include every metadata field.'], ['q' => 'Can it show page count?', 'a' => 'Yes, accessible PDFs show page count.'], ['q' => 'Can encrypted PDFs be inspected?', 'a' => 'Encrypted PDFs may block metadata or page access.'], ['q' => 'Can I copy the metadata?', 'a' => 'Yes, use the Copy Result button.']]),
            self::pdfTool('PDF Password Checker', 'pdf-password-checker', 'LOCK', 'Check whether a PDF appears password protected or encrypted.', 'PDF Password Checker is a browser-based tool for checking whether a PDF appears to be encrypted or password protected. Select a PDF and the tool tries to load it locally. If it can access the document, it displays that the PDF is readable and shows page count when available. If the file cannot be opened because it is encrypted, password protected or otherwise restricted, the tool shows a clear validation message. This is useful before submitting documents to portals, sending files to clients, processing PDFs with other tools or troubleshooting why a file cannot be opened. The check happens in your browser and the PDF is not uploaded, stored or sent to Toolexa servers. The tool is a practical accessibility check, not a password remover or security bypass. It does not crack passwords; it only reports whether the file can be read by the browser library. Clear file resets the selection for another check.', ['Detect whether the PDF is password protected', 'Display encryption status', 'Display page count if accessible', 'Show validation message', 'Copy result', 'Clear file'], ['Choose a PDF file.', 'Click Check PDF.', 'Review the encryption or accessibility status.', 'Check page count if the file is readable.', 'Copy the result or clear the file.'], [['q' => 'Does this remove PDF passwords?', 'a' => 'No, it only checks whether the PDF appears protected or readable.'], ['q' => 'Is my PDF uploaded?', 'a' => 'No, checking happens locally in your browser.'], ['q' => 'Can it show page count?', 'a' => 'Yes, page count appears when the PDF is accessible.'], ['q' => 'What does encrypted mean?', 'a' => 'It means the PDF has protection that may require a password or restrict reading.'], ['q' => 'Can I copy the status?', 'a' => 'Yes, use the Copy Result button.']]),
            self::pdfTool('PDF Merger', 'pdf-merger', 'MRG', 'Merge multiple PDF files into one downloadable PDF locally.', 'PDF Merger is a local browser tool for combining multiple PDF files into a single PDF without uploading files to a server. Select two or more PDFs, preview the selected list, drag files into the order you want, remove any file you do not need and merge the remaining documents into one downloadable PDF. It is useful for reports, invoices, forms, receipts, contracts, assignments, scanned sections and document packs that need to be submitted together. The tool uses browser-side PDF processing, so your files stay on your device during the session. If a PDF is encrypted or damaged, the merge may fail and the tool will show a message so you can remove or replace the file. The interface is built for quick document assembly rather than advanced editing: choose files, reorder, merge and download. Clear all removes the selection and generated output. This keeps the workflow fast, private and consistent with Toolexa’s other browser-first tools.', ['Merge multiple PDF files into one', 'Drag and drop file reordering', 'Preview selected files', 'Download merged PDF', 'Remove selected files', 'Clear all'], ['Choose two or more PDF files.', 'Drag the file list to set the merge order.', 'Remove any unwanted files.', 'Click Merge PDF Files.', 'Download the merged PDF or clear all.'], [['q' => 'Are PDFs uploaded while merging?', 'a' => 'No, merging runs locally in your browser.'], ['q' => 'Can I reorder files before merging?', 'a' => 'Yes, drag the selected files into the desired order.'], ['q' => 'Can I remove one selected file?', 'a' => 'Yes, each preview row includes a remove button.'], ['q' => 'Can encrypted PDFs be merged?', 'a' => 'Encrypted PDFs may fail to merge unless they are readable by the browser library.'], ['q' => 'Can I download the merged PDF?', 'a' => 'Yes, use Download PDF after merging.']]),
        ];
    }

    private static function pdfTool(string $name, string $slug, string $icon, string $desc, string $introduction, array $features, array $howTo, array $faq): array
    {
        return [
            'name' => $name,
            'slug' => $slug,
            'view' => 'pdf-utility',
            'icon' => $icon,
            'desc' => $desc,
            'category' => 'PDF Tools',
            'seo_title' => $name.' - Free Online PDF Tool',
            'seo_description' => $desc.' Process PDF files locally in your browser with no permanent server storage.',
            'keywords' => Str::lower($name).', pdf tools, online pdf tool, browser pdf utility',
            'formula' => null,
            'introduction' => $introduction,
            'features' => $features,
            'how_to' => $howTo,
            'faq' => $faq,
            'related' => ['image-to-pdf-converter', 'pdf-page-counter', 'pdf-metadata-viewer', 'pdf-password-checker', 'pdf-merger'],
        ];
    }

    public static function sellerTools(): array
    {
        return [
            self::sellerTool(
                'Meesho Label Cropper',
                'meesho-label-cropper',
                'MSH',
                'Auto crop Meesho shipping labels from A4 PDF pages to 4x6 output.',
                'Meesho Label Cropper is a seller-focused tool for quickly converting Meesho shipping label PDFs into cleaner 4x6 label pages. Upload the marketplace label PDF, select the Meesho label layout preset and click Process Label. The tool applies predefined crop coordinates for common Meesho A4 label formats, creates a new 4x6 PDF and lets you download the cropped output. It does not ask you to manually crop or drag a selection box. This is useful for sellers who print thermal labels or need a more compact shipping label format for packing workflows. Processing runs in the browser using PDF tools, so the uploaded file is not permanently stored on Toolexa servers. The result is designed for common label layouts, but sellers should preview the output and confirm it matches their current marketplace format before bulk printing. The simple flow keeps the task fast: choose layout, upload PDF, process and download.',
                ['Auto crop Meesho shipping label', 'A4 to 4x6 output', 'Predefined Meesho label layouts', 'Download PDF', 'Fast browser processing'],
                ['Select the Meesho label type.', 'Upload the Meesho shipping label PDF.', 'Click Process Label.', 'Review the generated output status.', 'Download the cropped 4x6 PDF.'],
                [['q' => 'Do I need to manually crop the Meesho label?', 'a' => 'No, the tool uses predefined crop coordinates for common Meesho layouts.'], ['q' => 'Does it upload my PDF?', 'a' => 'Processing happens locally where possible and files are not permanently stored.'], ['q' => 'What output size is created?', 'a' => 'The output is generated as 4x6 label-sized PDF pages.'], ['q' => 'Can layout changes affect cropping?', 'a' => 'Yes, marketplace layout updates may require choosing another preset.'], ['q' => 'Can I download the cropped PDF?', 'a' => 'Yes, download is available after processing.']],
                ['standard_a4' => 'Meesho A4 Standard', 'invoice_top' => 'Meesho Label Top Area']
            ),
            self::sellerTool(
                'Amazon Label Cropper',
                'amazon-label-cropper',
                'AMZ',
                'Auto detect and crop Amazon shipping labels into downloadable PDF output.',
                'Amazon Label Cropper helps marketplace sellers crop Amazon shipping label PDFs without manual selection. Upload the PDF, keep Auto Detect selected or choose a preset, and the tool uses predefined layout rules to crop the shipping label area into a compact label-sized PDF. This is built for common Amazon seller label exports where the printable shipping label appears in a predictable region of the page. The tool is useful when preparing labels for thermal printers or when reducing an A4 page into a cleaner label page. Processing is performed in the browser with PDF handling libraries where possible, so Toolexa does not permanently store the PDF. Because Amazon may update label formats by region, service type or seller workflow, users should confirm the generated output before printing many labels. The interface stays intentionally simple: select layout, upload PDF, process automatically and download the result.',
                ['Auto detect Amazon layout', 'Auto crop shipping label', '4x6 style output', 'Download PDF', 'No manual crop selection'],
                ['Select Auto Detect or an Amazon preset.', 'Upload the Amazon shipping label PDF.', 'Click Process Label.', 'Check the output status.', 'Download the cropped PDF.'],
                [['q' => 'Does Amazon Label Cropper auto detect layout?', 'a' => 'Yes, Auto Detect uses page shape and preset rules to choose a crop region.'], ['q' => 'Do I need to drag a crop box?', 'a' => 'No, cropping is automatic.'], ['q' => 'Are files stored on Toolexa?', 'a' => 'No permanent file storage is used.'], ['q' => 'Can Amazon layout changes affect results?', 'a' => 'Yes, verify output if Amazon changes the label design.'], ['q' => 'Can I download the cropped label?', 'a' => 'Yes, the processed output can be downloaded as PDF.']],
                ['auto' => 'Auto Detect Amazon Layout', 'a4_top' => 'Amazon A4 Top Label', 'a4_center' => 'Amazon A4 Center Label']
            ),
            self::sellerTool(
                'Flipkart Label Cropper',
                'flipkart-label-cropper',
                'FK',
                'Auto crop Flipkart shipping labels into printable label PDF output.',
                'Flipkart Label Cropper is designed for sellers who need to turn Flipkart shipping label PDFs into a compact cropped PDF without manual editing. Upload the Flipkart label PDF, choose the matching label type and process it with predefined coordinates for common Flipkart layouts. The tool creates label-sized pages that are easier to print or manage than full A4 exports. It is useful for order packing, thermal printer workflows and sellers who repeatedly prepare shipping labels. The PDF is handled in the browser where possible, and Toolexa does not permanently store uploaded files. Since marketplace label templates can change over time, the output should be checked before printing in bulk. The goal is speed and consistency: select the Flipkart preset, upload the label PDF, let the tool crop automatically and download the result. No manual crop box or image editor is required.',
                ['Auto crop Flipkart label', 'Predefined Flipkart layouts', 'Download PDF', 'Fast processing', 'No manual cropping'],
                ['Select the Flipkart label layout.', 'Upload the Flipkart PDF.', 'Click Process Label.', 'Review the generated output status.', 'Download the cropped PDF.'],
                [['q' => 'Does this tool manually crop Flipkart labels?', 'a' => 'No, it uses preset crop coordinates.'], ['q' => 'Can I use A4 Flipkart PDFs?', 'a' => 'Yes, common A4 label exports are supported by presets.'], ['q' => 'Does Toolexa store the file?', 'a' => 'No, files are not permanently stored.'], ['q' => 'What if the crop is not aligned?', 'a' => 'Try another layout preset or check if Flipkart changed its label template.'], ['q' => 'Is the output downloadable?', 'a' => 'Yes, the cropped label is downloaded as a PDF.']],
                ['standard_a4' => 'Flipkart A4 Standard', 'top_label' => 'Flipkart Top Label']
            ),
            self::sellerTool(
                'Myntra Label Cropper',
                'myntra-label-cropper',
                'MYN',
                'Auto crop Myntra seller labels into downloadable PDF output.',
                'Myntra Label Cropper gives sellers a faster way to prepare Myntra shipping label PDFs for printing. Instead of opening a PDF editor and manually selecting a region, choose the Myntra label type, upload the PDF and let the tool apply predefined crop coordinates. The output is generated as a compact PDF suitable for label printing workflows. This is helpful for sellers handling repeated packing tasks, thermal label preparation or cleaner document organization. Processing happens locally in the browser where possible, and uploaded files are not permanently stored by Toolexa. Because seller portals may adjust label templates, users should confirm the generated crop matches the current Myntra format before printing many labels. The interface is intentionally minimal so the task stays quick: select label type, upload PDF, process automatically and download the cropped PDF. The tool focuses on predictable marketplace label layouts rather than free manual cropping.',
                ['Auto crop Myntra labels', 'Predefined seller label presets', 'Download PDF', 'Browser-side processing', 'Clear file'],
                ['Choose the Myntra label preset.', 'Upload the Myntra seller PDF.', 'Click Process Label.', 'Check the processing summary.', 'Download the cropped PDF.'],
                [['q' => 'Does Myntra Label Cropper require manual crop selection?', 'a' => 'No, it uses predefined label layouts.'], ['q' => 'Can I download the result?', 'a' => 'Yes, a cropped PDF is generated for download.'], ['q' => 'Is the PDF permanently stored?', 'a' => 'No, uploaded PDFs are not permanently stored.'], ['q' => 'Can Myntra template changes affect output?', 'a' => 'Yes, always preview output before bulk printing.'], ['q' => 'What size is the result?', 'a' => 'The output is generated as label-sized PDF pages.']],
                ['standard_a4' => 'Myntra A4 Standard', 'shipping_block' => 'Myntra Shipping Block']
            ),
            self::sellerTool(
                'Ajio Label Cropper',
                'ajio-label-cropper',
                'AJI',
                'Auto crop Ajio shipping labels and download a cropped PDF.',
                'Ajio Label Cropper is a smart seller utility for cropping Ajio marketplace shipping label PDFs using predefined layouts. Upload an Ajio label PDF, select the matching layout and process it automatically into a compact PDF output. The tool avoids manual cropping, screenshots and repeated PDF editor work. It is especially useful for sellers who need faster packing workflows or label-sized pages for printing. Processing is handled locally in the browser where possible, and Toolexa does not permanently store uploaded PDFs. Common Ajio label positions are represented by presets, but sellers should check the output whenever the marketplace changes label design or page arrangement. The result can be downloaded as a PDF after processing. The workflow follows the same simple Toolexa pattern: select label type, upload PDF, click process, automatically generate cropped output and download. Clear file resets the tool for the next label.',
                ['Auto crop Ajio labels', 'Predefined Ajio layouts', 'Download PDF', 'Fast processing', 'Clear file'],
                ['Select the Ajio label type.', 'Upload the Ajio PDF.', 'Click Process Label.', 'Review the output status.', 'Download the cropped PDF.'],
                [['q' => 'Does Ajio Label Cropper upload my PDF?', 'a' => 'No permanent server storage is used.'], ['q' => 'Do I need to crop manually?', 'a' => 'No, the tool applies predefined crop coordinates.'], ['q' => 'Can I download cropped labels?', 'a' => 'Yes, download the generated PDF after processing.'], ['q' => 'What if the Ajio layout is different?', 'a' => 'Try another preset and verify the output before printing.'], ['q' => 'Is it suitable for seller workflows?', 'a' => 'Yes, it is designed for marketplace label preparation.']],
                ['standard_a4' => 'Ajio A4 Standard', 'top_label' => 'Ajio Top Label']
            ),
        ];
    }

    private static function sellerTool(string $name, string $slug, string $icon, string $desc, string $introduction, array $features, array $howTo, array $faq, array $layouts): array
    {
        return [
            'name' => $name,
            'slug' => $slug,
            'view' => 'seller-label',
            'icon' => $icon,
            'desc' => $desc,
            'category' => 'Seller Tools',
            'seo_title' => $name.' - Seller Shipping Label Tool',
            'seo_description' => $desc.' Crop marketplace labels with predefined layouts and download the output PDF.',
            'keywords' => Str::lower($name).', seller tools, shipping label cropper, marketplace label tool',
            'formula' => null,
            'introduction' => $introduction,
            'features' => $features,
            'how_to' => $howTo,
            'faq' => $faq,
            'related' => ['meesho-label-cropper', 'amazon-label-cropper', 'flipkart-label-cropper', 'myntra-label-cropper', 'ajio-label-cropper'],
            'label_layouts' => $layouts,
        ];
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
            ['name' => 'Text Tools', 'slug' => 'text-tools', 'icon' => 'TXT', 'count' => $counts['text-tools']['count'] ?? 0, 'status' => null],
            ['name' => 'Utility', 'slug' => 'utility', 'icon' => 'UTL', 'count' => $counts['utility']['count'] ?? 0, 'status' => null],
            ['name' => 'Developer Tools', 'slug' => 'developer-tools', 'icon' => 'DEV', 'count' => $counts['developer-tools']['count'] ?? 0, 'status' => null],
            ['name' => 'Image Tools', 'slug' => 'image-tools', 'icon' => 'IMG', 'count' => $counts['image-tools']['count'] ?? 0, 'status' => null],
            ['name' => 'PDF Tools', 'slug' => 'pdf-tools', 'icon' => 'PDF', 'count' => $counts['pdf-tools']['count'] ?? 0, 'status' => null],
            ['name' => 'Seller Tools', 'slug' => 'seller-tools', 'icon' => 'SEL', 'count' => $counts['seller-tools']['count'] ?? 0, 'status' => null],
        ];
    }

    public function index()
    {
        $tools = self::tools();
        $popularSlugs = self::popularSlugs();
        $popular = self::toolsBySlugs($popularSlugs);
        $homepageCategories = self::homepageCategories();
        $recentTools = self::recentTools(8);
        $latestArticles = collect(BlogRepository::all())->take(3)->values()->all();
        $discoverFeatures = DiscoverFeature::catalog();
        $schemaJsonLd = [
            [
                '@context' => 'https://schema.org',
                '@type' => 'CollectionPage',
                'name' => 'Toolexa Free Online Tools',
                'description' => 'Free online calculators, PDF tools, image tools, text utilities, developer tools and seller tools.',
                'url' => url('/'),
                'mainEntity' => [
                    '@type' => 'ItemList',
                    'itemListElement' => collect($popular)
                        ->values()
                        ->map(fn ($tool, $index) => [
                            '@type' => 'ListItem',
                            'position' => $index + 1,
                            'name' => $tool['name'],
                            'url' => url('tools/'.$tool['slug']),
                        ])
                        ->all(),
                ],
            ],
            [
                '@context' => 'https://schema.org',
                '@type' => 'FAQPage',
                'mainEntity' => [
                    [
                        '@type' => 'Question',
                        'name' => 'Are all Toolexa tools free?',
                        'acceptedAnswer' => [
                            '@type' => 'Answer',
                            'text' => 'Yes, Toolexa tools are free to use in the browser without registration.',
                        ],
                    ],
                    [
                        '@type' => 'Question',
                        'name' => 'Do I need an account to use Toolexa?',
                        'acceptedAnswer' => [
                            '@type' => 'Answer',
                            'text' => 'No account is required. You can open a tool and use it directly.',
                        ],
                    ],
                    [
                        '@type' => 'Question',
                        'name' => 'Does Toolexa store my tool inputs?',
                        'acceptedAnswer' => [
                            '@type' => 'Answer',
                            'text' => 'Toolexa tools are designed for quick browser-based use and do not require storing personal tool inputs.',
                        ],
                    ],
                ],
            ],
        ];

        return view('home', compact('tools', 'popular', 'popularSlugs', 'homepageCategories', 'recentTools', 'latestArticles', 'discoverFeatures', 'schemaJsonLd'));
    }
}

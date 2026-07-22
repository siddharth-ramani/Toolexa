<?php

namespace App\Http\Controllers\Tools;

use App\Services\HomepageService;
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
        static $resolvedTools;

        if ($resolvedTools !== null) {
            return $resolvedTools;
        }

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

        return $resolvedTools = self::enrichTools(array_merge($tools, self::textTools(), self::developerTools(), self::localTools(), self::utilityTools(), self::imageTools(), self::pdfTools(), self::sellerTools(), self::financeTools(), self::newBrowserTools(), self::latestBrowserTools(), self::newestBrowserTools(), self::browserTools100()));
    }

    public static function newBrowserTools(): array
    {
        $definitions = [
            ['SHA-256 Hash Generator', 'sha-256-hash-generator', 'SHA', 'Generate a secure SHA-256 hash from text instantly.', 'Security Tools',
                'SHA-256 Hash Generator is a fast, privacy-friendly utility for turning text into a 256-bit cryptographic digest. Type or paste a message, identifier, configuration value, API sample or document excerpt and the hash updates live as you work. The result is displayed as the standard 64-character hexadecimal value, ready to copy into a checksum list, development note, test fixture or verification workflow. SHA-256 always creates the same digest for the same exact input, while even a tiny input change produces a very different result. That makes it useful for integrity checks and technical comparisons, but a plain hash alone is not suitable for storing passwords. This Toolexa tool uses the Web Crypto API built into modern browsers. Your text stays on your device, is not uploaded to Toolexa and is not permanently stored. The clean responsive interface works on phones, tablets and desktops, includes an explicit Generate button for confirmation, and also performs live generation for convenience. Copy the digest with one click, share the tool link, or clear both fields before starting another check.',
                ['Generate standard SHA-256 digests', 'Live hash generation while typing', '64-character hexadecimal output', 'One-click copy hash', 'Clear input and output', 'Local Web Crypto processing'],
                ['Enter or paste text in the input field.', 'Wait for the live digest or click Generate SHA-256.', 'Review the 64-character hexadecimal hash.', 'Click Copy Hash to use the result elsewhere.', 'Click Clear to remove the input and output.'],
                [['q'=>'What is SHA-256?','a'=>'SHA-256 is a cryptographic hash function that produces a fixed 256-bit digest, commonly shown as 64 hexadecimal characters.'],['q'=>'Is SHA-256 encryption?','a'=>'No. Hashing is one-way and does not provide a method to recover the original text.'],['q'=>'Can I use this for passwords?','a'=>'Do not store passwords with plain SHA-256; use a dedicated salted password-hashing algorithm such as Argon2 or bcrypt.'],['q'=>'Does Toolexa upload my text?','a'=>'No. The hash is generated locally with your browser Web Crypto API.'],['q'=>'Will identical text create the same hash?','a'=>'Yes. Identical input bytes always produce the same SHA-256 digest.']],
                ['password-strength-checker','password-generator','md5-hash-generator','base64-encoder-decoder']],
            ['URL Parser', 'url-parser', 'URL', 'Parse any URL into protocol, host, port, path, query parameters and fragment.', 'Developer Tools',
                'URL Parser is a practical browser-based developer tool for breaking a complete web address into its individual components. Paste an absolute URL and instantly inspect its protocol, hostname, host, explicit or default port, pathname, query parameters and fragment. This is helpful when debugging redirects, API requests, tracking links, callback URLs, campaign parameters, deep links and application routing. Query parameters are decoded and listed individually, including repeated keys, so complex links are easier to understand than they are in a single address bar. The parsed result is also assembled into a clean text summary that you can copy into tickets, documentation, test cases or team messages. Parsing uses the browser’s native URL API and happens entirely on your device. Toolexa does not upload, transmit or permanently store the URLs you enter, including any query values they contain. Clear validation messages identify incomplete or invalid absolute URLs. The responsive two-panel layout keeps the original address and parsed data visible together on larger screens while remaining comfortable on mobile. Use the Parse URL button, copy the structured output, share the tool page or clear the fields for another link.',
                ['Parse complete absolute URLs', 'Protocol, host and port details', 'Path and fragment extraction', 'Decoded query parameter list', 'Copy structured parsed data', 'Local browser processing'],
                ['Paste an absolute URL including http:// or https://.', 'Click Parse URL.', 'Review protocol, host, port, path and fragment.', 'Inspect each decoded query parameter.', 'Copy the parsed summary or clear the tool.'],
                [['q'=>'What URL formats are supported?','a'=>'Enter an absolute URL with a scheme, such as https://example.com/path?x=1.'],['q'=>'Does the parser decode query parameters?','a'=>'Yes. The browser URL API decodes parameter names and values for display.'],['q'=>'Why is the port marked default?','a'=>'Browsers omit standard ports such as 80 for HTTP and 443 for HTTPS unless a different port is specified.'],['q'=>'Are repeated query keys supported?','a'=>'Yes. Every query entry is listed, including repeated keys.'],['q'=>'Is my URL uploaded?','a'=>'No. Parsing runs locally in your browser.']],
                ['url-encoder-decoder','url-slug-generator','json-formatter','base64-encoder-decoder']],
            ['Color Palette Generator', 'color-palette-generator', 'CLR', 'Generate, lock, copy and export beautiful random color palettes.', 'Color Tools',
                'Color Palette Generator helps designers, developers, artists and content creators quickly discover sets of colors for websites, apps, presentations, illustrations and brand experiments. Generate a five-color random palette with one click and view every shade as both a HEX value and an RGB value. If one or more colors fit your direction, lock those swatches and generate again; locked colors remain in place while the other positions refresh. Each swatch includes a copy control for moving its HEX value directly into CSS, design software or a style guide. The complete palette can also be exported as a portable JSON file containing the HEX and RGB values, making it easy to save a promising combination or share it with a teammate. Color generation and file creation happen locally with JavaScript in your browser. Toolexa does not upload or permanently store your choices. The palette adapts to smaller screens, uses readable foreground colors automatically, and supports repeated exploration without a page refresh. Generate a new set, preserve favorites with locks, copy individual colors, export the complete palette, or share the tool link when collaborating.',
                ['Five-color random palettes', 'HEX and RGB values', 'Lock individual colors', 'Copy any HEX value', 'Export palette as JSON', 'Responsive accessible swatches'],
                ['Click Generate Palette to create five colors.', 'Lock any colors you want to keep.', 'Generate again to replace only unlocked colors.', 'Click a swatch copy button to copy its HEX value.', 'Export the complete palette as a JSON file.'],
                [['q'=>'How many colors are generated?','a'=>'Each palette contains five colors.'],['q'=>'What does locking a color do?','a'=>'A locked swatch stays unchanged when you generate another palette.'],['q'=>'Which color formats are shown?','a'=>'Every swatch displays HEX and RGB values.'],['q'=>'What does Export Palette download?','a'=>'It downloads a JSON file containing the current HEX and RGB values.'],['q'=>'Are palettes stored by Toolexa?','a'=>'No. Generation and export happen locally in your browser.']],
                ['hex-rgb-hsl-color-converter','color-picker-from-image','image-to-base64-converter','css-beautifier']],
            ['PDF Splitter', 'pdf-splitter', 'PDF', 'Extract selected pages or page ranges into a new PDF.', 'PDF Tools',
                'PDF Splitter is a private, browser-based utility for extracting selected pages from a PDF into a new document. Choose a PDF and the tool reads its page count locally, helping you confirm the available range before processing. Enter individual pages or ranges such as 1-3, 5, 8-10, then create a new PDF containing those pages in the order requested. This is useful for separating chapters, sharing only relevant forms, extracting receipts, preparing study material, reducing a document to key pages or creating a smaller attachment. The page selection is validated against the source document, with duplicate pages removed to prevent accidental repetition. After extraction, a result summary shows the source page count and number of selected pages, and the new PDF can be downloaded immediately. PDF processing is performed in your browser with JavaScript; the source file and extracted document are not uploaded to Toolexa or permanently stored. The responsive interface works across desktop and mobile devices and includes clear reset and share controls. For protected, encrypted or damaged PDFs, the tool displays a helpful error instead of sending the file anywhere.',
                ['Read and preview source page count', 'Select individual pages and ranges', 'Extract pages into a new PDF', 'Validate page selections', 'Download the split PDF', 'Local private processing'],
                ['Choose a PDF from your device.', 'Confirm the detected total page count.', 'Enter pages or ranges, for example 1-3, 5, 8.', 'Click Extract Selected Pages.', 'Download the newly created PDF.'],
                [['q'=>'How do I specify pages?','a'=>'Use comma-separated page numbers or ranges, such as 1-3, 5, 8-10.'],['q'=>'Can I extract one page?','a'=>'Yes. Enter a single page number and process the PDF.'],['q'=>'Does the original PDF change?','a'=>'No. The tool creates a separate PDF and leaves the source file untouched.'],['q'=>'Are PDFs uploaded?','a'=>'No. Reading, copying pages and downloading happen locally in your browser.'],['q'=>'Can it split password-protected PDFs?','a'=>'Encrypted or password-protected PDFs may not be readable and can produce an error.']],
                ['pdf-merger','pdf-page-counter','pdf-metadata-viewer','image-to-pdf-converter']],
            ['Text Compare Tool', 'text-compare-tool', 'DIFF', 'Compare two text blocks with line and character difference highlighting.', 'Text Tools',
                'Text Compare Tool provides a clear way to find differences between two blocks of writing, code, configuration, lists or structured notes. Paste the original text on the left and the changed version on the right, then compare them to see a line-by-line result. Removed lines, added lines and unchanged lines use distinct visual styles, while modified line pairs include character-level highlighting so small edits are easier to spot. A compact summary reports line counts, character counts, additions, removals and changed lines. This makes the tool useful for reviewing drafts, checking copied data, comparing code snippets, inspecting settings, proofreading revisions or confirming whether two outputs are identical. The comparison runs entirely with JavaScript in your browser. Toolexa does not upload or permanently store either text block, and the Clear button immediately resets inputs and results. A plain-text version of the comparison can be copied into a ticket, email or document, while the share control shares only the tool link and never your entered content. The responsive layout places editors side by side when space allows and stacks them neatly on phones for comfortable review anywhere.',
                ['Compare two text blocks', 'Line-level additions and removals', 'Character highlighting for changed lines', 'Line and character count summary', 'Copy plain-text result', 'Clear both inputs and output'],
                ['Paste the original text into the first editor.', 'Paste the revised text into the second editor.', 'Click Compare Text.', 'Review the summary and highlighted line differences.', 'Copy the result or clear both editors.'],
                [['q'=>'What differences does the tool show?','a'=>'It highlights added, removed, unchanged and modified lines, with character-level detail for paired changes.'],['q'=>'Can I compare code?','a'=>'Yes. It works with plain text, code, lists, settings and other text-based content.'],['q'=>'Are spaces significant?','a'=>'Yes. Spaces and other characters are included in the comparison.'],['q'=>'Can I copy the comparison?','a'=>'Yes. Copy Result provides a plain-text diff summary.'],['q'=>'Is my text stored?','a'=>'No. Both inputs are compared locally and are not uploaded or permanently stored.']],
                ['word-counter','character-counter','remove-duplicate-lines','text-case-converter']],
        ];

        return array_map(fn ($item) => [
            'name'=>$item[0], 'slug'=>$item[1], 'view'=>'advanced-browser-tool', 'icon'=>$item[2], 'desc'=>$item[3], 'category'=>$item[4],
            'seo_title'=>$item[0].' - Free Online Toolexa Tool', 'seo_description'=>$item[3].' Process everything privately in your browser with no uploads.',
            'keywords'=>Str::lower($item[0]).', free online tool, local browser tool', 'formula'=>null, 'introduction'=>$item[5],
            'features'=>$item[6], 'how_to'=>$item[7], 'faq'=>$item[8], 'related'=>$item[9],
        ], $definitions);
    }

    public static function latestBrowserTools(): array
    {
        $definitions = [
            ['Open Graph Meta Tag Generator','open-graph-meta-tag-generator','OG','Generate complete Open Graph meta tags for social sharing previews.','SEO Tools',
                'Open Graph Meta Tag Generator helps website owners, developers, marketers and content teams create the HTML metadata used by social platforms when a page is shared. Enter a website or page title, a concise description, an image URL, the canonical page URL and the most appropriate content type. The tool instantly assembles clean og:title, og:description, og:image, og:url and og:type tags that can be copied into the head section of an HTML document or Blade layout. It is useful for articles, product pages, portfolios, landing pages, business websites and new projects where social previews need consistent information. A live output area makes it easy to review every value before copying the generated markup. Special HTML characters are safely encoded in attribute values so pasted text does not break the tags. Everything runs locally in your browser with JavaScript; Toolexa does not upload or permanently store the titles, descriptions or URLs you enter. The responsive form works on desktop and mobile, includes practical type choices, provides one-click HTML copying, and clears all fields when you are ready to prepare tags for another page.',
                ['Generate five essential Open Graph tags','Website title and description fields','Image and canonical URL inputs','Website, article and product type selection','Live HTML output','Copy HTML and clear controls'],
                ['Enter the page title and social description.','Add absolute image and website URLs.','Choose the most suitable Open Graph type.','Review the generated HTML tags.','Copy the HTML into your page head.'],
                [['q'=>'What are Open Graph tags?','a'=>'They are HTML meta tags that describe how a page may appear when shared on compatible social platforms.'],['q'=>'Where should I add the generated tags?','a'=>'Place them inside the head element of the relevant HTML page or layout.'],['q'=>'Should image and page URLs be absolute?','a'=>'Yes. Full HTTPS URLs are the most reliable choice for social crawlers.'],['q'=>'Which Open Graph type should I choose?','a'=>'Use website for general pages, article for editorial content, and product for product pages.'],['q'=>'Does Toolexa store my metadata?','a'=>'No. Generation happens locally in your browser and the values are not uploaded.']],
                ['robots-txt-generator','keyword-density-checker','url-slug-generator','html-formatter']],
            ['HTML Entity Encoder & Decoder','html-entity-encoder-decoder','HTML','Encode special HTML characters or decode entities with live conversion.','Developer Tools',
                'HTML Entity Encoder & Decoder is a browser-based developer utility for safely converting special characters into HTML entities and turning encoded markup back into readable text. Choose Encode when text containing ampersands, angle brackets, quotation marks or apostrophes needs to be displayed literally in HTML without being interpreted as markup. Choose Decode when copied content contains named or numeric entities such as &amp;, &lt;, &#39; or Unicode character references. Conversion updates live as you type, which makes the page convenient for code examples, documentation, CMS content, email templates, support messages and debugging escaped API values. The output stays in a separate read-only field so the original input remains available for comparison. Decoding uses the browser’s own HTML parser, while encoding applies safe entity replacements without executing the entered markup. All processing takes place locally with JavaScript. Toolexa does not upload, transmit or permanently store your text. The responsive interface includes a clear mode selector, copy button, character count feedback and a reset control for repeated work. It is designed for quick text snippets and longer blocks that need dependable escaping or readable decoding.',
                ['Encode HTML special characters','Decode named and numeric entities','Live conversion while typing','Separate input and output fields','Copy converted text','Clear both fields'],
                ['Choose Encode or Decode.','Paste or type text into the input editor.','Review the live converted output.','Copy the result when it is ready.','Clear the fields before another conversion.'],
                [['q'=>'Which characters are encoded?','a'=>'The encoder converts ampersands, angle brackets, double quotes and apostrophes to HTML entities.'],['q'=>'Can it decode numeric entities?','a'=>'Yes. Browser parsing supports common decimal, hexadecimal and named HTML entities.'],['q'=>'Does decoding execute scripts?','a'=>'No. The decoded value is written as plain text into a textarea.'],['q'=>'Is conversion automatic?','a'=>'Yes. Output updates when the input or selected mode changes.'],['q'=>'Is my text uploaded?','a'=>'No. Encoding and decoding run locally in your browser.']],
                ['html-formatter','html-to-markdown-converter','base64-encoder-decoder','url-encoder-decoder']],
            ['Time Zone Converter','time-zone-converter','TZ','Convert a date and time between searchable world time zones.','Date & Time Tools',
                'Time Zone Converter makes it easier to coordinate meetings, deadlines, launches, travel plans and remote work across different regions. Choose a source time zone, enter a local date and time, then select a destination zone to see the corresponding time with its date, zone name and UTC offset. The page also displays the current time in both selected zones and refreshes those clocks automatically, helping you compare what is happening now without performing a separate conversion. Searchable time-zone lists use standard IANA names such as Asia/Kolkata, Europe/London and America/New_York, giving clearer daylight-saving behavior than fixed offset lists. The converter uses modern browser internationalization APIs and calculates results entirely on your device. Toolexa does not upload or permanently store the date, time or selected locations. Validation messages explain when a local time cannot be interpreted. The formatted result can be copied into an email, calendar note, chat or project update, while Clear restores a clean starting point. Its responsive layout keeps the controls easy to use on phones, tablets and desktops for quick international scheduling wherever you are.',
                ['Convert between IANA time zones','Searchable source and destination lists','Current clocks in both selected zones','Daylight-saving-aware conversion','Copy formatted result','Clear and swap-friendly workflow'],
                ['Select the source time zone.','Enter the source date and local time.','Select the destination time zone.','Click Convert Time Zone.','Copy the formatted destination result.'],
                [['q'=>'Does it handle daylight saving time?','a'=>'Yes. IANA time-zone rules provided by the browser account for daylight-saving changes.'],['q'=>'What is an IANA time zone?','a'=>'It is a standard regional identifier such as Europe/Paris or America/Chicago.'],['q'=>'Can I see the current time in both zones?','a'=>'Yes. Current clocks for the selected source and destination zones update automatically.'],['q'=>'Why can a local time be invalid?','a'=>'Some local times do not exist or occur twice during daylight-saving transitions.'],['q'=>'Are my selections stored?','a'=>'No. Time conversion happens locally and Toolexa does not permanently store the values.']],
                ['timestamp-converter','age-calculator','url-parser','random-number-generator']],
            ['Favicon Generator','favicon-generator','ICO','Create favicon.ico and a complete multi-size favicon ZIP package.','Image Tools',
                'Favicon Generator converts a logo, symbol or image into the common icon files needed by modern websites and web apps. Upload a PNG, JPG or WebP image and generate square PNG assets at 16x16, 32x32, 48x48, 180x180, 192x192 and 512x512 pixels. The tool previews every generated size so you can check legibility before downloading. It also builds a multi-size favicon.ico file for traditional browser support and a ZIP package containing the ICO plus all required PNG files. This is useful for Laravel applications, static websites, blogs, dashboards, portfolios, progressive web apps and quick brand prototypes. Resizing, ICO assembly and ZIP creation all run locally in your browser with canvas and JavaScript. Your source image and generated files are never uploaded to Toolexa or permanently stored. A square, high-resolution source with comfortable padding generally produces the clearest small icons. The responsive layout keeps previews readable across screen sizes and provides direct ICO and ZIP downloads, a copyable size summary, a share button for the tool link and Clear for starting again with another image.',
                ['Upload PNG, JPG or WebP images','Generate six requested favicon sizes','Preview every generated icon','Create a multi-size favicon.ico','Download a complete ZIP package','Clear generated previews and files'],
                ['Upload a square high-resolution image.','Click Generate Favicons.','Inspect all six preview sizes.','Download favicon.ico or the ZIP package.','Clear the tool before using another image.'],
                [['q'=>'Which sizes are generated?','a'=>'The package includes 16x16, 32x32, 48x48, 180x180, 192x192 and 512x512 PNG files.'],['q'=>'Can I download favicon.ico?','a'=>'Yes. The tool creates a multi-size ICO file for direct download.'],['q'=>'What is included in the ZIP?','a'=>'The ZIP contains favicon.ico and each generated PNG size.'],['q'=>'What source image works best?','a'=>'Use a square, high-resolution image with padding around important details.'],['q'=>'Is my image uploaded?','a'=>'No. Resizing and package creation happen locally in your browser.']],
                ['ico-favicon-generator','image-resizer','image-cropper','png-to-jpg-converter']],
            ['UUID Batch Generator','uuid-batch-generator','UUID','Generate and download up to 1,000 random UUID v4 values.','Developer Tools',
                'UUID Batch Generator creates multiple RFC 4122 version 4 identifiers in one browser-based workflow. Enter any quantity from 1 to 1,000 and generate a clean newline-separated list for database seeds, test fixtures, mock API responses, spreadsheet imports, development samples or temporary identifiers. Every value uses random data from the browser cryptography API and includes the correct UUID version and variant bits. This provides stronger randomness than a basic Math.random implementation while keeping generation quick even at the maximum batch size. A result summary confirms the requested quantity, and the complete list can be copied to the clipboard or downloaded as a plain TXT file for use in editors, scripts and data tools. Everything is generated locally with JavaScript. Toolexa does not upload, log or permanently store the UUIDs or quantity you enter. The responsive interface includes quantity validation, a convenient default, one-click Copy All, download, sharing and clear controls. Generate a fresh batch whenever you need new values; UUID v4 identifiers are designed to have an extremely low chance of collision, although applications should still enforce uniqueness where business rules require it.',
                ['Generate 1 to 1,000 UUID v4 values','Custom batch quantity','Cryptographically strong browser randomness','Copy the complete list','Download output as TXT','Clear quantity and generated values'],
                ['Enter a quantity between 1 and 1,000.','Click Generate UUIDs.','Review the newline-separated UUID v4 list.','Copy all values or download the TXT file.','Clear the tool before generating another batch.'],
                [['q'=>'What is the maximum batch size?','a'=>'You can generate up to 1,000 UUID v4 values at one time.'],['q'=>'Are the UUIDs version 4?','a'=>'Yes. Each value has the correct version 4 and RFC variant bits.'],['q'=>'How are UUIDs randomized?','a'=>'The tool uses crypto.randomUUID when available, with crypto.getRandomValues as a secure fallback.'],['q'=>'What format is the download?','a'=>'The download is a plain UTF-8 TXT file with one UUID per line.'],['q'=>'Does Toolexa store generated UUIDs?','a'=>'No. Values are generated locally and are not uploaded or permanently stored.']],
                ['uuid-generator','uuid-validator','random-string-generator','random-number-generator']],
        ];

        return array_map(fn ($item) => [
            'name'=>$item[0], 'slug'=>$item[1], 'view'=>$item[1] === 'favicon-generator' ? 'local-utility' : 'advanced-browser-tool', 'icon'=>$item[2], 'desc'=>$item[3], 'category'=>$item[4],
            'seo_title'=>str_replace('&', 'and', $item[0]).' - Free Online Toolexa Tool', 'seo_description'=>$item[3].' Free, responsive and processed locally in your browser.',
            'keywords'=>Str::lower($item[0]).', free online tool, browser utility', 'formula'=>null, 'introduction'=>$item[5], 'features'=>$item[6], 'how_to'=>$item[7], 'faq'=>$item[8], 'related'=>$item[9],
        ], $definitions);
    }

    public static function newestBrowserTools(): array
    {
        $definitions = [
            ['XML Sitemap Generator','xml-sitemap-generator','XML','Build and download a standards-friendly XML sitemap from your URLs.','SEO Tools',
                'XML Sitemap Generator provides a simple local workspace for building a sitemap.xml file without writing XML by hand. Add website URLs one at a time, then choose an optional change frequency, priority value and last modified date for each entry. The tool validates absolute HTTP and HTTPS addresses, keeps every row editable and produces a clean urlset document using the standard sitemap namespace. It is useful for new websites, small static projects, landing-page collections, development environments and SEO checks where a focused sitemap is easier to prepare manually than with a crawler. You can add or remove rows, generate the XML whenever values change, copy the complete markup into an editor, or download a ready-to-use sitemap.xml file. All creation and validation happen locally with JavaScript in your browser. Toolexa does not crawl the submitted addresses, upload them or permanently store the sitemap content. The responsive row layout remains practical on phones and desktops, while sensible defaults help you get started quickly. After downloading, place the sitemap at an appropriate public URL and reference it from robots.txt or webmaster tools as required by your site.',
                ['Add and remove URLs manually','Set change frequency per URL','Set priority from 0.0 to 1.0','Add last modified dates','Copy generated XML','Download sitemap.xml'],
                ['Add one row for each absolute website URL.','Choose priority, change frequency and last modified date.','Add or remove rows as needed.','Click Generate Sitemap and review the XML.','Copy the XML or download sitemap.xml.'],
                [['q'=>'Which URL formats are accepted?','a'=>'Use complete HTTP or HTTPS URLs, including the domain name.'],['q'=>'Is change frequency required?','a'=>'No. It is an optional hint and can be omitted from an entry.'],['q'=>'What priority values can I use?','a'=>'Sitemap priority accepts values from 0.0 to 1.0.'],['q'=>'Does the tool crawl my website?','a'=>'No. It only creates XML from the URLs you enter manually.'],['q'=>'Where should sitemap.xml be uploaded?','a'=>'It is commonly placed at the website root, although your server configuration may use another public URL.']],
                ['robots-txt-generator','open-graph-meta-tag-generator','url-slug-generator','keyword-density-checker']],
            ['JSON Minifier','json-minifier','JSON','Minify valid JSON into compact, structure-preserving output.','Developer Tools',
                'JSON Minifier compresses valid JSON by removing indentation, line breaks and unnecessary whitespace while preserving the parsed data structure. Paste an object, array, API response, configuration value, webhook sample or test payload and the tool validates it before producing compact output. Strings, numbers, booleans, null values, nested objects and arrays remain intact because the input is parsed as JSON rather than shortened with unsafe text replacement. Clear error feedback identifies invalid syntax so malformed data is not silently exported. The result can be copied directly into a request, application setting or code editor, or downloaded as a UTF-8 JSON file for later use. This makes the tool helpful when reducing payload size, preparing fixtures, cleaning copied API examples or comparing a readable document with its compact representation. Parsing, minification and file generation happen entirely in your browser. Toolexa does not upload, transmit or permanently store your JSON. The responsive editor uses the same familiar Toolexa developer-tool styling, includes one-click output controls and resets both panels with Clear. For sensitive payloads, local processing means the content stays on the device throughout the workflow.',
                ['Parse and minify valid JSON','Preserve objects, arrays and value types','Report invalid JSON clearly','Copy compact output','Download a .json file','Clear input and result'],
                ['Paste valid JSON into the input editor.','Click Minify JSON.','Correct any syntax error shown by the tool.','Copy the compact result or download JSON.','Clear both editors before another payload.'],
                [['q'=>'Does minifying JSON change its data?','a'=>'No. Valid JSON is parsed and serialized without formatting whitespace while preserving its values and structure.'],['q'=>'What happens with invalid JSON?','a'=>'The tool displays an error and does not generate a misleading result.'],['q'=>'Are spaces inside strings removed?','a'=>'No. Characters inside JSON string values are preserved.'],['q'=>'Can I download the minified result?','a'=>'Yes. The output can be downloaded as a JSON file.'],['q'=>'Is my JSON uploaded?','a'=>'No. Validation, minification and download creation happen locally.']],
                ['json-formatter','json-validator','json-to-xml-converter','csv-to-json-converter']],
            ['CSS Gradient Generator','css-gradient-generator','GRD','Design linear or radial CSS gradients with a live preview.','Color Tools',
                'CSS Gradient Generator is an interactive design utility for creating ready-to-use linear and radial background gradients. Choose the gradient type, select two colors and adjust the angle for linear gradients while watching the preview update immediately. The generated background declaration appears beneath the controls, making it easy to copy into a stylesheet, component, design prototype or inline style. You can also download a small CSS file containing the finished rule for use in a project. This tool is useful for website headers, buttons, cards, hero sections, presentation backgrounds and quick visual experiments where manually editing gradient syntax slows down iteration. Standard browser color pickers provide precise HEX values, while the angle range lets you explore direction without remembering CSS conventions. Radial mode automatically removes the irrelevant angle from the generated syntax and centers the color transition. Every interaction and file download is handled locally with JavaScript. Toolexa does not upload or permanently store your colors or generated CSS. The responsive controls and large preview remain easy to use on desktop, tablet and mobile screens, and the share button shares only the tool page rather than your selected values.',
                ['Linear gradient generation','Radial gradient generation','Interactive angle control','Two browser color pickers','Live responsive preview','Copy or download generated CSS'],
                ['Choose Linear or Radial gradient.','Select the starting and ending colors.','Adjust the angle when using a linear gradient.','Review the live preview and generated CSS.','Copy the declaration or download the CSS file.'],
                [['q'=>'What is a linear gradient?','a'=>'A linear gradient blends colors along a straight line controlled by an angle or direction.'],['q'=>'What is a radial gradient?','a'=>'A radial gradient blends colors outward from a central point.'],['q'=>'Does angle affect radial gradients?','a'=>'No. The angle control applies only to linear gradients.'],['q'=>'Can I use the output in CSS background?','a'=>'Yes. The generated declaration can be pasted directly into a CSS rule.'],['q'=>'Are my selected colors stored?','a'=>'No. Preview and CSS generation run locally in the browser.']],
                ['color-palette-generator','hex-rgb-hsl-color-converter','color-picker-from-image','css-beautifier']],
            ['PNG to SVG Converter','png-to-svg-converter','SVG','Trace simple PNG graphics and icons into downloadable SVG markup.','Image Tools',
                'PNG to SVG Converter creates a vector-style SVG approximation from simple PNG graphics, flat icons, pixel art and small logos. Upload a PNG and the tool reads its pixels locally, reduces the working dimensions when necessary, groups horizontal runs of matching colors and writes those runs as SVG rectangles. The result preserves hard-edged, limited-color artwork particularly well and can be previewed before downloading. It is important to understand the limitation: photographs, gradients, shadows, antialiased illustrations and highly detailed images may create large SVG files and will not become clean editable vector paths. This browser tool is therefore best for simple graphics rather than photographic conversion or professional logo tracing. A color-simplification control lets you reduce channel precision, which can merge visually similar colors and keep the output more manageable. The generated markup is available for copying and can be downloaded as an .svg file. Image reading, tracing, preview and download generation happen completely on your device with canvas and JavaScript. Toolexa never uploads or permanently stores your PNG. The responsive preview and clear limitation notice make it easy to decide whether the traced result is suitable before using it in a website or design workflow.',
                ['Upload PNG images locally','Trace flat graphics into SVG rectangles','Color simplification control','Preview generated SVG','Copy or download SVG','Visible photographic-image limitations'],
                ['Upload a simple PNG icon or flat graphic.','Choose the color simplification level.','Click Convert to SVG.','Review the preview and limitation notice.','Copy the SVG or download the file.'],
                [['q'=>'Does this create editable vector paths?','a'=>'It creates grouped colored SVG rectangles, not smooth Bézier paths used by professional tracing software.'],['q'=>'Which images work best?','a'=>'Small flat icons, pixel art and limited-color graphics with hard edges work best.'],['q'=>'Why are photographs not recommended?','a'=>'Photographs contain many colors and details, producing very large SVGs without useful vector simplification.'],['q'=>'What does color simplification do?','a'=>'It groups nearby channel values to reduce the number of distinct colors and SVG elements.'],['q'=>'Is the PNG uploaded?','a'=>'No. Pixel analysis and SVG generation run locally in your browser.']],
                ['png-to-jpg-converter','image-resizer','image-cropper','image-to-base64-converter']],
            ['Text Sorter','text-sorter','SORT','Sort and clean line-based text with flexible ordering controls.','Text Tools',
                'Text Sorter organizes line-based content into alphabetical, reverse alphabetical or length-based order directly in your browser. Paste names, keywords, URLs, product codes, tasks, tags, file lists or exported spreadsheet values, then choose the ordering that fits your workflow. The Ignore Case option lets uppercase and lowercase values compare naturally without altering the original line text, while Remove Empty Lines cleans blank rows before sorting. Length mode orders shorter entries before longer ones and uses alphabetical comparison when two lines have the same length, producing predictable output. A summary shows the original and resulting line counts so list cleanup is easy to verify. The sorted result can be copied into another application or downloaded as a UTF-8 TXT file. This is useful for SEO lists, inventory preparation, research notes, development fixtures, contact cleanup and general text organization. All sorting, cleanup and download creation happen locally with JavaScript; Toolexa does not upload or permanently store your text. The responsive two-panel editor keeps source and output visible on larger screens and stacks them comfortably on mobile, with Clear available for quickly starting another sorting task.',
                ['Sort lines from A to Z','Sort lines from Z to A','Sort by line length','Optional empty-line removal','Optional case-insensitive comparison','Copy or download sorted text'],
                ['Paste one item per line into the input editor.','Choose A–Z, Z–A or Length sorting.','Enable Ignore Case or Remove Empty Lines if needed.','Click Sort Text and review the summary.','Copy the result or download a TXT file.'],
                [['q'=>'Does Ignore Case change my text?','a'=>'No. It affects comparison only; original capitalization is preserved.'],['q'=>'How are equal-length lines sorted?','a'=>'They use alphabetical comparison as a consistent secondary order.'],['q'=>'Can blank lines be retained?','a'=>'Yes. Leave Remove Empty Lines unchecked to keep them in the sortable list.'],['q'=>'Can I sort URL or keyword lists?','a'=>'Yes. Any plain line-based text can be sorted.'],['q'=>'Is my text uploaded?','a'=>'No. Sorting and TXT generation happen locally in your browser.']],
                ['remove-duplicate-lines','remove-extra-spaces','word-counter','character-counter']],
        ];

        return array_map(fn ($item) => [
            'name'=>$item[0], 'slug'=>$item[1], 'view'=>'advanced-browser-tool', 'icon'=>$item[2], 'desc'=>$item[3], 'category'=>$item[4],
            'seo_title'=>$item[0].' - Free Online Toolexa Tool', 'seo_description'=>$item[3].' Free local browser processing with copy and download options.',
            'keywords'=>Str::lower($item[0]).', free online tool, browser utility', 'formula'=>null, 'introduction'=>$item[5], 'features'=>$item[6], 'how_to'=>$item[7], 'faq'=>$item[8], 'related'=>$item[9],
        ], $definitions);
    }

    public static function browserTools100(): array
    {
        $definitions = [
            ['Image Rotator & Flipper','image-rotator-flipper','ROT','Rotate or flip multiple images with live previews and local downloads.','Image Tools',
                'Image Rotator & Flipper is a private browser-based editor for correcting image orientation or creating mirrored versions without uploading files. Select one or several PNG, JPG or WebP images, then rotate the complete batch by 90, 180 or 270 degrees, flip it horizontally, or flip it vertically. Every transformation is applied from the original file so repeated choices remain predictable instead of gradually reducing quality through multiple re-encodes. Individual preview cards show the transformed result and provide a dedicated download button, making batch work manageable while still letting you save only the files you need. This is useful for phone photos captured sideways, scanned pages, product images, social media assets, diagrams and quick visual corrections before publishing. Canvas processing happens entirely in your browser. Toolexa does not upload, transmit or permanently store your images or generated downloads. Output keeps PNG sources as PNG and exports other supported sources as high-quality JPEG files. The responsive preview grid adapts to desktop and mobile screens, while Clear removes the selected files and generated canvases. For very large batches, process a reasonable number at a time to stay within your device’s available memory.',
                ['Rotate images by 90, 180 or 270 degrees','Flip horizontally or vertically','Process multiple images together','Preview every transformed image','Download individual results','Local canvas processing with clear control'],
                ['Choose one or more PNG, JPG or WebP images.','Select a rotation or flip operation.','Click Apply to Batch.','Review each transformed preview.','Download the required images or clear the batch.'],
                [['q'=>'Can I process multiple images?','a'=>'Yes. Select multiple supported images and apply the same transformation to the batch.'],['q'=>'Which rotations are available?','a'=>'You can rotate clockwise by 90, 180 or 270 degrees.'],['q'=>'Do flips alter the original file?','a'=>'No. The original remains untouched and a transformed download is created.'],['q'=>'Which image formats are supported?','a'=>'The tool accepts PNG, JPEG and WebP images supported by the browser.'],['q'=>'Are images uploaded?','a'=>'No. Loading, transformation and downloads happen locally.']],
                ['image-resizer','image-cropper','image-compressor','png-to-svg-converter']],
            ['Regex Tester','regex-tester','REGEX','Test regular expressions with flags, highlighted matches and examples.','Developer Tools',
                'Regex Tester is an interactive developer utility for checking regular expressions against sample text directly in the browser. Enter a pattern without surrounding slashes, choose common flags such as global, case-insensitive, multiline or dot-all, and inspect every match in a highlighted preview. The result summary shows match count, matched text and character positions, while capturing groups are included when the expression defines them. Built-in examples for email addresses, URLs, numbers and repeated whitespace provide quick starting points for common validation and search tasks. Invalid patterns display a clear JavaScript error instead of failing silently, which makes experimentation and debugging easier. The plain-text match report can be copied into notes, tickets, documentation or test cases. This tool is useful for developers, QA testers, students, analysts and anyone preparing find-and-replace rules. All patterns and sample content stay on your device and are evaluated locally using the browser’s JavaScript RegExp engine. Toolexa does not upload or permanently store the expression or text. The responsive editor and preview are designed for both short checks and larger multi-line samples, with Clear available before beginning another test.',
                ['Enter JavaScript regular expressions','Select common regex flags','Highlight every match safely','Show groups and match positions','Load common regex examples','Copy results and clear inputs'],
                ['Enter a regular expression without slash delimiters.','Select the flags required for the test.','Paste or type sample text.','Click Test Regex and inspect highlighted matches.','Copy the report or clear the tester.'],
                [['q'=>'Should I include slash delimiters?','a'=>'No. Enter only the pattern; choose flags separately.'],['q'=>'Which regex engine is used?','a'=>'The tool uses the JavaScript RegExp engine provided by your browser.'],['q'=>'Can it display capturing groups?','a'=>'Yes. Captured group values appear in the match report.'],['q'=>'Why can matches differ from another language?','a'=>'Regular-expression syntax and features vary between JavaScript, PHP, Python and other engines.'],['q'=>'Is sample text uploaded?','a'=>'No. Testing and highlighting happen locally in your browser.']],
                ['json-validator','uuid-validator','html-formatter','text-compare-tool']],
            ['UUID Decoder & Inspector','uuid-decoder-inspector','UUID','Validate a UUID and inspect its version, variant and field structure.','Developer Tools',
                'UUID Decoder & Inspector helps developers validate and understand universally unique identifiers without sending them to a server. Paste a hyphenated UUID and the tool checks its canonical hexadecimal structure, identifies the version nibble, determines the RFC variant from the relevant high bits and separates the value into time-low, time-mid, time-high/version, clock sequence and node fields. A readable explanation clarifies which portions are structural metadata and which interpretation depends on the UUID version. For version 1 identifiers, the inspector also reconstructs the embedded Gregorian timestamp when it is representable, while version 4 values are described as random identifiers. This is useful when debugging database keys, API payloads, log entries, fixtures, distributed systems and integration data. The formatted inspection report can be copied into documentation or a support ticket. Validation and decoding run entirely with JavaScript in your browser. Toolexa does not upload or permanently store the UUID you inspect. The interface accepts upper- or lowercase hexadecimal input, normalizes valid values for reporting, and presents errors clearly. Remember that decoding a UUID does not reveal private user information unless an older UUID version itself embeds metadata such as time or node-related values.',
                ['Validate canonical UUID strings','Detect UUID versions 1 through 8','Extract the UUID variant','Explain the five UUID fields','Decode version 1 timestamp when available','Copy the complete inspection report'],
                ['Paste a canonical hyphenated UUID.','Click Inspect UUID.','Review validity, version and variant.','Inspect the separated UUID fields and explanation.','Copy the result when needed.'],
                [['q'=>'Which UUID versions can be detected?','a'=>'The version nibble is reported for valid UUIDs, including commonly used versions 1, 3, 4, 5, 6, 7 and 8.'],['q'=>'What is a UUID variant?','a'=>'The variant identifies the UUID layout family based on the high bits of the clock sequence field.'],['q'=>'Can a UUID reveal its creation time?','a'=>'Version 1 UUIDs contain a timestamp; random version 4 UUIDs do not.'],['q'=>'Does inspection identify the owner?','a'=>'No. The tool explains structural fields and does not look up external records.'],['q'=>'Is the UUID uploaded?','a'=>'No. Validation and inspection run locally.']],
                ['uuid-validator','uuid-generator','uuid-batch-generator','random-string-generator']],
            ['Reading Time Calculator','reading-time-calculator','READ','Calculate reading and speaking time using adjustable reading speed.','Utility Tools',
                'Reading Time Calculator estimates how long written content takes to read and speak using the pace you select. Paste an article, blog draft, speech, script, lesson, product description or documentation section and the tool counts words and characters immediately. Choose a reading speed suited to the audience, from careful reading to faster scanning, or enter a practical custom words-per-minute value through the available selector. The result shows estimated reading time alongside a speaking-time estimate based on a clear presentation pace. This is useful for writers planning article length, educators preparing lessons, marketers reviewing landing pages, creators timing videos and podcasts, and speakers checking scripts before a presentation. Short results are shown in seconds while longer content is expressed in minutes and seconds for easier planning. The copyable summary can be shared with an editor or added to a content brief. Everything is calculated locally in your browser using the text you provide. Toolexa does not upload or permanently store the content. The responsive layout updates live as you type or change speed, and Clear resets the editor and metrics before another calculation.',
                ['Calculate adjustable reading time','Count words and characters','Choose multiple reading speeds','Estimate speaking duration','Live result updates','Copy results and clear text'],
                ['Paste or type the content to analyze.','Choose a reading speed in words per minute.','Review word and character counts.','Compare estimated reading and speaking times.','Copy the summary or clear the text.'],
                [['q'=>'How is reading time calculated?','a'=>'Word count is divided by the selected words-per-minute reading speed.'],['q'=>'What speaking speed is used?','a'=>'Speaking time uses a practical presentation pace of approximately 130 words per minute.'],['q'=>'Does punctuation count as a word?','a'=>'Words are detected from non-whitespace text segments, so punctuation attached to a word does not add another word.'],['q'=>'Can I change reading speed?','a'=>'Yes. Select a slower or faster words-per-minute option.'],['q'=>'Is my content stored?','a'=>'No. Text analysis happens locally and is not permanently stored.']],
                ['word-counter','character-counter','text-sorter','text-case-converter']],
            ['Screen Resolution Checker','screen-resolution-checker','SCREEN','Inspect screen, viewport, window, pixel ratio and browser display details.','Web Tools',
                'Screen Resolution Checker displays the browser and display measurements that affect responsive website layouts. Open the page to see the physical CSS-pixel screen resolution, available screen area, viewport dimensions, outer browser-window size, device pixel ratio, color depth, pixel depth, orientation and user agent reported by the browser. Values update when the window is resized or the device orientation changes, making the tool useful for frontend testing, screenshot planning, breakpoint debugging, support conversations and checking how browser chrome affects the usable page area. Device pixel ratio helps explain why high-density screens may contain more hardware pixels than their CSS dimensions suggest, while viewport size shows the area available to the current document. A copyable report bundles the detected details for bug tickets, QA notes or developer discussions. All inspection uses standard browser APIs locally; Toolexa does not upload or permanently store the device information displayed by the tool. Some browsers may reduce or generalize user-agent details for privacy, and zoom settings can influence reported CSS-pixel measurements. The responsive result cards update automatically and require no form submission, with Refresh available whenever you want to capture the latest values.',
                ['Show screen and available resolution','Report viewport and browser window size','Display device pixel ratio','Show color and pixel depth','Display orientation and user agent','Copy a refreshed device report'],
                ['Open the tool in the browser you want to inspect.','Review screen and available dimensions.','Compare viewport and outer window sizes.','Check DPR, color depth and user agent.','Resize if needed, then copy the refreshed report.'],
                [['q'=>'What is the difference between screen and viewport size?','a'=>'Screen size describes the display in CSS pixels, while viewport size is the area available to the webpage.'],['q'=>'What is device pixel ratio?','a'=>'DPR is the ratio between device pixels and CSS pixels reported by the browser.'],['q'=>'Why does resolution differ from advertised hardware pixels?','a'=>'Browser scaling, operating-system scaling and DPR affect CSS-pixel measurements.'],['q'=>'Does browser zoom affect results?','a'=>'Zoom can affect viewport and CSS-pixel values in many browsers.'],['q'=>'Is device information uploaded?','a'=>'No. The values are read and displayed locally.']],
                ['timestamp-converter','image-resizer','url-parser','browser-utility']],
        ];

        return array_map(fn ($item) => [
            'name'=>$item[0], 'slug'=>$item[1], 'view'=>'advanced-browser-tool', 'icon'=>$item[2], 'desc'=>$item[3], 'category'=>$item[4],
            'seo_title'=>str_replace('&','and',$item[0]).' - Free Online Toolexa Tool', 'seo_description'=>$item[3].' Free and processed privately in your browser.',
            'keywords'=>Str::lower($item[0]).', free online tool, browser utility', 'formula'=>null, 'introduction'=>$item[5], 'features'=>$item[6], 'how_to'=>$item[7], 'faq'=>$item[8], 'related'=>$item[9],
        ], $definitions);
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
            self::developerTool(
                'JSON Formatter',
                'json-formatter',
                'JSON',
                'Beautify, minify and validate JSON locally in your browser.',
                'JSON Formatter is a free developer tool for cleaning, validating and compressing JSON without uploading your data. Paste a JSON object, array, API response, configuration snippet or test payload into the editor, then choose Beautify JSON to create readable indentation or Minify JSON to remove extra spaces for compact storage and transport. The same page can validate JSON and show a clear error message with an approximate line number when parsing fails. This makes it useful for debugging API responses, preparing documentation examples, checking webhook payloads, reviewing app settings and cleaning copied data before sharing it with teammates. All processing happens in your browser with JavaScript, so Toolexa does not permanently store the JSON you enter. The output can be copied or downloaded as a .json file, and Clear resets both panels for another payload. The layout follows Toolexa’s standard responsive tool design, so it is comfortable on desktop screens while still usable on mobile when you need a quick formatting check.',
                [
                    'Beautify JSON with readable indentation',
                    'Minify JSON for compact output',
                    'Validate JSON before using it',
                    'Show error line number for invalid JSON',
                    'Copy formatted or minified output',
                    'Download output as a .json file',
                    'Clear input and output instantly',
                ],
                [
                    'Paste JSON into the input editor.',
                    'Click Beautify JSON, Minify JSON or Validate JSON.',
                    'Review the formatted output or validation message.',
                    'Use Copy Result or Download .json when you need the output.',
                    'Click Clear before working with another JSON payload.',
                ],
                [
                    ['q' => 'Does JSON Formatter upload my JSON?', 'a' => 'No, formatting and validation run locally in your browser.'],
                    ['q' => 'Can it minify JSON?', 'a' => 'Yes, click Minify JSON to remove unnecessary whitespace.'],
                    ['q' => 'Will it show JSON errors?', 'a' => 'Yes, invalid JSON displays a parse message and approximate line number.'],
                    ['q' => 'Can I download the formatted JSON?', 'a' => 'Yes, use Download .json after generating valid output.'],
                    ['q' => 'What JSON inputs are supported?', 'a' => 'Objects, arrays, strings, numbers, booleans and null are supported when they follow valid JSON syntax.'],
                ],
                ['json-validator', 'json-to-xml-converter', 'xml-to-json-converter', 'html-formatter', 'base64-encoder', 'md5-hash-generator']
            ),
            self::developerTool(
                'JSON Validator',
                'json-validator',
                'JVAL',
                'Validate JSON and find syntax errors with line numbers.',
                'JSON Validator is a focused browser tool for checking whether JSON text is valid before you paste it into code, an API client, a database field or a configuration file. Add your JSON payload to the editor and click Validate JSON. The tool parses it locally, confirms valid JSON when the structure is correct, and reports syntax problems when parsing fails. It also estimates the error line from the parser position when available, helping you jump to the likely issue faster. This is especially helpful for missing commas, trailing commas, unquoted keys, unmatched braces, broken arrays and copied API responses that look correct at first glance. Toolexa does not upload or permanently store your input; validation happens in the browser using JavaScript. The result summary is copyable, and Clear resets the page for another payload. JSON Validator is intentionally simple, making it a good quick check for developers, QA testers, students, support teams and anyone who needs confidence that a JSON snippet is safe to use.',
                [
                    'Validate JSON syntax locally',
                    'Highlight validation errors in the result panel',
                    'Show approximate error line number',
                    'Display valid JSON summary',
                    'Copy validation result',
                    'Clear input and result quickly',
                ],
                [
                    'Paste JSON into the input editor.',
                    'Click Validate JSON.',
                    'Read the validation status and line number if an error is found.',
                    'Copy the result summary if needed.',
                    'Use Clear before checking another JSON snippet.',
                ],
                [
                    ['q' => 'What does JSON Validator check?', 'a' => 'It checks whether the input can be parsed as valid JSON.'],
                    ['q' => 'Does it show the exact error line?', 'a' => 'It shows an approximate line number when the browser parser provides an error position.'],
                    ['q' => 'Can it fix JSON automatically?', 'a' => 'No, this tool validates and reports errors; use JSON Formatter after fixing syntax.'],
                    ['q' => 'Are trailing commas allowed?', 'a' => 'No, standard JSON does not allow trailing commas.'],
                    ['q' => 'Is the JSON stored by Toolexa?', 'a' => 'No, validation runs locally and the page does not permanently store your input.'],
                ],
                ['json-formatter', 'json-to-xml-converter', 'xml-to-json-converter', 'base64-decoder', 'url-encoder-decoder', 'uuid-validator']
            ),
            self::developerTool(
                'JSON to XML Converter',
                'json-to-xml-converter',
                'J2X',
                'Convert JSON objects and arrays into readable XML.',
                'JSON to XML Converter helps developers transform JSON data into XML directly in the browser. Paste a valid JSON object, array, API response, sample payload or configuration snippet, then convert it into structured XML with readable indentation. Object keys become XML tags, arrays create repeated item nodes, and primitive values are escaped so the output remains valid XML text. This is useful when comparing API formats, preparing integration samples, documenting payload changes, testing legacy XML systems or moving data between tools that expect different formats. The converter validates the JSON before conversion and shows a clear error message if the input cannot be parsed. All conversion work happens locally with JavaScript; Toolexa does not upload or permanently store the JSON you enter. The XML output can be copied into an editor, API client or documentation page, and it can also be downloaded as an XML file. The page uses the same responsive Toolexa tool layout with clear actions, result feedback and quick reset controls.',
                [
                    'Convert JSON to XML',
                    'Validate JSON before conversion',
                    'Format XML with indentation',
                    'Escape XML special characters',
                    'Copy XML output',
                    'Download XML file',
                    'Clear editor and result',
                ],
                [
                    'Paste valid JSON into the input editor.',
                    'Click Convert JSON to XML.',
                    'Review the generated XML output.',
                    'Copy the XML or download it as a file.',
                    'Click Clear before converting another payload.',
                ],
                [
                    ['q' => 'Can arrays be converted to XML?', 'a' => 'Yes, arrays are converted into repeated item nodes.'],
                    ['q' => 'What happens if JSON is invalid?', 'a' => 'The tool shows a validation error instead of generating XML.'],
                    ['q' => 'Does the converter upload JSON?', 'a' => 'No, conversion runs locally in your browser.'],
                    ['q' => 'Can I download the XML?', 'a' => 'Yes, use Download XML after conversion.'],
                    ['q' => 'How are special XML characters handled?', 'a' => 'Characters such as ampersands and angle brackets are escaped in text values.'],
                ],
                ['xml-to-json-converter', 'json-formatter', 'json-validator', 'html-formatter', 'base64-encoder', 'url-encoder-decoder']
            ),
            self::developerTool(
                'XML to JSON Converter',
                'xml-to-json-converter',
                'X2J',
                'Validate XML and convert it into JSON locally.',
                'XML to JSON Converter is a practical browser tool for turning XML documents into JSON objects without sending the content to a server. Paste XML from an API response, legacy feed, configuration file, sitemap sample or integration document, then convert it into readable JSON. The tool validates XML with the browser parser first and reports parsing errors when the markup is broken. During conversion, attributes are grouped under @attributes, repeated child elements become arrays, text-only nodes become string values, and mixed content is represented with a #text field where needed. This gives a clear JSON structure that is useful for debugging, prototyping, migration notes and documentation. All parsing and conversion run locally in JavaScript, so Toolexa does not upload or permanently store the XML you paste. You can copy the JSON result or download it as a .json file. The interface also includes a dedicated Validate XML action and a Clear button for quick repeated checks on desktop, tablet or mobile.',
                [
                    'Validate XML syntax',
                    'Convert XML to JSON',
                    'Preserve XML attributes in JSON',
                    'Handle repeated XML elements as arrays',
                    'Copy JSON output',
                    'Download JSON file',
                    'Clear input and output',
                ],
                [
                    'Paste XML into the input editor.',
                    'Click Validate XML if you want to check markup first.',
                    'Click Convert XML to JSON.',
                    'Review the generated JSON structure.',
                    'Copy or download the JSON output when ready.',
                ],
                [
                    ['q' => 'Does XML validation happen before conversion?', 'a' => 'Yes, invalid XML is reported before JSON output is generated.'],
                    ['q' => 'How are XML attributes represented?', 'a' => 'Attributes are stored in an @attributes object.'],
                    ['q' => 'How are repeated tags handled?', 'a' => 'Repeated child elements become arrays in the JSON output.'],
                    ['q' => 'Can I download the converted JSON?', 'a' => 'Yes, use Download JSON after conversion.'],
                    ['q' => 'Is my XML uploaded?', 'a' => 'No, XML parsing and conversion run locally in your browser.'],
                ],
                ['json-to-xml-converter', 'json-formatter', 'json-validator', 'html-formatter', 'base64-decoder', 'uuid-validator']
            ),
            self::developerTool(
                'HTML Formatter',
                'html-formatter',
                'HTML',
                'Beautify and minify HTML snippets in your browser.',
                'HTML Formatter is a free developer tool for making HTML easier to read or smaller to paste into templates, emails, demos and documentation. Add an HTML snippet, page fragment, copied markup, component output or email block into the editor, then choose Beautify HTML for readable indentation or Minify HTML for compact output. Beautify mode uses the browser DOM parser to normalize markup and then formats nested elements with indentation. Minify mode removes comments and unnecessary whitespace between tags while keeping text content usable. This is helpful when reviewing generated HTML, cleaning copied code, preparing examples, comparing component output or quickly shrinking a snippet before sharing it. Processing happens locally in your browser with JavaScript, and Toolexa does not upload or permanently store your HTML. The output can be copied or downloaded as an HTML file, and Clear resets the editor. The page follows the existing Toolexa responsive tool layout with SEO content, FAQs, related tools and simple action buttons.',
                [
                    'Beautify HTML with indentation',
                    'Minify HTML snippets',
                    'Copy formatted output',
                    'Download HTML output',
                    'Clear editor and result',
                    'Local browser processing',
                ],
                [
                    'Paste HTML into the input editor.',
                    'Click Beautify HTML or Minify HTML.',
                    'Review the generated output.',
                    'Copy the result or download it as an HTML file.',
                    'Use Clear before formatting another snippet.',
                ],
                [
                    ['q' => 'Does HTML Formatter upload my markup?', 'a' => 'No, formatting runs locally in your browser.'],
                    ['q' => 'Can it minify HTML?', 'a' => 'Yes, Minify HTML removes comments and extra spacing between tags.'],
                    ['q' => 'Will beautify mode fix all invalid HTML?', 'a' => 'The browser may normalize some markup, but you should still review important output.'],
                    ['q' => 'Can I download the output?', 'a' => 'Yes, use Download after formatting or minifying HTML.'],
                    ['q' => 'Is it suitable for full pages?', 'a' => 'It works best for snippets and moderate HTML documents used in everyday development.'],
                ],
                ['json-formatter', 'json-validator', 'json-to-xml-converter', 'xml-to-json-converter', 'url-encoder-decoder', 'base64-encoder']
            ),
            self::developerTool(
                'CSS Minifier',
                'css-minifier',
                'CSS',
                'Minify CSS by removing comments and unnecessary whitespace locally.',
                'CSS Minifier is a browser-based developer tool for compressing stylesheet code into a smaller, cleaner output. Paste CSS from a component, theme file, landing page, email template or quick experiment, then minify it with one click. The tool removes CSS comments, trims unnecessary whitespace, tightens punctuation around braces and separators, and keeps the result ready to copy or download as a .css file. This is useful when preparing snippets for production, reducing inline CSS size, cleaning copied examples, sharing compact code in documentation or quickly testing how much space can be saved without opening a build tool. Processing runs entirely in your browser with JavaScript, so Toolexa does not upload, inspect or permanently store the CSS you enter. The page follows the same responsive Toolexa tool layout with a clear input editor, result panel, copy button, download control and reset action. Developers, designers and students can use it for quick CSS cleanup whenever a full bundler is unnecessary.',
                [
                    'Minify CSS locally in the browser',
                    'Remove CSS comments',
                    'Remove unnecessary whitespace',
                    'Copy minified CSS output',
                    'Download output as a .css file',
                    'Clear input and output quickly',
                ],
                [
                    'Paste CSS into the input editor.',
                    'Click Minify CSS.',
                    'Review the compressed CSS output.',
                    'Copy the result or download it as a .css file.',
                    'Use Clear before minifying another stylesheet.',
                ],
                [
                    ['q' => 'Does CSS Minifier upload my stylesheet?', 'a' => 'No, minification runs locally in your browser.'],
                    ['q' => 'Does it remove CSS comments?', 'a' => 'Yes, standard block comments are removed during minification.'],
                    ['q' => 'Can I download the minified CSS?', 'a' => 'Yes, use Download .css after generating output.'],
                    ['q' => 'Will it optimize every CSS rule?', 'a' => 'It focuses on comments and whitespace, not advanced semantic optimization.'],
                    ['q' => 'Is it safe for quick snippets?', 'a' => 'Yes, it is designed for common CSS snippets and moderate stylesheet text.'],
                ],
                ['css-beautifier', 'html-formatter', 'html-to-markdown-converter', 'json-formatter', 'base64-encoder-decoder', 'url-encoder-decoder']
            ),
            self::developerTool(
                'CSS Beautifier',
                'css-beautifier',
                'CSS',
                'Format CSS with proper indentation and readable spacing.',
                'CSS Beautifier is a free developer utility for turning compact or messy stylesheet code into readable CSS. Paste minified CSS, copied browser output, theme snippets, component styles or quick notes, then format the code with consistent indentation and line breaks. The beautifier expands braces and declarations onto separate lines, trims crowded punctuation, and gives selectors and properties enough spacing to review changes more comfortably. It is useful when debugging design issues, reading third-party snippets, preparing documentation examples, teaching CSS, comparing before-and-after code or cleaning styles before editing them in a project. Everything runs locally in the browser with JavaScript, and Toolexa does not upload or permanently store your CSS. The output appears in the standard Toolexa result panel where it can be copied or downloaded. A Clear button resets the editor for the next stylesheet. The tool is intentionally lightweight, responsive and practical for everyday front-end work when opening a full IDE formatter would slow down a quick task.',
                [
                    'Format CSS into readable code',
                    'Apply proper indentation',
                    'Add clear line breaks around rules',
                    'Copy beautified CSS output',
                    'Download formatted CSS',
                    'Clear editor and result',
                ],
                [
                    'Paste CSS into the input editor.',
                    'Click Format CSS.',
                    'Review the beautified stylesheet.',
                    'Copy the output or download it.',
                    'Click Clear to reset the page.',
                ],
                [
                    ['q' => 'What does CSS Beautifier do?', 'a' => 'It formats CSS with indentation, spacing and readable line breaks.'],
                    ['q' => 'Can it beautify minified CSS?', 'a' => 'Yes, paste minified CSS and click Format CSS.'],
                    ['q' => 'Does formatting happen on the server?', 'a' => 'No, formatting runs locally in your browser.'],
                    ['q' => 'Can I download formatted CSS?', 'a' => 'Yes, use the Download button after formatting.'],
                    ['q' => 'Will it change CSS behavior?', 'a' => 'It is designed to change formatting only, but you should review important output before production use.'],
                ],
                ['css-minifier', 'html-formatter', 'json-formatter', 'html-to-markdown-converter', 'markdown-to-html-converter', 'base64-encoder-decoder']
            ),
            self::developerTool(
                'HTML to Markdown Converter',
                'html-to-markdown-converter',
                'H2M',
                'Convert HTML snippets into Markdown locally in your browser.',
                'HTML to Markdown Converter helps turn basic HTML into clean Markdown without sending your content anywhere. Paste headings, paragraphs, links, lists, blockquotes, code blocks or simple article markup, then convert it into Markdown that is easier to edit in docs, README files, CMS drafts, issue trackers and static-site workflows. The converter reads the HTML with the browser parser and maps common elements such as h1 to h6, p, strong, em, a, img, ul, ol, li, blockquote, pre and code into Markdown syntax. This is helpful when migrating copied web content, cleaning documentation snippets, preparing developer notes or converting small formatted sections into plain text-friendly Markdown. Processing happens locally with JavaScript, and Toolexa does not upload or permanently store the HTML you enter. The Markdown output can be copied or downloaded as a .md file. The page follows the same responsive Toolexa structure with a clear editor, result panel, copy action, download button, related tools, FAQs and supporting SEO content.',
                [
                    'Convert HTML to Markdown',
                    'Handle headings, links and lists',
                    'Convert bold, italic and code elements',
                    'Copy Markdown output',
                    'Download output as a .md file',
                    'Clear input and output',
                ],
                [
                    'Paste HTML into the input editor.',
                    'Click Convert HTML to Markdown.',
                    'Review the Markdown output.',
                    'Copy the Markdown or download it as a .md file.',
                    'Use Clear before converting another snippet.',
                ],
                [
                    ['q' => 'Which HTML elements are supported?', 'a' => 'Common headings, paragraphs, links, lists, images, quotes and code elements are supported.'],
                    ['q' => 'Does it upload my HTML?', 'a' => 'No, conversion runs locally in your browser.'],
                    ['q' => 'Can I download Markdown?', 'a' => 'Yes, use Download .md after conversion.'],
                    ['q' => 'Is it suitable for full websites?', 'a' => 'It is best for snippets, articles and moderate HTML content.'],
                    ['q' => 'Will styling be converted?', 'a' => 'No, Markdown focuses on content structure, not CSS styling.'],
                ],
                ['markdown-to-html-converter', 'html-formatter', 'css-beautifier', 'json-formatter', 'base64-encoder-decoder', 'url-encoder-decoder']
            ),
            self::developerTool(
                'Markdown to HTML Converter',
                'markdown-to-html-converter',
                'M2H',
                'Convert Markdown to HTML with a live preview.',
                'Markdown to HTML Converter is a local browser tool for turning Markdown notes into HTML output with an instant preview. Paste Markdown from a README draft, documentation note, changelog, blog outline, issue comment or quick content block, then convert it into HTML that can be copied or downloaded. The converter supports common Markdown patterns such as headings, paragraphs, bold, italic, inline code, fenced code blocks, blockquotes, unordered lists, ordered lists, links, images and horizontal rules. A live preview appears below the result so you can quickly check how the converted content will read before using it elsewhere. All processing happens in your browser with JavaScript, and Toolexa does not upload or permanently store your Markdown. The output panel gives copy and download controls, while Clear resets the editor for the next document. The tool is practical for developers, writers, students and content teams who need a quick Markdown-to-HTML bridge without opening a full static site generator or editor extension.',
                [
                    'Convert Markdown to HTML',
                    'Show live HTML preview',
                    'Support common Markdown syntax',
                    'Copy generated HTML',
                    'Download HTML output',
                    'Clear editor and preview',
                ],
                [
                    'Paste Markdown into the input editor.',
                    'Click Convert Markdown to HTML.',
                    'Review the generated HTML and live preview.',
                    'Copy the HTML or download it.',
                    'Use Clear to reset the converter.',
                ],
                [
                    ['q' => 'Does Markdown conversion happen locally?', 'a' => 'Yes, Markdown is converted in your browser.'],
                    ['q' => 'Does this tool show a preview?', 'a' => 'Yes, the converted HTML is rendered in a live preview area.'],
                    ['q' => 'Can I download the HTML?', 'a' => 'Yes, use Download HTML after conversion.'],
                    ['q' => 'Which Markdown syntax is supported?', 'a' => 'Common headings, lists, links, images, quotes, code and emphasis are supported.'],
                    ['q' => 'Is my Markdown stored?', 'a' => 'No, Toolexa does not permanently store the content you enter.'],
                ],
                ['html-to-markdown-converter', 'html-formatter', 'css-minifier', 'json-formatter', 'base64-encoder-decoder', 'url-encoder-decoder']
            ),
            self::developerTool(
                'Base64 Encoder & Decoder',
                'base64-encoder-decoder',
                'B64',
                'Encode text to Base64 or decode Base64 back to text.',
                'Base64 Encoder & Decoder combines two common developer tasks on one browser-based page. Paste plain text and encode it into Base64, or paste an encoded string and decode it back into readable text. The decoder checks invalid Base64 input and shows a clear error instead of producing confusing output. This is useful when working with API samples, authorization examples, headers, configuration values, small payloads, data snippets, documentation and debugging notes. Base64 is encoding rather than encryption, so the tool is best used for representation and testing rather than protecting secrets. All processing happens locally in your browser with JavaScript, and Toolexa does not upload or permanently store the text you enter. The result can be copied or downloaded as a text file, and the Clear button resets the page for another value. The interface follows the same Toolexa developer-tool layout with responsive editors, status messages, FAQs, related tools, breadcrumbs, schema markup and shareable page metadata.',
                [
                    'Encode text to Base64',
                    'Decode Base64 to text',
                    'Detect invalid Base64 input',
                    'Copy output',
                    'Download result',
                    'Clear input and output',
                ],
                [
                    'Paste plain text or Base64 into the input editor.',
                    'Click Encode Base64 or Decode Base64.',
                    'Review the output or validation error.',
                    'Copy the result or download it.',
                    'Use Clear before processing another value.',
                ],
                [
                    ['q' => 'Can this tool both encode and decode Base64?', 'a' => 'Yes, it includes separate encode and decode actions.'],
                    ['q' => 'Does it detect invalid Base64?', 'a' => 'Yes, invalid Base64 input shows a validation error.'],
                    ['q' => 'Is Base64 encryption?', 'a' => 'No, Base64 is encoding and should not be used to secure secrets.'],
                    ['q' => 'Can I download the result?', 'a' => 'Yes, use the Download button after generating output.'],
                    ['q' => 'Does Toolexa store my text?', 'a' => 'No, processing runs locally in your browser.'],
                ],
                ['base64-encoder', 'base64-decoder', 'json-formatter', 'url-encoder-decoder', 'css-minifier', 'html-formatter']
            ),
            self::developerTool(
                'SQL Formatter',
                'sql-formatter',
                'SQL',
                'Beautify and minify SQL queries locally in your browser.',
                'SQL Formatter is a browser-based developer tool for making SQL queries easier to read or smaller to share. Paste a SELECT, INSERT, UPDATE, DELETE, JOIN, WHERE, GROUP BY or ORDER BY query into the editor, then choose Beautify SQL to add line breaks and indentation, or Minify SQL to remove comments and unnecessary whitespace. The tool is useful when reviewing copied database queries, preparing examples for documentation, cleaning logs, formatting snippets from dashboards or making a long query easier to debug before sending it to a teammate. Processing happens locally with JavaScript, so Toolexa does not upload, execute or permanently store any SQL you enter. The output can be copied or downloaded as a SQL file, and Clear resets both editors. This tool focuses on readable formatting for common SQL syntax rather than database-specific validation, making it a fast helper for developers, analysts, students and support teams working with query text.',
                [
                    'Beautify SQL queries',
                    'Minify SQL output',
                    'Remove SQL comments during minify',
                    'Copy formatted SQL',
                    'Download SQL output',
                    'Clear editor and result',
                ],
                [
                    'Paste SQL into the input editor.',
                    'Click Beautify SQL or Minify SQL.',
                    'Review the generated SQL output.',
                    'Copy the result or download it.',
                    'Use Clear before formatting another query.',
                ],
                [
                    ['q' => 'Does SQL Formatter run my query?', 'a' => 'No, it only formats text locally in your browser.'],
                    ['q' => 'Can it minify SQL?', 'a' => 'Yes, Minify SQL removes comments and unnecessary whitespace.'],
                    ['q' => 'Does it validate database syntax?', 'a' => 'No, it is a formatter and minifier, not a database validator.'],
                    ['q' => 'Can I download the formatted query?', 'a' => 'Yes, use the Download button after generating output.'],
                    ['q' => 'Is my SQL stored by Toolexa?', 'a' => 'No, Toolexa does not permanently store the SQL you enter.'],
                ],
                ['json-formatter', 'css-beautifier', 'html-formatter', 'base64-encoder-decoder', 'url-encoder-decoder', 'md5-hash-generator']
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

    public static function localTools(): array
    {
        return [
            self::localTool(
                'HEX ↔ RGB ↔ HSL Color Converter',
                'hex-rgb-hsl-color-converter',
                'CLR',
                'Convert HEX, RGB and HSL color values with a live preview.',
                'HEX ↔ RGB ↔ HSL Color Converter is a browser-based color utility for designers, developers and content creators who need fast color format conversion. Enter a HEX value to get RGB and HSL equivalents, update RGB values to generate HEX and HSL, or enter HSL values to convert back to RGB and HEX. The live preview shows the current color immediately, making it easier to check whether a copied brand color, CSS variable, theme token or design sample looks correct before using it. This is useful when moving between design tools, CSS code, documentation, inline styles and UI prototypes where different color formats are required. All calculations happen locally in your browser with JavaScript, so Toolexa does not upload or permanently store any color values. The result area lists copy-ready HEX, RGB and HSL strings, and the copy button helps move values into your stylesheet or design notes quickly. The page follows the same responsive Toolexa tool layout with clear controls and supporting SEO content.',
                [
                    'HEX to RGB conversion',
                    'RGB to HEX conversion',
                    'RGB to HSL conversion',
                    'HSL to RGB conversion',
                    'Live color preview',
                    'Copy values',
                ],
                [
                    'Enter a HEX, RGB or HSL value in the matching fields.',
                    'Click the conversion action you need.',
                    'Check the live color preview.',
                    'Copy the generated HEX, RGB and HSL values.',
                    'Use Clear to reset the converter.',
                ],
                [
                    ['q' => 'Can I convert HEX to RGB?', 'a' => 'Yes, enter a HEX value and click HEX to RGB.'],
                    ['q' => 'Does the tool show HSL values?', 'a' => 'Yes, RGB values can be converted into HSL.'],
                    ['q' => 'Is there a live preview?', 'a' => 'Yes, the preview updates to show the selected color.'],
                    ['q' => 'Are color values uploaded?', 'a' => 'No, conversion happens locally in your browser.'],
                    ['q' => 'Can I copy all values?', 'a' => 'Yes, the output contains copy-ready HEX, RGB and HSL strings.'],
                ],
                ['css-beautifier', 'css-minifier', 'html-formatter', 'image-resizer', 'image-compressor', 'json-formatter'],
                'Color Tools'
            ),
            self::localTool(
                'Barcode Generator',
                'barcode-generator',
                'BAR',
                'Generate Code128, Code39, EAN-13 and UPC-A barcodes locally.',
                'Barcode Generator is a business utility for creating printable barcodes directly in your browser. Choose Code128 for flexible text and numbers, Code39 for simple alphanumeric labels, EAN-13 for retail-style 13-digit codes, or UPC-A for 12-digit product codes. Enter the barcode value, generate the preview, then download the barcode as SVG or PNG, or print it from the page. This is useful for product mockups, inventory labels, internal references, packaging drafts, warehouse testing, sample catalog entries and small business workflows where a quick barcode image is needed. Barcode generation runs locally with JavaScript, so Toolexa does not upload or permanently store your barcode value. The preview lets you check the visual output before downloading, while the copy area summarizes the encoded value and selected format. Users should test printed barcodes with their own scanner before using them in production, especially for retail systems that require official number allocation. The interface stays simple, responsive and aligned with the existing Toolexa tool design.',
                [
                    'Generate Code128 barcodes',
                    'Generate Code39 barcodes',
                    'Generate EAN-13 barcodes',
                    'Generate UPC-A barcodes',
                    'Download PNG or SVG',
                    'Print barcode output',
                ],
                [
                    'Choose the barcode type.',
                    'Enter the barcode value.',
                    'Click Generate Barcode.',
                    'Review the preview and output summary.',
                    'Download PNG, download SVG or print the barcode.',
                ],
                [
                    ['q' => 'Which barcode formats are supported?', 'a' => 'The tool supports Code128, Code39, EAN-13 and UPC-A.'],
                    ['q' => 'Can I download the barcode?', 'a' => 'Yes, download options are available for SVG and PNG.'],
                    ['q' => 'Can I print the barcode?', 'a' => 'Yes, use the Print Barcode button after generating it.'],
                    ['q' => 'Does Toolexa store barcode values?', 'a' => 'No, barcode generation runs locally in your browser.'],
                    ['q' => 'Should I test the printed barcode?', 'a' => 'Yes, always scan-test output before production or retail use.'],
                ],
                ['vat-calculator', 'qr-generator', 'image-to-base64-converter', 'pdf-merger', 'json-formatter', 'random-number-generator'],
                'Business Tools'
            ),
            self::localTool(
                'Image to Base64 Converter',
                'image-to-base64-converter',
                'B64',
                'Upload an image and convert it to a Base64 data URL.',
                'Image to Base64 Converter is a browser-based image utility for converting small image files into Base64 data URLs. Upload a PNG, JPG, WebP or GIF image, preview it on the page, and generate a copy-ready Base64 string that can be used in HTML, CSS, JSON samples, test payloads, documentation or quick prototypes. This is helpful when you need a tiny embedded asset, want to test an API field, prepare a CSS background data URL or include a sample image without hosting it separately. The file is read locally with the browser FileReader API, so Toolexa does not upload or permanently store your image. The preview confirms you selected the right file before copying or downloading the result as a TXT file. Base64 can make files larger than the original binary image, so it is best for small icons, placeholders and test data rather than large photos. The page uses the same responsive Toolexa layout with clear upload, preview, copy, download and reset controls.',
                [
                    'Upload image from your device',
                    'Convert image to Base64',
                    'Preview selected image',
                    'Copy Base64 output',
                    'Download output as TXT',
                    'Clear selected file and result',
                ],
                [
                    'Upload a supported image file.',
                    'Click Convert to Base64.',
                    'Check the preview and generated data URL.',
                    'Copy the Base64 text or download it as TXT.',
                    'Use Clear before converting another image.',
                ],
                [
                    ['q' => 'Which image formats are supported?', 'a' => 'PNG, JPG, JPEG, WebP and GIF files are supported.'],
                    ['q' => 'Does this upload my image?', 'a' => 'No, the image is converted locally in your browser.'],
                    ['q' => 'Can I preview the image?', 'a' => 'Yes, the uploaded image preview appears in the result panel.'],
                    ['q' => 'Can I download the Base64 text?', 'a' => 'Yes, use Download TXT after conversion.'],
                    ['q' => 'Is Base64 good for large images?', 'a' => 'It is usually better for small images because Base64 output is larger than the source file.'],
                ],
                ['base64-encoder-decoder', 'image-compressor', 'image-resizer', 'jpg-to-png-converter', 'png-to-jpg-converter', 'html-formatter'],
                'Image Tools'
            ),
            self::localTool(
                'VAT Calculator',
                'vat-calculator',
                'VAT',
                'Add or remove VAT and calculate VAT amount and totals.',
                'VAT Calculator is a simple finance tool for adding VAT to a net amount or removing VAT from a gross amount. Enter the amount, enter the VAT rate, then choose Add VAT or Remove VAT. The result shows the net amount, VAT amount and total amount in a copy-ready summary. This is useful for invoices, quotations, product pricing, receipts, business estimates, purchase comparisons and quick tax checks where you need to understand how VAT affects a price. Add VAT starts from an amount before tax and calculates the final total. Remove VAT starts from a tax-inclusive total and works backward to estimate the pre-tax value and VAT component. Calculations run locally in your browser, and Toolexa does not upload or permanently store your amounts. Results are intended for quick planning and should be checked against local tax rules, rounding policies and accounting requirements before official use. The page follows the same responsive Toolexa layout with clear inputs, result summary, copy button and helpful FAQs.',
                [
                    'Add VAT to a net amount',
                    'Remove VAT from a gross amount',
                    'Calculate VAT amount',
                    'Show total amount',
                    'Copy result summary',
                    'Clear inputs and output',
                ],
                [
                    'Enter the amount.',
                    'Enter the VAT rate percentage.',
                    'Choose Add VAT or Remove VAT.',
                    'Review the net amount, VAT amount and total amount.',
                    'Copy the result or clear the calculator.',
                ],
                [
                    ['q' => 'Can I add VAT to a price?', 'a' => 'Yes, enter the net amount and click Add VAT.'],
                    ['q' => 'Can I remove VAT from a total?', 'a' => 'Yes, enter the VAT-inclusive total and click Remove VAT.'],
                    ['q' => 'Does it show VAT amount?', 'a' => 'Yes, the result includes VAT amount and total amount.'],
                    ['q' => 'Are VAT results stored?', 'a' => 'No, calculations happen locally in your browser.'],
                    ['q' => 'Should I use this for official tax filing?', 'a' => 'Use it for estimates and verify official calculations with your accountant or local rules.'],
                ],
                ['gst-calculator', 'discount-calculator', 'percentage-calculator', 'invoice-generator', 'emi-calculator', 'simple-interest-calculator'],
                'Finance'
            ),
            self::localTool(
                'Robots.txt Generator',
                'robots-txt-generator',
                'ROB',
                'Generate robots.txt rules with allow, disallow and sitemap support.',
                'Robots.txt Generator is a browser-based SEO tool for creating a clean robots.txt file without writing every directive manually. Enter the user-agent, add Allow rules, add Disallow rules and include a sitemap URL when available. The tool then generates a copy-ready robots.txt file that can be downloaded and uploaded to the root of a website. It is useful for site owners, SEO teams, developers and bloggers who need a quick starting point for crawler instructions, staging blocks, admin-path exclusions or sitemap discovery. Robots.txt files guide compliant crawlers, but they do not protect private content, so sensitive pages should still be secured properly. All generation happens locally in your browser with JavaScript, and Toolexa does not upload or permanently store the rules you enter. The output panel makes it easy to review the generated directives before copying or downloading the file. The page follows Toolexa’s standard responsive layout with SEO metadata, breadcrumbs, FAQs, related tools and copy/share controls.',
                ['Generate robots.txt', 'Allow rules', 'Disallow rules', 'Sitemap URL support', 'Copy output', 'Download robots.txt', 'Clear form'],
                ['Enter the user-agent value.', 'Add Allow rules and Disallow rules, one per line.', 'Enter the sitemap URL if available.', 'Click Generate robots.txt.', 'Copy or download the generated file.'],
                [
                    ['q' => 'Where should robots.txt be uploaded?', 'a' => 'It should be placed at the root of your domain, such as example.com/robots.txt.'],
                    ['q' => 'Can robots.txt secure private pages?', 'a' => 'No, it only gives crawler instructions and should not be used as access control.'],
                    ['q' => 'Can I add a sitemap URL?', 'a' => 'Yes, the generator includes a Sitemap directive when you enter a URL.'],
                    ['q' => 'Does Toolexa store my rules?', 'a' => 'No, robots.txt generation happens locally in your browser.'],
                    ['q' => 'Can I download the generated file?', 'a' => 'Yes, use Download robots.txt after generating output.'],
                ],
                ['sitemap', 'json-formatter', 'html-formatter', 'sql-formatter', 'url-encoder-decoder', 'qr-generator'],
                'SEO Tools'
            ),
            self::localTool(
                'Password Strength Checker',
                'password-strength-checker',
                'SEC',
                'Check password strength, crack time and improvement suggestions locally.',
                'Password Strength Checker is a local security tool for estimating how strong a password looks before you use it. Type a password and the page calculates a score based on length, character variety, repeated patterns and common weak choices. The result shows a Weak, Medium or Strong indicator, an estimated crack time, a character analysis and practical suggestions for improving the password. This is helpful when teaching password hygiene, reviewing temporary credentials, checking whether a generated phrase is diverse enough or helping users understand why short passwords are risky. The password is evaluated entirely in your browser with JavaScript and is never uploaded or permanently stored by Toolexa. The tool is educational and should not replace a trusted password manager, breach checking service or organization policy. For important accounts, use long unique passwords and multi-factor authentication. The interface follows Toolexa’s standard responsive tool pattern with a clear input, result summary, copy and clear controls, FAQs and related security/developer utilities.',
                ['Password strength score', 'Weak/Medium/Strong indicator', 'Crack time estimation', 'Character analysis', 'Suggestions to improve password', 'Copy and clear'],
                ['Enter a password in the field.', 'Click Check Strength.', 'Review the score, strength label and crack time estimate.', 'Read character analysis and suggestions.', 'Clear the field before checking another password.'],
                [
                    ['q' => 'Is my password uploaded?', 'a' => 'No, the password is checked locally in your browser.'],
                    ['q' => 'What does the score mean?', 'a' => 'The score estimates strength using length, variety and common weakness checks.'],
                    ['q' => 'Is crack time exact?', 'a' => 'No, it is a rough educational estimate and depends on attacker resources.'],
                    ['q' => 'Can I copy the result?', 'a' => 'Yes, the result summary can be copied.'],
                    ['q' => 'Should I reuse strong passwords?', 'a' => 'No, use unique passwords for important accounts.'],
                ],
                ['password-generator', 'base64-encoder-decoder', 'md5-hash-generator', 'uuid-generator', 'random-string-generator', 'json-validator'],
                'Security Tools'
            ),
            self::localTool(
                'CSV to JSON Converter',
                'csv-to-json-converter',
                'CSV',
                'Convert pasted or uploaded CSV data into pretty JSON locally.',
                'CSV to JSON Converter is a developer tool for turning spreadsheet-style CSV data into structured JSON in the browser. Paste CSV text or upload a .csv file, then convert it into an array of JSON objects using the first row as field names. The output is pretty printed by default so it is easy to inspect, copy, download and use in API tests, seed files, mock data, documentation or front-end prototypes. The converter handles quoted values, commas inside quoted cells and multiple rows for common CSV workflows. Because all parsing happens locally with JavaScript, Toolexa does not upload or permanently store your CSV file or generated JSON. This tool is helpful for developers, analysts, students and content teams who need a quick bridge between tabular data and JSON without opening a spreadsheet script or server-side converter. The interface includes upload, paste, copy, download and clear controls, plus the standard Toolexa SEO sections, related tools, FAQs, breadcrumbs and schema metadata.',
                ['Convert CSV to JSON', 'Upload CSV file', 'Copy JSON output', 'Download JSON', 'Pretty print JSON', 'Clear input and output'],
                ['Paste CSV text or upload a CSV file.', 'Click Convert CSV to JSON.', 'Review the pretty printed JSON output.', 'Copy or download the JSON file.', 'Use Clear before converting another dataset.'],
                [
                    ['q' => 'Does CSV upload to Toolexa?', 'a' => 'No, uploaded CSV files are read locally in your browser.'],
                    ['q' => 'How are JSON keys created?', 'a' => 'The first CSV row is used as the object field names.'],
                    ['q' => 'Are quoted CSV values supported?', 'a' => 'Yes, common quoted values and commas inside quoted cells are supported.'],
                    ['q' => 'Can I download the JSON?', 'a' => 'Yes, use Download JSON after conversion.'],
                    ['q' => 'Is the output pretty printed?', 'a' => 'Yes, JSON is formatted with indentation for readability.'],
                ],
                ['json-formatter', 'json-validator', 'json-to-xml-converter', 'xml-to-json-converter', 'sql-formatter', 'base64-encoder-decoder'],
                'Developer Tools'
            ),
            self::localTool(
                'Timestamp Converter',
                'timestamp-converter',
                'TIME',
                'Convert Unix timestamps to dates and dates to Unix timestamps.',
                'Timestamp Converter is a browser-based date and time tool for converting between Unix timestamps and human-readable dates. Enter a Unix timestamp to see the matching local date and ISO date, or choose a human date and convert it back into a Unix timestamp. A Current Timestamp button instantly fills the result with the current time, which is useful for logs, APIs, debugging, database records, analytics exports, token expiry checks and developer notes. The converter supports second-based Unix timestamps and also recognizes millisecond-length values when pasted. All conversion happens locally in your browser with JavaScript, so Toolexa does not upload or permanently store any dates or timestamps. The result is copy-ready and includes useful formats for quick comparison across systems. Because time zones can affect interpretation, the output includes both local time and ISO UTC text. The page follows Toolexa’s existing responsive tool layout with copy, clear, share, supporting content, FAQs, breadcrumbs and schema markup.',
                ['Unix Timestamp to Human Date', 'Human Date to Unix Timestamp', 'Current timestamp', 'Copy result', 'Clear input and output'],
                ['Enter a Unix timestamp or choose a human date.', 'Click the conversion action you need.', 'Use Current Timestamp for the current time.', 'Review local and UTC output.', 'Copy the result or clear the tool.'],
                [
                    ['q' => 'Does this convert Unix timestamps?', 'a' => 'Yes, it converts Unix timestamps into human-readable dates.'],
                    ['q' => 'Can I convert a date to timestamp?', 'a' => 'Yes, choose a human date and click Date to Unix.'],
                    ['q' => 'Does it show current timestamp?', 'a' => 'Yes, use the Current Timestamp button.'],
                    ['q' => 'Are timestamps uploaded?', 'a' => 'No, conversion happens locally in your browser.'],
                    ['q' => 'Which timezone is shown?', 'a' => 'The output includes local time and ISO UTC time.'],
                ],
                ['age-calculator', 'json-formatter', 'sql-formatter', 'uuid-generator', 'random-number-generator', 'unit-converter'],
                'Date & Time Tools'
            ),
            self::localTool(
                'WebP to PNG Converter',
                'webp-to-png-converter',
                'W2P',
                'Convert one or more WebP images to PNG locally in your browser.',
                'WebP to PNG Converter is an image utility for converting WebP files into PNG images without uploading them to a server. Select one WebP image or multiple WebP files, convert them in the browser, preview the output list and download each generated PNG. This is useful when a design app, upload form, marketplace, document editor or older workflow does not accept WebP images but supports PNG. The conversion uses browser image and canvas APIs, so Toolexa does not permanently store your files. PNG output is lossless but may be larger than the original WebP file, so it is best used when compatibility matters more than compression. Batch support helps convert several small assets in one session, while Clear resets the selected files and output list. The page follows Toolexa’s responsive image-tool style with upload, preview, download, related tools, FAQs, breadcrumbs and schema metadata. Users should review transparency and dimensions in the preview before using converted files in production graphics or websites.',
                ['Upload WebP image', 'Convert WebP to PNG', 'Preview converted images', 'Download PNG', 'Batch support', 'Clear files and output'],
                ['Upload one or more WebP images.', 'Click Convert to PNG.', 'Review the generated preview list.', 'Download each PNG output.', 'Use Clear before converting another batch.'],
                [
                    ['q' => 'Does WebP conversion upload files?', 'a' => 'No, conversion happens locally in your browser.'],
                    ['q' => 'Can I convert multiple images?', 'a' => 'Yes, the file input supports batch conversion.'],
                    ['q' => 'Can PNG files be larger?', 'a' => 'Yes, PNG output is often larger than WebP.'],
                    ['q' => 'Is transparency preserved?', 'a' => 'Canvas conversion usually preserves transparency from supported WebP files.'],
                    ['q' => 'Can I preview output?', 'a' => 'Yes, converted images appear in the preview list.'],
                ],
                ['image-to-base64-converter', 'png-to-jpg-converter', 'jpg-to-png-converter', 'image-compressor', 'image-resizer', 'image-cropper'],
                'Image Tools'
            ),
            self::localTool(
                'Keyword Density Checker',
                'keyword-density-checker',
                'KEY',
                'Analyze keyword density, word counts and top keywords locally.',
                'Keyword Density Checker is a browser-based SEO tool for reviewing how often words appear in a piece of content. Paste article copy, landing page text, product descriptions, blog drafts or meta content, then analyze total words, unique words, top keywords and keyword percentages. The report helps writers and SEO teams understand whether a page repeats a term too often, misses important wording or contains a healthy mix of related phrases. It is useful during content editing, competitor note-taking, on-page SEO reviews and readability checks before publishing. The analyzer ignores common stop words so the keyword list focuses on meaningful terms, and the output can be copied into a content brief or audit note. Everything runs locally with JavaScript, so Toolexa does not upload or permanently store your text. The page follows the standard Toolexa layout with responsive editors, copy/share actions, FAQs, breadcrumbs, schema data, related tools and supporting SEO content.',
                ['Analyze keyword density', 'Total word count', 'Unique word count', 'Top keywords', 'Keyword percentage', 'Copy report'],
                ['Paste your content into the text box.', 'Click Analyze Keyword Density.', 'Review total words, unique words and top keywords.', 'Check keyword percentages for repeated terms.', 'Copy the report if needed.'],
                [
                    ['q' => 'Does this tool upload my content?', 'a' => 'No, keyword analysis runs locally in your browser.'],
                    ['q' => 'What is keyword density?', 'a' => 'It is the percentage of total words represented by a specific keyword.'],
                    ['q' => 'Are common words ignored?', 'a' => 'Yes, common stop words are filtered from the top keyword list.'],
                    ['q' => 'Can I copy the report?', 'a' => 'Yes, the generated report is copy-ready.'],
                    ['q' => 'Is there an ideal density?', 'a' => 'There is no universal perfect number; use the report to avoid unnatural repetition.'],
                ],
                ['robots-txt-generator', 'url-slug-generator', 'html-to-markdown-converter', 'word-counter', 'character-counter', 'json-formatter'],
                'SEO Tools'
            ),
            self::localTool(
                'ICO / Favicon Generator',
                'ico-favicon-generator',
                'ICO',
                'Generate favicon sizes and downloadable ICO or ZIP files from an image.',
                'ICO / Favicon Generator helps create common favicon assets directly in the browser. Upload a PNG, JPG or WebP image and generate multiple favicon sizes including 16x16, 32x32, 48x48, 64x64, 180x180, 192x192 and 512x512. The page previews the generated icons, creates a multi-size .ico file using PNG image entries, and can also download a ZIP containing PNG favicon files plus the ICO. This is useful for website owners, Laravel projects, static sites, dashboards, blogs and brand prototypes that need favicon assets without opening an image editor. All resizing happens locally with canvas, so Toolexa does not upload or permanently store your image. For best results, start with a square, high-resolution logo or icon with enough padding. The tool keeps the workflow simple: upload, generate, preview and download. It follows Toolexa’s standard responsive layout with copy/share actions where relevant, related tools, FAQs, breadcrumbs, SEO metadata and schema markup.',
                ['Upload image', 'Generate favicon sizes', 'Generate 16x16 through 512x512 assets', 'Download .ico', 'Download ZIP', 'Preview icons', 'Clear output'],
                ['Upload a PNG, JPG or WebP image.', 'Click Generate Favicons.', 'Review the generated size previews.', 'Download the .ico file or ZIP package.', 'Use Clear before generating another favicon set.'],
                [
                    ['q' => 'Which sizes are generated?', 'a' => 'The tool generates 16x16, 32x32, 48x48, 64x64, 180x180, 192x192 and 512x512.'],
                    ['q' => 'Does the image upload to Toolexa?', 'a' => 'No, resizing happens locally in your browser.'],
                    ['q' => 'Can I download an ICO file?', 'a' => 'Yes, the tool creates a downloadable favicon.ico file.'],
                    ['q' => 'Can I download all sizes together?', 'a' => 'Yes, use Download ZIP for the generated assets.'],
                    ['q' => 'What source image works best?', 'a' => 'A square high-resolution logo or icon usually gives the best favicon results.'],
                ],
                ['image-resizer', 'image-cropper', 'image-compressor', 'png-to-jpg-converter', 'image-to-base64-converter', 'color-picker-from-image'],
                'Image Tools'
            ),
            self::localTool(
                'Mortgage Calculator',
                'mortgage-calculator',
                'MOR',
                'Calculate monthly mortgage payment, total interest and amortization summary.',
                'Mortgage Calculator is a browser-based finance tool for estimating loan payments and total borrowing cost. Enter the loan amount, annual interest rate and loan term, then calculate the estimated monthly payment, total payment, total interest and an amortization summary. The result helps homeowners, buyers, agents and finance learners compare loan scenarios before speaking with a lender. It shows how principal and interest change over time and gives a yearly summary so the long-term cost is easier to understand. Calculations happen locally in your browser with JavaScript, and Toolexa does not upload or permanently store loan values. Results are estimates and may not include taxes, insurance, fees, changing rates, prepayments or lender-specific rules. Use the printable result option to save or share a quick scenario, then verify official numbers with a qualified lender or advisor. The page follows Toolexa’s standard responsive tool pattern with copy, share, related tools, FAQs, breadcrumbs, SEO metadata and schema markup.',
                ['Loan amount input', 'Interest rate input', 'Loan term input', 'Monthly payment', 'Total interest', 'Amortization summary', 'Printable result'],
                ['Enter the loan amount.', 'Enter the annual interest rate.', 'Enter the loan term in years.', 'Click Calculate Mortgage.', 'Review monthly payment, total interest and yearly amortization summary.'],
                [
                    ['q' => 'Does this calculate monthly mortgage payment?', 'a' => 'Yes, it estimates the monthly principal and interest payment.'],
                    ['q' => 'Does it include taxes and insurance?', 'a' => 'No, it focuses on loan principal and interest only.'],
                    ['q' => 'Can I print the result?', 'a' => 'Yes, use the Print Result button after calculating.'],
                    ['q' => 'Are mortgage values stored?', 'a' => 'No, calculation runs locally in your browser.'],
                    ['q' => 'Are results official loan quotes?', 'a' => 'No, they are estimates and should be verified with a lender.'],
                ],
                ['emi-calculator', 'loan-calculator', 'home-loan-emi-calculator', 'simple-interest-calculator', 'compound-interest-calculator', 'vat-calculator'],
                'Finance'
            ),
            self::localTool(
                'URL Slug Generator',
                'url-slug-generator',
                'SLUG',
                'Convert text into a clean SEO-friendly URL slug.',
                'URL Slug Generator is a text and SEO utility for converting headings, titles and phrases into clean URL slugs. Paste a blog title, product name, category heading, page label or campaign phrase, then generate a lowercase slug with special characters removed and spaces replaced by hyphens. This is useful when preparing SEO-friendly URLs, Laravel route slugs, blog permalinks, product handles, documentation anchors or content migration spreadsheets. The generator keeps the output simple and readable, helping URLs look consistent across a website. Everything runs locally in your browser with JavaScript, so Toolexa does not upload or permanently store the text you enter. The result can be copied immediately and Clear resets the tool for another title. Slugs should still be reviewed for clarity, length and uniqueness before publishing. The page follows the same Toolexa structure as other tools with responsive layout, helpful introduction, features, how-to steps, FAQs, related tools, popular tools, breadcrumbs, Open Graph metadata and schema JSON-LD.',
                ['Convert text into SEO-friendly slug', 'Lowercase conversion', 'Remove special characters', 'Replace spaces with hyphens', 'Copy output', 'Clear input and output'],
                ['Paste a title or phrase into the input box.', 'Click Generate Slug.', 'Review the lowercase hyphenated URL slug.', 'Copy the slug if it looks correct.', 'Use Clear before generating another slug.'],
                [
                    ['q' => 'What is a URL slug?', 'a' => 'A slug is the readable part of a URL that identifies a page.'],
                    ['q' => 'Does it convert text to lowercase?', 'a' => 'Yes, output is converted to lowercase.'],
                    ['q' => 'Are special characters removed?', 'a' => 'Yes, special characters are stripped or normalized.'],
                    ['q' => 'Can I copy the slug?', 'a' => 'Yes, the generated slug appears in a copy-ready output box.'],
                    ['q' => 'Is my text stored?', 'a' => 'No, slug generation runs locally in your browser.'],
                ],
                ['keyword-density-checker', 'text-case-converter', 'remove-extra-spaces', 'word-counter', 'html-to-markdown-converter', 'robots-txt-generator'],
                'Text Tools'
            ),
            self::localTool(
                'Color Picker From Image',
                'color-picker-from-image',
                'PICK',
                'Upload an image, click a pixel and copy HEX, RGB and HSL colors.',
                'Color Picker From Image is a browser-based color tool for extracting colors from uploaded images. Select a PNG, JPG or WebP image, click directly on the preview, and the tool reports the selected pixel as HEX, RGB and HSL values. This is helpful for designers, developers, marketers and content creators who need to sample a brand color, match an interface shade, inspect a screenshot, build a palette from product photography or copy colors into CSS variables. The picked color is shown in a live preview and added to a small palette list that can be downloaded as text. All image reading and pixel sampling happens locally with canvas, so Toolexa does not upload or permanently store your file. The output is copy-ready and works well on desktop and mobile screens. For precise picking, use a clear image and click the exact area you want to sample. The page follows Toolexa’s standard tool architecture with responsive UI, related tools, FAQs, breadcrumbs, SEO metadata and schema markup.',
                ['Upload image', 'Click to pick color', 'HEX output', 'RGB output', 'HSL output', 'Copy color codes', 'Download palette'],
                ['Upload a PNG, JPG or WebP image.', 'Click a point on the image preview.', 'Review HEX, RGB and HSL values.', 'Copy the color codes or continue picking colors.', 'Download the palette when ready.'],
                [
                    ['q' => 'Does the image upload to Toolexa?', 'a' => 'No, the image is processed locally in your browser.'],
                    ['q' => 'Which color formats are shown?', 'a' => 'The tool shows HEX, RGB and HSL values.'],
                    ['q' => 'Can I pick multiple colors?', 'a' => 'Yes, each click adds the selected color to the palette output.'],
                    ['q' => 'Can I download the palette?', 'a' => 'Yes, use Download Palette after selecting colors.'],
                    ['q' => 'Which image formats work?', 'a' => 'PNG, JPG and WebP images are supported by the upload input.'],
                ],
                ['hex-rgb-hsl-color-converter', 'image-resizer', 'image-cropper', 'image-compressor', 'ico-favicon-generator', 'css-beautifier'],
                'Color Tools'
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

    private static function developerTool(string $name, string $slug, string $icon, string $desc, string $introduction, array $features, array $howTo, array $faq, array $related): array
    {
        return [
            'name' => $name,
            'slug' => $slug,
            'view' => 'developer-utility',
            'icon' => $icon,
            'desc' => $desc,
            'category' => 'Developer Tools',
            'seo_title' => $name.' - Free Online Developer Tool',
            'seo_description' => $desc.' Use this free Toolexa developer tool locally in your browser with copy, download, clear and responsive controls.',
            'keywords' => Str::lower($name).', developer tools, json tools, xml tools, html formatter, free online tool',
            'formula' => null,
            'introduction' => $introduction,
            'features' => $features,
            'how_to' => $howTo,
            'faq' => $faq,
            'related' => $related,
        ];
    }

    private static function localTool(string $name, string $slug, string $icon, string $desc, string $introduction, array $features, array $howTo, array $faq, array $related, string $category): array
    {
        return [
            'name' => $name,
            'slug' => $slug,
            'view' => 'local-utility',
            'icon' => $icon,
            'desc' => $desc,
            'category' => $category,
            'seo_title' => $name.' - Free Online Tool',
            'seo_description' => $desc.' Use this free Toolexa tool locally in your browser with copy, clear and responsive controls.',
            'keywords' => Str::lower($name).', '.Str::lower($category).', online tool, free tool',
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
                'Interactively crop Meesho shipping labels from A4 PDF pages with a live, adjustable preview.',
                'Meesho Label Cropper is a seller-focused tool for turning Meesho shipping label PDFs into clean, print-ready label pages. Upload the marketplace label PDF and the tool renders a high-resolution preview of the page so you can see exactly what you are cropping. Choose a starting layout such as A4 Single Label, A4 Double Label or Thermal 4x6, then use one-click preset buttons to jump straight to the shipping label, invoice, barcode area or packing slip region. Every preset is just a starting point: drag the crop box, resize it from any corner or edge, zoom in to check barcode alignment, and undo or redo changes freely before exporting. The same adjusted crop is applied across every page of a bulk label PDF. Export as a vector-accurate PDF or a high-resolution PNG. Everything runs locally in your browser, so files are never uploaded or stored on Toolexa servers, and the barcode is never re-rasterized when exporting to PDF, keeping it fully scannable.',
                ['Live high-resolution PDF preview', 'A4 Single, A4 Double, Thermal 4x6 and Custom layouts', 'One-click shipping label, invoice, barcode and packing slip presets', 'Drag, resize, zoom and pan the crop box', 'Undo and redo crop adjustments', 'Export as PDF or PNG without quality loss'],
                ['Upload the Meesho shipping label PDF.', 'Pick a starting layout: A4 Single, A4 Double, Thermal 4x6 or Custom.', 'Click a preset (Shipping Label, Invoice, Barcode Area or Packing Slip) or draw your own crop box.', 'Drag, resize or zoom the crop box until it matches the label exactly.', 'Choose PDF or PNG and download the cropped output.'],
                [['q' => 'Do I need to manually crop the Meesho label?', 'a' => 'No, presets give you an accurate starting crop, but you can drag, resize or zoom to fine-tune it before exporting.'], ['q' => 'Does it upload my PDF?', 'a' => 'No, the PDF is rendered and cropped entirely in your browser and is never uploaded or stored.'], ['q' => 'Does cropping reduce barcode quality?', 'a' => 'No, PDF export uses vector cropping so the barcode stays crisp and scannable, and PNG export renders at high resolution.'], ['q' => 'Does the crop apply to every page?', 'a' => 'Yes, the adjusted crop box is applied to every page of a multi-order label PDF for PDF export.'], ['q' => 'Can I undo a crop adjustment?', 'a' => 'Yes, undo and redo buttons let you step back through your recent crop changes.']],
                [
                    ['key' => 'a4_single', 'label' => 'A4 Single Label', 'hint' => 'One shipping label with the tax invoice below it on a full A4 sheet.', 'pageType' => 'a4'],
                    ['key' => 'a4_double', 'label' => 'A4 Double Label', 'hint' => 'Two shipping labels stacked on a single A4 sheet.', 'pageType' => 'a4'],
                    ['key' => 'thermal_4x6', 'label' => 'Thermal 4x6', 'hint' => 'Already a label-sized 4x6in page exported for a thermal printer.', 'pageType' => 'thermal'],
                    ['key' => 'custom', 'label' => 'Custom', 'hint' => 'Start with a blank crop box and select the area yourself.', 'pageType' => 'custom'],
                ]
            ),
            self::sellerTool(
                'Amazon Label Cropper',
                'amazon-label-cropper',
                'AMZ',
                'Crop Amazon shipping labels with an interactive, high-resolution preview instead of a blind auto-crop.',
                'Amazon Label Cropper helps marketplace sellers turn Amazon shipping label PDFs into clean, print-ready pages. Upload the PDF and the tool renders a high-resolution preview so you can see exactly what will be cropped before exporting. Choose A4, Thermal or Custom as a starting layout, then use one-click presets for the shipping label, invoice, barcode area or packing slip, and adjust the crop box by dragging its handles, zooming in or panning around the page. This is far more reliable than a blind fixed-coordinate crop because Amazon label positioning can vary by region, service type and seller workflow, and you can always see and correct the result before downloading. Export as a vector-accurate PDF, applied consistently across every page of a bulk label file, or as a high-resolution PNG for a single label. Processing happens fully in your browser; Toolexa never uploads or stores the PDF.',
                ['Live high-resolution PDF preview', 'A4, Thermal and Custom layouts', 'One-click shipping label, invoice, barcode and packing slip presets', 'Drag, resize, zoom and pan the crop box', 'Undo and redo crop adjustments', 'Export as PDF or PNG without quality loss'],
                ['Upload the Amazon shipping label PDF.', 'Pick a starting layout: A4, Thermal or Custom.', 'Click a preset (Shipping Label, Invoice, Barcode Area or Packing Slip) or draw your own crop box.', 'Drag, resize or zoom the crop box until it matches the label exactly.', 'Choose PDF or PNG and download the cropped output.']
                ,
                [['q' => 'Does Amazon Label Cropper auto detect the layout?', 'a' => 'It starts you on an accurate preset for A4 or Thermal exports, and you can see and adjust the exact crop area before exporting rather than relying on a blind guess.'], ['q' => 'Do I need to drag a crop box?', 'a' => 'Presets get you close automatically, but you can drag, resize or zoom the box for a perfect fit.'], ['q' => 'Are files stored on Toolexa?', 'a' => 'No permanent file storage is used; everything runs locally in your browser.'], ['q' => 'Can Amazon layout changes affect results?', 'a' => 'Yes, if Amazon changes its label design you can simply adjust the crop box instead of waiting for a preset update.'], ['q' => 'Can I download the cropped label?', 'a' => 'Yes, export as a vector-accurate PDF or a high-resolution PNG.']],
                [
                    ['key' => 'a4', 'label' => 'A4', 'hint' => 'Full A4 sheet with the shipping label near the top of the page.', 'pageType' => 'a4'],
                    ['key' => 'thermal', 'label' => 'Thermal', 'hint' => 'Already a label-sized page exported for a thermal printer.', 'pageType' => 'thermal'],
                    ['key' => 'custom', 'label' => 'Custom', 'hint' => 'Start with a blank crop box and select the area yourself.', 'pageType' => 'custom'],
                ]
            ),
            self::sellerTool(
                'Flipkart Label Cropper',
                'flipkart-label-cropper',
                'FK',
                'Crop Flipkart shipping labels with a live, adjustable preview and vector-accurate PDF export.',
                'Flipkart Label Cropper is designed for sellers who need to turn Flipkart shipping label PDFs into compact, print-ready pages without guesswork. Upload the Flipkart label PDF and the tool renders a high-resolution preview so you can see exactly what is being cropped. Pick A4, Thermal or Custom as a starting layout, then use one-click presets for the shipping label, invoice, barcode area or packing slip. Every preset can be fine-tuned by dragging the crop box, resizing from any handle, zooming in to verify barcode alignment, and panning around a large page. This keeps output accurate even as Flipkart\'s label templates change over time, since you can always see and correct the crop rather than trusting a fixed set of coordinates. Export as a vector-accurate PDF applied to every page of a bulk file, or as a high-resolution PNG for a single label. The PDF is handled entirely in your browser, and Toolexa does not upload or permanently store it.',
                ['Live high-resolution PDF preview', 'A4, Thermal and Custom layouts', 'One-click shipping label, invoice, barcode and packing slip presets', 'Drag, resize, zoom and pan the crop box', 'Undo and redo crop adjustments', 'Export as PDF or PNG without quality loss'],
                ['Upload the Flipkart shipping label PDF.', 'Pick a starting layout: A4, Thermal or Custom.', 'Click a preset (Shipping Label, Invoice, Barcode Area or Packing Slip) or draw your own crop box.', 'Drag, resize or zoom the crop box until it matches the label exactly.', 'Choose PDF or PNG and download the cropped output.'],
                [['q' => 'Does this tool manually crop Flipkart labels?', 'a' => 'It starts from an accurate preset, and you can drag or resize the crop box for a perfect manual fit.'], ['q' => 'Can I use A4 Flipkart PDFs?', 'a' => 'Yes, both A4 and Thermal exports are supported as starting layouts.'], ['q' => 'Does Toolexa store the file?', 'a' => 'No, files are processed locally in your browser and are not permanently stored.'], ['q' => 'What if the crop is not aligned?', 'a' => 'Drag or resize the crop box, zoom in to check alignment, or use undo to step back to a previous adjustment.'], ['q' => 'Is the output downloadable?', 'a' => 'Yes, download the cropped label as a vector-accurate PDF or a high-resolution PNG.']],
                [
                    ['key' => 'a4', 'label' => 'A4', 'hint' => 'Full A4 sheet with the shipping label near the top of the page.', 'pageType' => 'a4'],
                    ['key' => 'thermal', 'label' => 'Thermal', 'hint' => 'Already a label-sized page exported for a thermal printer.', 'pageType' => 'thermal'],
                    ['key' => 'custom', 'label' => 'Custom', 'hint' => 'Start with a blank crop box and select the area yourself.', 'pageType' => 'custom'],
                ]
            ),
            self::sellerTool(
                'Myntra Label Cropper',
                'myntra-label-cropper',
                'MYN',
                'Crop Myntra seller labels with a live, adjustable preview instead of a fixed blind crop.',
                'Myntra Label Cropper gives sellers a faster, more reliable way to prepare Myntra shipping label PDFs for printing. Instead of trusting a fixed crop that may not match your export, upload the PDF and the tool renders a high-resolution preview so you can see the page before cropping. Choose A4, Thermal or Custom as a starting layout, then use one-click presets for the shipping label, invoice, barcode area or packing slip, and fine-tune the crop box by dragging its handles, zooming in to check the barcode, or panning around the page. This is especially useful for repeated packing tasks, since the adjusted crop can be applied consistently across every page of a bulk label PDF. Export as a vector-accurate PDF or a high-resolution PNG. Processing happens locally in your browser, and uploaded files are not permanently stored by Toolexa.',
                ['Live high-resolution PDF preview', 'A4, Thermal and Custom layouts', 'One-click shipping label, invoice, barcode and packing slip presets', 'Drag, resize, zoom and pan the crop box', 'Undo and redo crop adjustments', 'Export as PDF or PNG without quality loss'],
                ['Upload the Myntra shipping label PDF.', 'Pick a starting layout: A4, Thermal or Custom.', 'Click a preset (Shipping Label, Invoice, Barcode Area or Packing Slip) or draw your own crop box.', 'Drag, resize or zoom the crop box until it matches the label exactly.', 'Choose PDF or PNG and download the cropped output.'],
                [['q' => 'Does Myntra Label Cropper require manual crop selection?', 'a' => 'Presets provide an accurate starting point, and you can drag or resize the box manually if needed.'], ['q' => 'Can I download the result?', 'a' => 'Yes, export as a vector-accurate PDF or a high-resolution PNG.'], ['q' => 'Is the PDF permanently stored?', 'a' => 'No, uploaded PDFs are processed locally and are not permanently stored.'], ['q' => 'Can Myntra template changes affect output?', 'a' => 'You can always see the live preview and adjust the crop box, so template changes no longer produce a silently wrong crop.'], ['q' => 'What size is the result?', 'a' => 'PDF output matches your adjusted crop box exactly at full vector quality; PNG output renders at high resolution.']],
                [
                    ['key' => 'a4', 'label' => 'A4', 'hint' => 'Full A4 sheet with the shipping label block on the page.', 'pageType' => 'a4'],
                    ['key' => 'thermal', 'label' => 'Thermal', 'hint' => 'Already a label-sized page exported for a thermal printer.', 'pageType' => 'thermal'],
                    ['key' => 'custom', 'label' => 'Custom', 'hint' => 'Start with a blank crop box and select the area yourself.', 'pageType' => 'custom'],
                ]
            ),
            self::sellerTool(
                'Ajio Label Cropper',
                'ajio-label-cropper',
                'AJI',
                'Crop Ajio shipping labels with a live, adjustable preview and download a print-ready PDF or PNG.',
                'Ajio Label Cropper is a seller utility for cropping Ajio marketplace shipping label PDFs with full visual control. Upload an Ajio label PDF and the tool renders a high-resolution preview so you can see exactly what will be cropped, instead of trusting a fixed set of coordinates that may not match your export. Select A4, Thermal or Custom as a starting layout, then use one-click presets for the shipping label, invoice, barcode area or packing slip, and adjust the crop box by dragging, resizing, zooming or panning until it lines up perfectly. This is especially useful for faster packing workflows, since the confirmed crop is applied consistently across every page of a bulk label PDF. Export as a vector-accurate PDF or a high-resolution PNG. Processing is handled locally in your browser, and Toolexa does not upload or permanently store your PDF.',
                ['Live high-resolution PDF preview', 'A4, Thermal and Custom layouts', 'One-click shipping label, invoice, barcode and packing slip presets', 'Drag, resize, zoom and pan the crop box', 'Undo and redo crop adjustments', 'Export as PDF or PNG without quality loss'],
                ['Upload the Ajio shipping label PDF.', 'Pick a starting layout: A4, Thermal or Custom.', 'Click a preset (Shipping Label, Invoice, Barcode Area or Packing Slip) or draw your own crop box.', 'Drag, resize or zoom the crop box until it matches the label exactly.', 'Choose PDF or PNG and download the cropped output.'],
                [['q' => 'Does Ajio Label Cropper upload my PDF?', 'a' => 'No, processing happens locally in your browser and no permanent server storage is used.'], ['q' => 'Do I need to crop manually?', 'a' => 'Presets give you an accurate starting crop, and manual dragging or resizing is available whenever you need to fine-tune it.'], ['q' => 'Can I download cropped labels?', 'a' => 'Yes, download the generated output as a vector-accurate PDF or a high-resolution PNG.'], ['q' => 'What if the Ajio layout is different?', 'a' => 'Switch layouts, use another preset, or drag the crop box directly since you can always see the live preview.'], ['q' => 'Is it suitable for seller workflows?', 'a' => 'Yes, the confirmed crop applies across every page of a bulk marketplace label PDF.']],
                [
                    ['key' => 'a4', 'label' => 'A4', 'hint' => 'Full A4 sheet with the shipping label block on the page.', 'pageType' => 'a4'],
                    ['key' => 'thermal', 'label' => 'Thermal', 'hint' => 'Already a label-sized page exported for a thermal printer.', 'pageType' => 'thermal'],
                    ['key' => 'custom', 'label' => 'Custom', 'hint' => 'Start with a blank crop box and select the area yourself.', 'pageType' => 'custom'],
                ]
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
            'seo_title' => $name.' - Interactive Seller Shipping Label Tool',
            'seo_description' => $desc.' Adjust the crop live, then export as PDF or PNG without losing barcode quality.',
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
        static $toolsBySlug;
        $toolsBySlug ??= collect(self::tools())->keyBy('slug')->all();

        return $toolsBySlug[$slug] ?? null;
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
        static $categories;

        return $categories ??= collect(self::tools())
            ->pluck('category')
            ->unique()
            ->values()
            ->map(function ($category) {
                static $counts;
                $counts ??= collect(self::tools())->countBy('category');

                return [
                'name' => $category,
                'slug' => Str::slug($category),
                'count' => $counts[$category] ?? 0,
                ];
            })
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
            ['name' => 'SEO Tools', 'slug' => 'seo-tools', 'icon' => 'SEO', 'count' => $counts['seo-tools']['count'] ?? 0, 'status' => null],
            ['name' => 'Security Tools', 'slug' => 'security-tools', 'icon' => 'SEC', 'count' => $counts['security-tools']['count'] ?? 0, 'status' => null],
            ['name' => 'Date & Time Tools', 'slug' => 'date-time-tools', 'icon' => 'D/T', 'count' => $counts['date-time-tools']['count'] ?? 0, 'status' => null],
            ['name' => 'Color Tools', 'slug' => 'color-tools', 'icon' => 'CLR', 'count' => $counts['color-tools']['count'] ?? 0, 'status' => null],
            ['name' => 'Business Tools', 'slug' => 'business-tools', 'icon' => 'BUS', 'count' => $counts['business-tools']['count'] ?? 0, 'status' => null],
            ['name' => 'Image Tools', 'slug' => 'image-tools', 'icon' => 'IMG', 'count' => $counts['image-tools']['count'] ?? 0, 'status' => null],
            ['name' => 'PDF Tools', 'slug' => 'pdf-tools', 'icon' => 'PDF', 'count' => $counts['pdf-tools']['count'] ?? 0, 'status' => null],
            ['name' => 'Seller Tools', 'slug' => 'seller-tools', 'icon' => 'SEL', 'count' => $counts['seller-tools']['count'] ?? 0, 'status' => null],
            ['name' => 'Web Tools', 'slug' => 'web-tools', 'icon' => 'WEB', 'count' => $counts['web-tools']['count'] ?? 0, 'status' => null],
        ];
    }

    public function index(HomepageService $homepage)
    {
        return view('home', $homepage->data());
    }

    public function redirectHome()
    {
        return redirect('/');
    }
}

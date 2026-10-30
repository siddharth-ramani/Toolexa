<?php

return [
    'cache_hours' => 6,
    'default_published_at' => '2026-07-20',
    'content_version' => '1.0',

    'categories' => [
        'Image Tools' => [
            'context' => 'image quality, file size, transparency, software compatibility and the final publishing destination',
            'references' => [
                ['name' => 'MDN Image file type guide', 'url' => 'https://developer.mozilla.org/en-US/docs/Web/Media/Guides/Formats/Image_types'],
                ['name' => 'W3C Images Tutorial', 'url' => 'https://www.w3.org/WAI/tutorials/images/'],
            ],
        ],
        'Finance' => [
            'context' => 'risk tolerance, time horizon, liquidity, tax treatment and whether returns are guaranteed or market-linked',
            'references' => [
                ['name' => 'Reserve Bank of India', 'url' => 'https://www.rbi.org.in/'],
                ['name' => 'SEBI Investor', 'url' => 'https://investor.sebi.gov.in/'],
                ['name' => 'Income Tax Department', 'url' => 'https://www.incometax.gov.in/'],
            ],
        ],
        'Developer Tools' => [
            'context' => 'data shape, ecosystem support, validation requirements, performance and the systems that must consume the output',
            'references' => [
                ['name' => 'JSON specification', 'url' => 'https://www.rfc-editor.org/rfc/rfc8259'],
                ['name' => 'W3C Extensible Markup Language', 'url' => 'https://www.w3.org/XML/'],
                ['name' => 'MDN Web Docs', 'url' => 'https://developer.mozilla.org/'],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Manual comparison overrides
    |--------------------------------------------------------------------------
    | Every generated field can be replaced per slug: recommendations,
    | summaries, explanations, examples, decisions, mistakes, faqs,
    | references, content_version, published_at and version_history.
    */
    'comparisons' => [
        'jpg-vs-png' => [
            'content_version' => '2.0',
            'meta_title' => 'JPG vs PNG: Quality, File Size & Transparency Compared',
            'meta_description' => 'Compare JPG and PNG for photos, logos, screenshots, transparency, editing, printing and web use. Learn which format fits each real task.',
            'introduction' => 'JPG and PNG are widely supported raster image formats, but they preserve images differently. JPG normally uses lossy compression: it can make photographs much smaller by discarding image information, and the chosen quality setting affects both size and visible artifacts. PNG uses lossless compression and can preserve full alpha transparency, which makes it a strong choice for logos, interface screenshots, diagrams and graphics with sharp edges. Neither extension guarantees a particular file size or professional print result. Image dimensions, color complexity, encoder settings, metadata and the destination all matter. This guide compares the formats by actual workflow—photographs, transparent graphics, repeated editing, website delivery and print handoff—so you can choose an export intentionally rather than assuming PNG is always higher quality or JPG is always smaller.',
            'recommendations' => [
                ['label' => 'Best for Photographs', 'winner' => 'JPG', 'reason' => 'JPG usually gives a substantially smaller file for continuous-tone photographs when exported at an appropriate quality setting.'],
                ['label' => 'Best for Transparency', 'winner' => 'PNG', 'reason' => 'PNG supports full alpha transparency; ordinary JPEG has no alpha channel.'],
                ['label' => 'Best for Screenshots and Text', 'winner' => 'PNG', 'reason' => 'Lossless PNG preserves flat colors, thin lines and small interface text without JPEG ringing or block artifacts.'],
                ['label' => 'Best for Repeated Editing', 'winner' => 'PNG', 'reason' => 'Saving PNG losslessly avoids cumulative compression damage, although keeping the original editable source is still better.'],
                ['label' => 'Best Universal Web Fallback', 'winner' => 'Tie — depends on the image', 'reason' => 'Both have broad browser support: use JPG for photographic fallbacks and PNG when sharp edges or transparency must be preserved.'],
            ],
            'summary' => [
                'left' => ['title' => 'Choose JPG if', 'text' => 'The image is a photograph or continuous-tone scene, transparency is unnecessary, and a smaller broadly compatible file matters more than lossless pixel preservation.'],
                'right' => ['title' => 'Choose PNG if', 'text' => 'The image contains transparency, interface text, line art, a diagram or flat-color graphics where exact edges and lossless saving matter.'],
                'context' => 'Start with the visual content and destination. Compare exports at the same pixel dimensions; do not judge formats using unrelated images or assume the extension alone determines quality.',
            ],
            'explanations' => [
                'left' => [
                    'overview' => 'JPG (also written JPEG) is a raster image format commonly used for photographs. Typical web JPEG encoders use lossy compression, trading some image information for a smaller file.',
                    'history' => 'JPEG refers to the compression standard developed by the Joint Photographic Experts Group. Files commonly use .jpg or .jpeg and the image/jpeg media type; the shorter extension does not represent a different format.',
                    'how_it_works' => 'A typical JPEG encoder transforms image blocks into frequency information, quantizes that information according to a quality setting and compresses the result. Stronger quantization usually reduces size but can introduce blocking, ringing around edges and lost fine texture. Re-saving an already compressed JPG can add further loss.',
                    'where_used' => 'JPG is practical for camera photographs, product photos without transparency, email images and broadly compatible web fallbacks. Export once from the best available source and inspect important details at the intended display size.',
                    'context' => 'JPG does not support an alpha channel. A white background seen in a JPG is part of the pixels, not removable transparency.',
                ],
                'right' => [
                    'overview' => 'PNG is a raster image format with lossless compression and support for grayscale, indexed color, truecolor and optional transparency information.',
                    'history' => 'PNG was designed as a portable, patent-free format for lossless raster images. The current W3C PNG specification documents its image types, color information, alpha handling and chunk-level integrity checks.',
                    'how_it_works' => 'PNG filters image rows and compresses them without intentionally discarding decoded pixel information. Truecolor or grayscale PNG can include an alpha channel for partial transparency, while indexed PNG can carry transparency values for palette entries. Compression effort can change file size without acting like a JPEG quality slider.',
                    'where_used' => 'PNG is practical for transparent logos, UI captures, diagrams, pixel art and raster graphics with sharp text or limited colors. It can also store photographs losslessly, but those files are often much larger than a suitable JPG or modern web format.',
                    'context' => 'Lossless means the encoded pixel data can be reconstructed; it does not make a low-resolution, previously compressed or blurry source more detailed.',
                ],
            ],
            'examples' => [
                ['option' => 'JPG', 'scenario' => 'Marketplace product photograph', 'explanation' => 'For an opaque photo with fabric texture and gradual shadows, export a high-quality JPG from the original, inspect fine edges and confirm the marketplace’s pixel and byte limits. Converting a small JPG to PNG will not restore discarded detail.'],
                ['option' => 'PNG', 'scenario' => 'Transparent brand logo', 'explanation' => 'A raster logo placed over different page colors needs real alpha transparency. Export PNG from the original logo; a JPG version has already flattened its background and converting it to PNG cannot automatically recover transparency.'],
                ['option' => 'PNG', 'scenario' => 'Software screenshot', 'explanation' => 'A screenshot containing small text, icons and flat interface colors usually suits PNG because lossless encoding preserves hard transitions. Crop unused space before export instead of lowering JPEG quality until the text becomes fuzzy.'],
                ['option' => 'JPG', 'scenario' => 'Emailing several travel photos', 'explanation' => 'Resize copies to the required dimensions and export JPG at a visually acceptable quality. Keep the original camera files separately because email copies are delivery derivatives, not editing masters.'],
                ['option' => 'Depends', 'scenario' => 'Print handoff', 'explanation' => 'Use the printer’s requested format, dimensions, resolution and color profile. A high-resolution JPG may print well and a PNG may preserve sharp raster artwork, but neither extension alone guarantees correct physical size or color output.'],
            ],
            'decisions' => [
                'left' => [
                    'choose_if' => ['The source is a photograph or continuous-tone scene', 'The destination needs broad image/jpeg support', 'Transparency is not required', 'You can inspect and control the export quality', 'Smaller delivery size matters'],
                    'reconsider_if' => ['The image needs transparent or semi-transparent pixels', 'Small text, diagrams or hard edges show visible artifacts', 'The file will be repeatedly opened and re-saved as JPG', 'The destination requires a lossless archival or editing master'],
                    'alternative' => 'Choose PNG for lossless raster graphics and transparency. For a modern website, also evaluate WebP or AVIF while providing an appropriate fallback.',
                ],
                'right' => [
                    'choose_if' => ['The image requires alpha transparency', 'It is a screenshot, diagram, pixel art or flat-color graphic', 'Exact raster edges must survive saving', 'Lossless pixel reconstruction matters', 'The destination explicitly accepts PNG'],
                    'reconsider_if' => ['It is a large photograph and the PNG is too heavy', 'The asset should really remain vector, such as a scalable logo', 'The print provider requires another format or color workflow', 'Page performance requires a more efficient modern format'],
                    'alternative' => 'Choose JPG for an opaque photographic derivative when a smaller universal file is the priority; retain the original source separately.',
                ],
            ],
            'mistakes' => [
                'Converting an already compressed JPG to PNG and expecting lost detail to return.',
                'Renaming .jpg to .png instead of actually decoding and re-encoding the file.',
                'Assuming every PNG is smaller or every JPG is low quality without comparing equivalent exports.',
                'Using JPG for a logo that requires transparency and then trying to remove its baked-in background.',
                'Repeatedly editing and saving the same JPG instead of returning to the original source.',
                'Comparing files with different pixel dimensions and attributing the entire size difference to format.',
                'Treating pixels-per-inch metadata as a substitute for sufficient pixel dimensions in print.',
                'Uploading a huge PNG photograph to a website solely because PNG is described as lossless.',
            ],
            'faqs' => [
                ['question' => 'Is JPG or PNG better quality?', 'answer' => 'PNG preserves decoded pixel data losslessly, while typical JPG encoding is lossy. But a PNG made from a damaged JPG does not restore detail, and a well-exported JPG can look excellent for photographs.'],
                ['question' => 'Which is smaller, JPG or PNG?', 'answer' => 'For photographs, JPG is usually much smaller. For simple graphics with few colors, PNG can be efficient. Dimensions, image content, metadata and encoder settings prevent a universal file-size rule.'],
                ['question' => 'Does JPG support transparency?', 'answer' => 'Ordinary JPEG does not have an alpha channel. Any visible background in a JPG is stored as image pixels.'],
                ['question' => 'Can PNG have partial transparency?', 'answer' => 'Yes. PNG image types with alpha can represent values from fully transparent through partially transparent to fully opaque.'],
                ['question' => 'Should screenshots be JPG or PNG?', 'answer' => 'PNG is usually the safer choice for interface screenshots containing small text, icons and flat colors because it avoids lossy edge artifacts. A photographic game screenshot may require a practical size comparison.'],
                ['question' => 'Should photographs be JPG or PNG?', 'answer' => 'JPG is generally the practical delivery format for opaque photographs when broad compatibility and size matter. Keep the original or a suitable master for future editing.'],
                ['question' => 'Does converting JPG to PNG improve quality?', 'answer' => 'No. The PNG prevents an additional lossy save at that step, but information already discarded by JPEG compression cannot be reconstructed by changing the container.'],
                ['question' => 'Is PNG always best for logos?', 'answer' => 'PNG is useful for raster logos with transparency, but SVG is often better when a true vector original exists because it scales without raster pixels. Follow the destination’s accepted formats.'],
                ['question' => 'Which format is better for printing?', 'answer' => 'Neither wins by extension alone. Ask the print provider about pixel dimensions, physical size, resolution, color profile and accepted format. Avoid repeatedly compressed JPGs and undersized raster files.'],
                ['question' => 'Are JPG and JPEG different?', 'answer' => 'No practical image-format difference exists between the .jpg and .jpeg extensions; both commonly identify JPEG images using image/jpeg.'],
                ['question' => 'Can I use both formats on a website?', 'answer' => 'Yes. Use each according to content, or use picture and source elements for modern formats with JPG or PNG fallbacks where appropriate.'],
                ['question' => 'Is opening a JPG harmful to quality?', 'answer' => 'Simply viewing or decoding it does not add loss. Editing and re-encoding it as JPG can introduce another generation of lossy compression.'],
            ],
            'references' => [
                ['name' => 'PNG Specification, Third Edition', 'url' => 'https://www.w3.org/TR/png/'],
                ['name' => 'JPEG 1 standard overview', 'url' => 'https://jpeg.org/jpeg/'],
                ['name' => 'MDN image file type and format guide', 'url' => 'https://developer.mozilla.org/en-US/docs/Web/Media/Guides/Formats/Image_types'],
            ],
            'published_at' => '2026-07-20',
            'version_history' => [
                ['date' => '2026-10-30', 'note' => 'Added format-specific recommendations, conversion limitations, print guidance and current primary references.'],
            ],
        ],
        'webp-vs-png' => [
            'content_version' => '2.0',
            'meta_title' => 'WebP vs PNG: File Size, Quality & Transparency Compared',
            'meta_description' => 'Compare WebP and PNG for websites, transparency, lossless graphics, animation, editing and compatibility. Choose the right format for each image.',
            'introduction' => 'WebP and PNG can both store lossless images and transparency, but they are not equivalent exports. PNG is an established lossless raster format with broad support in browsers, editors and document workflows. WebP is designed for web delivery and supports lossy compression, lossless compression, alpha transparency and animation. A WebP file can therefore represent very different quality choices depending on how it was encoded. File-size claims are averages from particular test sets, not a promise for every source image. The practical choice depends on whether the asset is a photograph or graphic, whether exact transparent RGB values matter, which browsers and editing tools consume it, and whether a fallback is required.',
            'recommendations' => [
                ['label' => 'Best for Website Delivery', 'winner' => 'WebP', 'reason' => 'WebP offers lossy and lossless modes plus transparency and animation in a web-focused format.'],
                ['label' => 'Best Editing Compatibility', 'winner' => 'PNG', 'reason' => 'PNG remains a dependable interchange choice across image editors, documents and older workflows.'],
                ['label' => 'Best for Lossless Transparency', 'winner' => 'Tie — test the actual export', 'reason' => 'Both support alpha transparency; encoder settings and treatment of hidden RGB values can affect the result.'],
                ['label' => 'Best for Animation', 'winner' => 'WebP', 'reason' => 'WebP natively supports multiple animated frames, while ordinary single-image PNG does not.'],
                ['label' => 'Best Archival Editing Master', 'winner' => 'PNG', 'reason' => 'PNG is simpler when a broadly editable lossless raster master is needed, although the original project file is preferable.'],
            ],
            'summary' => [
                'left' => ['title' => 'Choose WebP if', 'text' => 'The destination is a modern website and you want a tested lossy or lossless derivative with optional transparency or animation.'],
                'right' => ['title' => 'Choose PNG if', 'text' => 'You need a broadly editable lossless raster file, dependable document compatibility or a fallback for transparent graphics.'],
                'context' => 'Compare files created from the same original at the same dimensions. Confirm visual quality, alpha edges, metadata needs and destination support rather than selecting by extension alone.',
            ],
            'explanations' => [
                'left' => ['overview' => 'WebP is a RIFF-based image format supporting lossy VP8 image data, lossless VP8L data, alpha transparency, metadata and animation.', 'history' => 'Google released WebP as an open web image format focused on reducing transfer size while supporting photographic and graphical content.', 'how_it_works' => 'Lossy and lossless WebP use different coding paths. A quality number has different meaning by mode, so “WebP” alone does not tell you whether pixels were preserved losslessly.', 'where_used' => 'Use it for tested website derivatives, including photographs, transparent graphics and animations, while retaining the original source.', 'context' => 'A WebP conversion can be larger than its source in some mode/content combinations; measure the actual output.'],
                'right' => ['overview' => 'PNG is a lossless raster format supporting grayscale, indexed color, truecolor and optional alpha transparency.', 'history' => 'PNG is standardized by W3C and designed for portable, well-compressed lossless raster images with chunk-level integrity checks.', 'how_it_works' => 'PNG filters scanlines and applies lossless compression. Encoder effort can change byte size without intentionally discarding decoded pixels.', 'where_used' => 'Use it for screenshots, diagrams, transparent graphics, document insertion and broadly editable lossless interchange.', 'context' => 'PNG photographs can be unnecessarily large for web delivery, and a vector original is usually better for scalable logos.'],
            ],
            'examples' => [
                ['option' => 'WebP', 'scenario' => 'Website product photograph', 'explanation' => 'Create a lossy WebP derivative from the original, compare texture and edges at display size, and keep a JPG fallback only if the supported audience or workflow needs it.'],
                ['option' => 'PNG', 'scenario' => 'UI screenshot for documentation', 'explanation' => 'PNG provides a predictable lossless file that document tools and reviewers can open without requiring a web-specific conversion.'],
                ['option' => 'WebP', 'scenario' => 'Animated website illustration', 'explanation' => 'Animated WebP can carry multiple frames and alpha; verify decoding cost and supply another representation when the consuming platform does not accept it.'],
                ['option' => 'Depends', 'scenario' => 'Transparent icon', 'explanation' => 'Test lossless WebP against PNG from the same source. Choose by actual bytes, edge appearance and the software that must edit or display the icon.'],
            ],
            'decisions' => [
                'left' => ['choose_if' => ['The final destination is a modern website', 'Lossy or lossless mode is chosen deliberately', 'Smaller measured delivery size matters', 'Animation is required'], 'reconsider_if' => ['An editor or marketplace rejects WebP', 'A document workflow requires PNG', 'The export was not visually compared', 'A fallback is required'], 'alternative' => 'Use PNG as a lossless transparent fallback or interchange file when compatibility is more important than delivery size.'],
                'right' => ['choose_if' => ['Lossless raster interchange is required', 'The image contains sharp text or diagrams', 'Broad editing and document support matters', 'A transparent fallback is needed'], 'reconsider_if' => ['A large photographic PNG harms page weight', 'Animation is required', 'A tested WebP is materially smaller', 'The asset should remain vector'], 'alternative' => 'Use WebP for a measured website derivative while retaining PNG or the original project file where needed.'],
            ],
            'mistakes' => ['Assuming every WebP is lossy or every WebP is smaller.', 'Comparing files created at different dimensions or quality levels.', 'Deleting the original after making a web derivative.', 'Converting a damaged source and expecting the new container to restore detail.', 'Ignoring whether the destination editor, CMS or marketplace accepts WebP.', 'Using PNG for a large photograph without measuring page-weight impact.', 'Forgetting animation when converting animated WebP to a single PNG.'],
            'faqs' => [
                ['question' => 'Is WebP always smaller than PNG?', 'answer' => 'No. Google reports strong average reductions for test sets, but content, mode and encoder settings determine each actual result.'],
                ['question' => 'Can WebP be lossless?', 'answer' => 'Yes. WebP supports separate lossy and lossless encoding modes.'],
                ['question' => 'Do both formats support transparency?', 'answer' => 'Yes. PNG supports alpha and WebP can carry alpha in lossless or lossy-RGB workflows.'],
                ['question' => 'Can PNG be animated?', 'answer' => 'APNG extends PNG for animation, but an ordinary single-image PNG is not animated. WebP supports animated frames in its extended container.'],
                ['question' => 'Does converting PNG to WebP improve quality?', 'answer' => 'No. Lossless conversion can preserve visible pixels, while lossy conversion trades information for size; neither adds missing detail.'],
                ['question' => 'Which is better for screenshots?', 'answer' => 'PNG is a predictable interchange choice; lossless WebP can be smaller for web delivery if the destination supports it.'],
                ['question' => 'Which should I keep as the original?', 'answer' => 'Keep the true source or project file. Treat WebP and PNG exports as derivatives when possible.'],
                ['question' => 'Does Toolexa’s WebP to PNG converter preserve animation?', 'answer' => 'No. Browser canvas conversion produces still PNG output, so animated WebP frames are not preserved.'],
                ['question' => 'Can a WebP file become larger after conversion?', 'answer' => 'Yes. Source content, color representation and the selected lossy or lossless mode can produce a larger result. Measure rather than assuming.'],
                ['question' => 'Which format works better in documents?', 'answer' => 'PNG is generally the more predictable interchange choice for office and document workflows; verify the specific application before converting.'],
            ],
            'references' => [['name' => 'Google WebP overview', 'url' => 'https://developers.google.com/speed/webp'], ['name' => 'WebP container specification', 'url' => 'https://developers.google.com/speed/webp/docs/riff_container'], ['name' => 'W3C PNG specification', 'url' => 'https://www.w3.org/TR/png/']],
            'version_history' => [['date' => '2026-10-30', 'note' => 'Added mode-specific WebP guidance, animation limitations and source-equivalent comparison rules.']],
        ],
        'fd-vs-sip' => [
            'content_version' => '2.0',
            'meta_title' => 'FD vs SIP: Risk, Returns, Liquidity & Goal Comparison',
            'meta_description' => 'Compare bank FD and mutual-fund SIP by capital risk, return certainty, liquidity, contribution pattern, tax and time horizon before choosing.',
            'introduction' => 'FD and SIP are not two versions of the same product. A bank fixed deposit usually places a lump sum for a stated tenure and contracted rate, subject to the bank’s terms. SIP is a method of investing a fixed amount periodically into a selected mutual-fund scheme; its value depends on the scheme’s assets and market movement, so the illustrated return is not guaranteed. A fair comparison must separate contribution pattern, capital risk, time horizon, liquidity, tax and the consequence of needing money during a loss. It must also compare the same cash-flow schedule: ₹1,00,000 deposited today is not equivalent to ₹10,000 invested monthly for ten months because the money is invested for different lengths of time.',
            'recommendations' => [
                ['label' => 'Best for Return Certainty', 'winner' => 'FD', 'reason' => 'The contracted deposit rate gives a more predictable maturity calculation when held under the stated terms.'],
                ['label' => 'Best for Market-linked Growth Potential', 'winner' => 'SIP', 'reason' => 'A SIP can build exposure to a mutual-fund scheme over time, but the value and return are not guaranteed.'],
                ['label' => 'Best for a Lump Sum with a Fixed Date', 'winner' => 'FD', 'reason' => 'FD directly models a present lump sum held for a selected tenure.'],
                ['label' => 'Best for Regular Monthly Investing', 'winner' => 'SIP', 'reason' => 'SIP is specifically a periodic investment method.'],
                ['label' => 'Best Overall', 'winner' => 'Tie — depends on the goal', 'reason' => 'Risk tolerance, cash-flow timing, liquidity and time horizon decide; many plans use different products for different goals.'],
            ],
            'summary' => [
                'left' => ['title' => 'Choose FD if', 'text' => 'You have a lump sum, need a more predictable value on a defined date and accept the deposit’s rate, premature-closure and tax terms.'],
                'right' => ['title' => 'Choose SIP if', 'text' => 'You can invest periodically for a suitable horizon, accept market fluctuation and have selected a regulated mutual-fund scheme matching the goal and risk level.'],
                'context' => 'Do not compare a guaranteed deposit rate with an assumed SIP return as if both were promises. Compare cash flows, post-tax outcomes and risk separately.',
            ],
            'explanations' => [
                'left' => ['overview' => 'An FD places money with a bank for a tenure at the applicable contracted rate and payout terms.', 'history' => 'Fixed deposits are established bank products used for scheduled lump-sum saving and interest income.', 'how_it_works' => 'The bank applies the stated compounding or payout convention. Premature closure can change the effective rate or attract a penalty under the terms.', 'where_used' => 'FDs are used for near- or medium-term goals where predictability matters more than market-linked upside.', 'context' => 'DICGC cover is currently up to ₹5 lakh per depositor per bank for principal and interest in the same right and capacity, subject to its rules; it is not unlimited.'],
                'right' => ['overview' => 'A SIP is an instruction to invest a recurring amount in a chosen mutual-fund scheme.', 'history' => 'SIPs are widely used to build regular investing discipline without requiring one lump-sum entry.', 'how_it_works' => 'Each instalment buys units at the applicable NAV. Unit count varies as NAV changes; the ending value depends on the scheme and market, not a fixed calculator rate.', 'where_used' => 'SIPs are used for recurring, longer-horizon goals where the investor accepts the selected scheme’s risk.', 'context' => 'SEBI states market returns are not fixed and SIP calculator outputs are illustrations, not actual-return promises.'],
            ],
            'examples' => [
                ['option' => 'FD', 'scenario' => 'Lump sum needed on a known date', 'explanation' => 'A saver with money available today can compare bank FD maturity values using the actual quoted rate, tenure and compounding terms, then check premature-closure conditions.'],
                ['option' => 'SIP', 'scenario' => 'Monthly long-term contribution', 'explanation' => 'A salaried investor can contribute after each payday to a selected mutual fund, while accepting that the value can be below contributions at some points.'],
                ['option' => 'Both', 'scenario' => 'Separate short and long goals', 'explanation' => 'A near-term known expense may use a predictable deposit while a distant growth goal uses a risk-appropriate SIP; product choice follows the goal rather than one universal ranking.'],
            ],
            'decisions' => [
                'left' => ['choose_if' => ['A lump sum is available now', 'A defined maturity date and predictable calculation matter', 'Market fluctuation is unsuitable for this goal', 'The bank and deposit terms have been verified'], 'reconsider_if' => ['Inflation may exceed the post-tax return', 'Premature access is likely', 'Deposit concentration exceeds the intended risk limit', 'The goal requires long-term market-linked growth'], 'alternative' => 'Use a risk-appropriate SIP for recurring long-term investing, not as a guaranteed substitute.'],
                'right' => ['choose_if' => ['Income supports recurring contributions', 'The time horizon can tolerate market cycles', 'The scheme and risk-o-meter match the goal', 'Returns are treated as uncertain'], 'reconsider_if' => ['Money is needed soon on a fixed date', 'Temporary losses would force withdrawal', 'The scheme has not been understood', 'An assumed calculator return is being treated as guaranteed'], 'alternative' => 'Use a suitable deposit for money that needs contractual predictability rather than market exposure.'],
            ],
            'mistakes' => ['Comparing an FD lump sum with monthly SIP contributions as identical cash flows.', 'Treating an assumed SIP return as guaranteed.', 'Ignoring FD premature-closure rules and post-tax return.', 'Choosing a mutual fund only from recent returns.', 'Assuming every FD amount has unlimited deposit-insurance protection.', 'Using market-linked money for an inflexible near-term expense.', 'Calling SIP a product instead of an investment method into a chosen scheme.'],
            'faqs' => [
                ['question' => 'Is SIP safer than FD?', 'answer' => 'A mutual-fund SIP is market-linked and can fluctuate. An FD has different risks and contractual terms; verify the bank and applicable deposit protection.'],
                ['question' => 'Is SIP return guaranteed?', 'answer' => 'No. SEBI’s SIP calculator states that market returns are not fixed and illustrations do not represent actual returns.'],
                ['question' => 'Is FD return guaranteed?', 'answer' => 'The contracted rate makes maturity more predictable if terms are met, but verify bank, closure, tax and deposit-protection conditions.'],
                ['question' => 'What does DICGC cover?', 'answer' => 'DICGC currently insures eligible deposits up to ₹5 lakh per depositor per bank for principal and interest in the same right and capacity, subject to its rules.'],
                ['question' => 'Can I use both FD and SIP?', 'answer' => 'Yes. Different goals can require different liquidity, certainty, horizon and risk.'],
                ['question' => 'Why do the Toolexa calculators show steady SIP growth?', 'answer' => 'The SIP Calculator compounds one assumed rate for illustration; real mutual-fund values fluctuate and will not follow a smooth path.'],
                ['question' => 'How should I compare equal amounts?', 'answer' => 'Match contribution dates and cash flows. A lump sum invested today has more time than monthly instalments added later.'],
                ['question' => 'Which is better for a short fixed-date goal?', 'answer' => 'Market fluctuation may be unsuitable for an inflexible near-term goal; assess an appropriately timed deposit and liquidity terms.'],
                ['question' => 'Does an SIP eliminate market risk through averaging?', 'answer' => 'No. Regular purchases can spread entry dates, but the selected scheme can still fall and the ending value is not guaranteed.'],
                ['question' => 'Should tax decide between FD and SIP?', 'answer' => 'Tax matters, but it should be evaluated with risk, liquidity, horizon and current rules rather than used as the only criterion.'],
            ],
            'references' => [['name' => 'SEBI SIP Calculator and disclaimer', 'url' => 'https://investor.sebi.gov.in/calculators/sip_calculator.html'], ['name' => 'SEBI factors before investing', 'url' => 'https://investor.sebi.gov.in/investment-thingsbeforeinv.html'], ['name' => 'DICGC deposit insurance FAQ', 'url' => 'https://www.dicgc.org.in/FAQs']],
            'version_history' => [['date' => '2026-10-30', 'note' => 'Rebuilt the comparison around matched cash flows, guaranteed-versus-assumed returns and official SEBI/DICGC guidance.']],
        ],
    ],
];

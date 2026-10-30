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
    ],
];

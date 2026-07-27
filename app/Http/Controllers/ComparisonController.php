<?php

namespace App\Http\Controllers;

use App\Services\ComparisonService;
use App\Services\EditorialService;
use App\Services\ComparisonQualityService;

class ComparisonController extends Controller
{
    public function index(ComparisonService $comparisons)
    {
        return view('compare.index', [
            'comparisons' => $comparisons->all(),
            'breadcrumbs' => [['name' => 'Home', 'url' => url('/')], ['name' => 'Comparisons', 'url' => route('compare.index')]],
            'canonicalUrl' => route('compare.index'),
            'seoTitle' => 'Tool, Format and Calculator Comparisons | Toolexa',
            'seoDescription' => 'Compare popular tools, file formats, calculators and concepts with clear differences, advantages, disadvantages and best use cases.',
            'seoKeywords' => 'tool comparisons, format comparisons, calculator comparisons, Toolexa',
        ]);
    }

    public function show(
        string $slug,
        ComparisonService $comparisons,
        EditorialService $editorial,
        ComparisonQualityService $quality
    )
    {
        $comparison = $comparisons->find($slug);
        abort_unless($comparison, 404);
        $qualityContent = $quality->build($comparison, $comparisons->all());
        $comparison['published_at'] = $qualityContent['published_at'];
        $comparison['reading_time'] = $qualityContent['reading_time'];
        $canonicalUrl = route('compare.show', $comparison['slug']);
        $editorialMeta = $editorial->metadata('comparisons', $comparison);
        $schemas = $comparison['schema'];
        foreach ($schemas as &$schema) {
            if (($schema['@type'] ?? null) === 'Article') {
                $schema['datePublished'] = $qualityContent['published_at'];
                $schema['dateModified'] = $editorialMeta['updated_at'];
                $schema['author'] = $editorial->personSchema($editorialMeta['author']);
                $schema['reviewedBy'] = $editorial->reviewerSchema($editorialMeta['reviewer']);
                $schema['wordCount'] = $qualityContent['word_count'];
            }
            if (($schema['@type'] ?? null) === 'FAQPage') {
                $schema['mainEntity'] = collect($qualityContent['faqs'])->map(fn (array $faq) => [
                    '@type' => 'Question',
                    'name' => $faq['question'],
                    'acceptedAnswer' => ['@type' => 'Answer', 'text' => $faq['answer']],
                ])->all();
            }
        }
        unset($schema);
        $schemas[] = array_merge(['@context' => 'https://schema.org'], $editorial->personSchema($editorialMeta['author']));

        return view('compare.show', [
            'comparison' => $comparison,
            'qualityContent' => $qualityContent,
            'editorialMeta' => $editorialMeta,
            'breadcrumbs' => [
                ['name' => 'Home', 'url' => url('/')],
                ['name' => 'Comparisons', 'url' => route('compare.index')],
                ['name' => $comparison['title'], 'url' => $canonicalUrl],
            ],
            'canonicalUrl' => $canonicalUrl,
            'seoTitle' => $comparison['meta_title'],
            'seoDescription' => $comparison['meta_description'],
            'seoKeywords' => strtolower($comparison['title']).', comparison, differences, pros and cons',
            'schemaJsonLd' => $schemas,
        ]);
    }
}

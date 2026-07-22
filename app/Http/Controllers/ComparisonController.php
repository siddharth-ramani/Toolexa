<?php

namespace App\Http\Controllers;

use App\Services\ComparisonService;

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

    public function show(string $slug, ComparisonService $comparisons)
    {
        $comparison = $comparisons->find($slug);
        abort_unless($comparison, 404);
        $canonicalUrl = route('compare.show', $comparison['slug']);

        return view('compare.show', [
            'comparison' => $comparison,
            'breadcrumbs' => [
                ['name' => 'Home', 'url' => url('/')],
                ['name' => 'Comparisons', 'url' => route('compare.index')],
                ['name' => $comparison['title'], 'url' => $canonicalUrl],
            ],
            'canonicalUrl' => $canonicalUrl,
            'seoTitle' => $comparison['meta_title'],
            'seoDescription' => $comparison['meta_description'],
            'seoKeywords' => strtolower($comparison['title']).', comparison, differences, pros and cons',
            'schemaJsonLd' => $comparison['schema'],
        ]);
    }
}

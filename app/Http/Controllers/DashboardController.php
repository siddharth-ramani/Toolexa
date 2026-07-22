<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Tools\HomeController;

class DashboardController extends Controller
{
    public function index()
    {
        $toolCatalog = collect(HomeController::tools())->map(fn (array $tool) => [
            'type' => 'tool', 'slug' => $tool['slug'], 'title' => $tool['name'], 'url' => url('tools/'.$tool['slug']),
            'icon' => $tool['icon'], 'category' => $tool['category'], 'description' => $tool['desc'],
        ])->values()->all();

        return view('dashboard', [
            'toolCatalog' => $toolCatalog,
            'breadcrumbs' => [['name' => 'Home', 'url' => url('/')], ['name' => 'My Dashboard', 'url' => route('dashboard')]],
            'canonicalUrl' => route('dashboard'),
            'seoTitle' => 'My Personal Toolexa Dashboard',
            'seoDescription' => 'Access recently used tools, saved articles, favorite comparisons, pinned tools and personal collections on this device.',
            'seoKeywords' => 'Toolexa dashboard, favorite tools, recent tools, personal collections',
            'robotsMeta' => 'noindex, follow',
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Tools\HomeController;

class WorkspaceController extends Controller
{
    public function index()
    {
        $tools = collect(HomeController::tools())->map(fn (array $tool) => [
            'slug' => $tool['slug'], 'name' => $tool['name'], 'icon' => $tool['icon'], 'category' => $tool['category'],
            'description' => $tool['desc'], 'url' => url('tools/'.$tool['slug']).'?workspace=1',
        ])->values()->all();

        return view('workspace', [
            'workspaceTools' => $tools,
            'canonicalUrl' => route('workspace'),
            'seoTitle' => 'Multi-Tool Workspace | Toolexa',
            'seoDescription' => 'Open up to four Toolexa tools together in a private, customizable split-screen browser workspace.',
            'seoKeywords' => 'Toolexa workspace, split screen tools, multiple online tools',
            'robotsMeta' => 'noindex, follow',
            'breadcrumbs' => [['name' => 'Home', 'url' => url('/')], ['name' => 'Workspace', 'url' => route('workspace')]],
        ]);
    }
}

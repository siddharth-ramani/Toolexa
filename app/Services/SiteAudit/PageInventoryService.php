<?php

namespace App\Services\SiteAudit;

use App\Http\Controllers\Tools\HomeController;
use App\Services\ComparisonService;
use App\Services\TopicHubService;
use App\Support\BlogRepository;

class PageInventoryService
{
    public function all(): array
    {
        $pages = [
            $this->page('homepage', 'Homepage', '/'),
            $this->page('directory', 'Blog Directory', route('blog.index', [], false)),
            $this->page('directory', 'Comparison Directory', route('compare.index', [], false)),
            $this->page('directory', 'Topic Directory', route('hub.index', [], false)),
            $this->page('utility_page', 'Search', route('search', [], false)),
            $this->page('utility_page', 'Personal Dashboard', route('dashboard', [], false)),
            $this->page('utility_page', 'Workspace', route('workspace', [], false)),
        ];

        foreach (HomeController::tools() as $tool) {
            $pages[] = $this->page('tool', $tool['name'], '/tools/'.$tool['slug'], $tool['slug']);
        }
        foreach (BlogRepository::all() as $article) {
            $pages[] = $this->page('blog', $article['title'], route('blog.show', $article['slug'], false), $article['slug']);
        }
        foreach (app(ComparisonService::class)->all() as $comparison) {
            $pages[] = $this->page('comparison', $comparison['title'], route('compare.show', $comparison['slug'], false), $comparison['slug']);
        }
        foreach (app(TopicHubService::class)->all() as $topic) {
            $pages[] = $this->page('topic', $topic['title'], route('hub.show', $topic['slug'], false), $topic['slug']);
        }
        foreach (HomeController::categories() as $category) {
            $pages[] = $this->page('category', $category['name'], route('category.show', $category['slug'], false), $category['slug']);
        }
        foreach (['trust', 'editorial-policy', 'accuracy-policy', 'how-we-test-tools'] as $trustPage) {
            $pages[] = $this->page('trust', str($trustPage)->headline()->toString(), '/'.$trustPage, $trustPage);
        }
        foreach (['about', 'contact', 'privacy-policy', 'terms', 'disclaimer'] as $legalPage) {
            $pages[] = $this->page('legal', str($legalPage)->headline()->toString(), '/'.$legalPage, $legalPage);
        }
        foreach (array_keys(config('editorial.authors', [])) as $author) {
            $pages[] = $this->page('author', config('editorial.authors.'.$author.'.name'), route('authors.show', $author, false), $author);
        }

        return collect($pages)->unique('path')->values()->all();
    }

    private function page(string $type, string $name, string $path, ?string $slug = null): array
    {
        return ['type' => $type, 'name' => $name, 'path' => $path, 'slug' => $slug ?? trim($path, '/') ?: 'home'];
    }
}

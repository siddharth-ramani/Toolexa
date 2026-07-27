<?php

namespace App\Services;

use App\Http\Controllers\Tools\HomeController;
use App\Support\BlogRepository;
use Illuminate\Support\Facades\Cache;

class EditorialService
{
    public function author(?string $slug = null): ?array
    {
        $slug ??= config('editorial.default_author');

        return $this->profiles()['authors'][$slug] ?? null;
    }

    public function reviewer(?string $slug = null): ?array
    {
        $slug ??= config('editorial.default_reviewer');

        return $this->profiles()['reviewers'][$slug] ?? null;
    }

    public function metadata(string $type, array $content): array
    {
        $slug = $content['slug'];
        $assignmentType = ['articles' => 'article', 'tools' => 'tool', 'comparisons' => 'comparison'][$type] ?? rtrim($type, 's');
        $assignment = config("editorial.{$assignmentType}_assignments.{$slug}", []);
        $author = $this->author($assignment['author'] ?? null);
        $reviewer = $this->reviewer($assignment['reviewer'] ?? null);
        $publishedAt = $content['published_at'] ?? null;
        $updatedAt = $assignment['updated_at']
            ?? ($content['updated_at'] ?? config('editorial.default_updated_at'));

        return [
            'author' => $author,
            'reviewer' => $reviewer,
            'published_at' => $publishedAt,
            'updated_at' => $updatedAt,
            'reading_time' => $this->readingTime($content),
            'history' => config("editorial.history.{$type}.{$slug}", []),
        ];
    }

    public function authorContent(string $slug): array
    {
        $author = $this->author($slug);
        abort_unless($author, 404);

        $articleAssignments = config('editorial.article_assignments', []);
        $toolAssignments = config('editorial.tool_assignments', []);
        $comparisonAssignments = config('editorial.comparison_assignments', []);

        $articles = collect(BlogRepository::all())
            ->filter(fn (array $article) => ($articleAssignments[$article['slug']]['author'] ?? config('editorial.default_author')) === $slug)
            ->sortByDesc('published_at')
            ->take(6)
            ->values()
            ->all();
        $tools = collect(HomeController::tools())
            ->filter(fn (array $tool) => ($toolAssignments[$tool['slug']]['author'] ?? config('editorial.default_author')) === $slug)
            ->reverse()
            ->take(6)
            ->values()
            ->all();
        $comparisons = collect(app(ComparisonService::class)->all())
            ->filter(fn (array $comparison) => ($comparisonAssignments[$comparison['slug']]['author'] ?? config('editorial.default_author')) === $slug)
            ->take(6)
            ->values()
            ->all();

        return compact('author', 'articles', 'tools', 'comparisons');
    }

    public function personSchema(array $profile): array
    {
        return array_filter([
            '@type' => 'Person',
            '@id' => route('authors.show', $profile['slug']).'#person',
            'name' => $profile['name'],
            'url' => route('authors.show', $profile['slug']),
            'image' => asset($profile['photo']),
            'jobTitle' => $profile['role'],
            'description' => $profile['bio'],
            'knowsAbout' => $profile['expertise'],
            'email' => $profile['email'] ?? null,
            'sameAs' => array_values($profile['social_links'] ?? []),
        ], fn ($value) => $value !== null && $value !== []);
    }

    public function reviewerSchema(array $profile): array
    {
        return [
            '@type' => 'Person',
            'name' => $profile['name'],
            'jobTitle' => $profile['role'],
            'description' => $profile['bio'],
            'knowsAbout' => $profile['expertise'],
        ];
    }

    private function readingTime(array $content): int
    {
        if (isset($content['reading_time'])) {
            return max(1, (int) $content['reading_time']);
        }

        $text = collect($content)->flatten()->filter(fn ($value) => is_string($value))->implode(' ');
        $words = str_word_count(strip_tags($text));

        return max(1, (int) ceil($words / 220));
    }

    private function profiles(): array
    {
        $fingerprint = substr(sha1(json_encode([
            config('editorial.authors'),
            config('editorial.reviewers'),
        ])), 0, 12);

        return Cache::remember('editorial-profiles:'.$fingerprint, now()->addDay(), fn () => [
            'authors' => config('editorial.authors', []),
            'reviewers' => config('editorial.reviewers', []),
        ]);
    }
}

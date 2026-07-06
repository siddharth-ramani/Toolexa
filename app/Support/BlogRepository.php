<?php

namespace App\Support;

use Illuminate\Support\Str;

class BlogRepository
{
    public static function all(): array
    {
        return collect(self::definitions())
            ->map(fn (array $article) => self::withReadingTime($article))
            ->values()
            ->all();
    }

    public static function find(string $slug): ?array
    {
        return collect(self::all())->firstWhere('slug', $slug);
    }

    public static function related(string $slug, int $limit = 3): array
    {
        $current = self::find($slug);

        if (! $current) {
            return [];
        }

        return collect(self::all())
            ->reject(fn (array $article) => $article['slug'] === $slug)
            ->sortByDesc(function (array $article) use ($current) {
                return count(array_intersect($article['related_tools'], $current['related_tools']))
                    + ($article['category'] === $current['category'] ? 2 : 0);
            })
            ->take($limit)
            ->values()
            ->all();
    }

    public static function adjacent(string $slug): array
    {
        $articles = self::all();
        $index = collect($articles)->search(fn (array $article) => $article['slug'] === $slug);

        return [
            'previous' => $index !== false && $index > 0 ? $articles[$index - 1] : null,
            'next' => $index !== false && $index < count($articles) - 1 ? $articles[$index + 1] : null,
        ];
    }

    public static function tableOfContents(array $article): array
    {
        return collect($article['sections'])
            ->map(fn (array $section) => [
                'id' => Str::slug($section['heading']),
                'title' => $section['heading'],
            ])
            ->all();
    }

    private static function withReadingTime(array $article): array
    {
        $words = str_word_count(strip_tags(json_encode($article['sections'])));
        $article['reading_time'] = max(1, (int) ceil($words / 220));

        return $article;
    }

    private static function definitions(): array
    {
        return BlogArticleDefinitions::all();
    }

}

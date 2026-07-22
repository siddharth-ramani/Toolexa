<?php

namespace App\Services;

use App\Http\Controllers\Tools\HomeController;
use App\Support\BlogRepository;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class IntelligentSearchService
{
    private const CACHE_KEY = 'intelligent-search:index:v1';

    public function search(string $query, string $filter = 'all', int $limit = 8): array
    {
        $query = $this->normalize($query);
        $filter = in_array($filter, ['all', 'tools', 'articles', 'categories'], true) ? $filter : 'all';
        $limit = max(1, min($limit, 20));
        $allowedType = ['tools' => 'tool', 'articles' => 'article', 'categories' => 'category'][$filter] ?? null;
        $groups = ['tools' => [], 'articles' => [], 'categories' => []];

        if ($query === '') {
            return ['query' => '', 'filter' => $filter, 'total' => 0, 'groups' => $groups];
        }

        $matches = collect($this->index())
            ->when($allowedType, fn ($items) => $items->where('type', $allowedType))
            ->map(function (array $item) use ($query) {
                $item['_score'] = $this->score($query, $item);

                return $item;
            })
            ->filter(fn (array $item) => $item['_score'] > 0)
            ->sortByDesc('_score')
            ->values();

        foreach (['tool' => 'tools', 'article' => 'articles', 'category' => 'categories'] as $type => $group) {
            $groups[$group] = $matches->where('type', $type)->take($limit)->map(function (array $item) {
                unset($item['_score'], $item['_search'], $item['_tokens']);

                return $item;
            })->values()->all();
        }

        return ['query' => $query, 'filter' => $filter, 'total' => $matches->count(), 'groups' => $groups];
    }

    public function popularContent(int $limit = 6): array
    {
        $tools = HomeController::toolsBySlugs(array_slice(HomeController::popularSlugs(), 0, $limit));
        $articles = collect(BlogRepository::all())->take($limit)->values()->all();

        return ['tools' => $tools, 'articles' => $articles];
    }

    public function trendingSearches(): array
    {
        return array_values(array_filter(array_map('trim', config('search.trending', []))));
    }

    private function index(): array
    {
        return Cache::remember(self::CACHE_KEY, now()->addHours(6), function () {
            $tools = collect(HomeController::tools())->map(fn (array $tool) => $this->prepare([
                'type' => 'tool',
                'title' => $tool['name'],
                'slug' => $tool['slug'],
                'description' => $tool['desc'],
                'category' => $tool['category'],
                'icon' => $tool['icon'],
                'url' => url('tools/'.$tool['slug']),
                'keywords' => $tool['keywords'] ?? '',
                'tags' => $tool['tags'] ?? [],
            ]));
            $articles = collect(BlogRepository::all())->map(fn (array $article) => $this->prepare([
                'type' => 'article',
                'title' => $article['title'],
                'slug' => $article['slug'],
                'description' => $article['excerpt'],
                'category' => $article['category'],
                'icon' => 'BLOG',
                'url' => route('blog.show', $article['slug']),
                'keywords' => $article['meta_description'] ?? '',
                'tags' => $article['tags'] ?? [],
            ]));
            $categories = collect(HomeController::categories())->map(function (array $category) {
                $label = str_contains($category['name'], 'Tools') ? $category['name'] : $category['name'].' Tools';

                return $this->prepare([
                    'type' => 'category',
                    'title' => $label,
                    'slug' => $category['slug'],
                    'description' => 'Browse '.$category['count'].' free '.$label.' on Toolexa.',
                    'category' => 'Category',
                    'icon' => 'CAT',
                    'url' => route('category.show', $category['slug']),
                    'keywords' => $category['name'].' '.$label,
                    'tags' => [],
                ]);
            });

            return $tools->concat($articles)->concat($categories)->values()->all();
        });
    }

    private function prepare(array $item): array
    {
        $item['_search'] = $this->normalize(implode(' ', [
            $item['title'], $item['slug'], $item['description'], $item['category'], $item['keywords'], implode(' ', (array) $item['tags']),
        ]));
        $item['_tokens'] = array_values(array_unique(explode(' ', $item['_search'])));
        unset($item['keywords'], $item['tags']);

        return $item;
    }

    private function score(string $query, array $item): float
    {
        $title = $this->normalize($item['title']);
        $slug = $this->normalize($item['slug']);
        $score = 0;

        if ($title === $query) {
            $score += 120;
        } elseif (str_starts_with($title, $query)) {
            $score += 85;
        } elseif (str_contains($title, $query)) {
            $score += 65;
        }

        if (str_contains($slug, $query)) {
            $score += 45;
        }

        if (str_contains($item['_search'], $query)) {
            $score += 35;
        }

        $queryTokens = array_values(array_filter(explode(' ', $query)));
        $matchedTokens = 0;
        $fuzzyScore = 0;

        foreach ($queryTokens as $queryToken) {
            $best = 0;
            foreach ($item['_tokens'] as $candidateToken) {
                if (str_starts_with($candidateToken, $queryToken)) {
                    $best = max($best, 1);
                    continue;
                }

                $length = max(mb_strlen($queryToken), mb_strlen($candidateToken));
                if ($length === 0) {
                    continue;
                }
                $similarity = 1 - (levenshtein($queryToken, $candidateToken) / $length);
                $best = max($best, $similarity);
            }

            if ($best >= .62) {
                $matchedTokens++;
                $fuzzyScore += $best * 30;
            }
        }

        if ($matchedTokens !== count($queryTokens)) {
            return $score > 0 ? $score : 0;
        }

        return $score + $fuzzyScore + ($item['type'] === 'tool' ? 3 : 0);
    }

    private function normalize(string $value): string
    {
        return trim(preg_replace('/\s+/', ' ', preg_replace('/[^\pL\pN]+/u', ' ', Str::lower($value))) ?? '');
    }
}

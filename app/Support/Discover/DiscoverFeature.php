<?php

namespace App\Support\Discover;

class DiscoverFeature
{
    public function __construct(
        public readonly string $key,
        public readonly string $name,
        public readonly string $slug,
        public readonly string $description,
        public readonly string $icon,
        public readonly string $badge,
        public readonly string $category,
        public readonly string $launchDate,
        public readonly bool $active,
        public readonly string $prompt,
        public readonly array $words,
        public readonly array $emojis,
        public readonly array $themes,
    ) {
    }

    public static function howPeopleSeeYou(): self
    {
        return new self(
            key: 'how_people_see_you',
            name: 'How People See You',
            slug: 'how-people-see-you',
            description: 'Create your personal link, share it with friends, and receive anonymous words that describe you.',
            icon: 'HP',
            badge: 'NEW',
            category: 'Friends',
            launchDate: '2026-07-03',
            active: true,
            prompt: 'Describe :name in three words',
            words: [
                'Helpful',
                'Funny',
                'Kind',
                'Creative',
                'Leader',
                'Smart',
                'Calm',
                'Positive',
                'Honest',
                'Confident',
                'Friendly',
                'Hardworking',
                'Motivating',
                'Trustworthy',
                'Creative Thinker',
                'Good Listener',
                'Supportive',
                'Stylish',
                'Disciplined',
                'Problem Solver',
            ],
            emojis: ['😀', '😎', '🔥', '❤️', '👏', '🚀', '💯'],
            themes: ['light', 'dark', 'colorful'],
        );
    }

    public static function catalog(): array
    {
        return [
            self::howPeopleSeeYou(),
        ];
    }

    public function isNew(): bool
    {
        $launchDate = \Carbon\CarbonImmutable::parse($this->launchDate);

        return $launchDate->lessThanOrEqualTo(now()) && $launchDate->diffInDays(now()) <= 30;
    }

    public function url(): string
    {
        return route('discover.feature.create', $this->slug);
    }

    public function demoUrl(): string
    {
        return route('discover.feature.demo', $this->slug);
    }

    public function promptFor(string $name): string
    {
        return str_replace(':name', $name, $this->prompt);
    }
}

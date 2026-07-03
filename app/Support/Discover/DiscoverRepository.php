<?php

namespace App\Support\Discover;

use Carbon\CarbonImmutable;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use RuntimeException;

class DiscoverRepository
{
    public function __construct(private readonly DiscoverFeature $feature)
    {
    }

    public static function default(): self
    {
        return new self(DiscoverFeature::howPeopleSeeYou());
    }

    public function feature(): DiscoverFeature
    {
        return $this->feature;
    }

    public function create(string $name, string $theme, ?UploadedFile $photo = null, bool $publicResults = false): array
    {
        $this->ensureDirectories();

        do {
            $id = Str::random(random_int(10, 12));
        } while ($this->exists($id));

        $entry = [
            'id' => $id,
            'feature' => $this->feature->key,
            'name' => $name,
            'theme' => in_array($theme, $this->feature->themes, true) ? $theme : 'light',
            'created_at' => now()->toIso8601String(),
            'owner_token' => Str::random(12),
            'public_results' => $publicResults,
            'photo' => null,
            'responses' => [],
        ];

        if ($photo) {
            $extension = strtolower($photo->getClientOriginalExtension() ?: $photo->extension() ?: 'jpg');
            $filename = $id.'.'.$extension;
            $photo->move($this->uploadsPath(), $filename);
            $entry['photo'] = 'uploads/'.$filename;
        }

        $this->write($id, $entry);

        return $entry;
    }

    public function find(string $id): ?array
    {
        if (!preg_match('/^[A-Za-z0-9]{10,12}$/', $id)) {
            return null;
        }

        $path = $this->entryPath($id);
        if (!File::exists($path)) {
            return null;
        }

        $contents = File::get($path);
        $entry = json_decode($contents, true);

        return is_array($entry) ? $entry : null;
    }

    public function photoPath(array $entry): ?string
    {
        if (empty($entry['photo']) || !is_string($entry['photo'])) {
            return null;
        }

        $path = $this->basePath().DIRECTORY_SEPARATOR.$entry['photo'];

        return str_starts_with(realpath($path) ?: '', realpath($this->basePath()) ?: '')
            && File::exists($path)
            ? $path
            : null;
    }

    public function appendResponse(string $id, array $response): array
    {
        $path = $this->entryPath($id);
        if (!File::exists($path)) {
            throw new RuntimeException('Discover entry not found.');
        }

        $handle = fopen($path, 'c+');
        if (!$handle) {
            throw new RuntimeException('Unable to open Discover entry.');
        }

        try {
            flock($handle, LOCK_EX);
            rewind($handle);
            $entry = json_decode(stream_get_contents($handle), true);
            if (!is_array($entry)) {
                throw new RuntimeException('Invalid Discover entry.');
            }

            $entry['responses'] = $entry['responses'] ?? [];
            $entry['responses'][] = $response;

            rewind($handle);
            ftruncate($handle, 0);
            fwrite($handle, json_encode($entry, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
            fflush($handle);
        } finally {
            flock($handle, LOCK_UN);
            fclose($handle);
        }

        return $entry;
    }

    public function recentlyResponded(array $entry, string $fingerprint, int $minutes = 3): bool
    {
        $cutoff = now()->subMinutes($minutes);

        foreach (array_reverse($entry['responses'] ?? []) as $response) {
            if (($response['fingerprint'] ?? null) !== $fingerprint) {
                continue;
            }

            $submittedAt = CarbonImmutable::parse($response['submitted_at'] ?? 'now');
            if ($submittedAt->greaterThan($cutoff)) {
                return true;
            }
        }

        return false;
    }

    public function analytics(array $entry): array
    {
        $responses = $entry['responses'] ?? [];
        $wordCounts = [];
        $emojiCounts = [];
        $messages = [];
        $days = [];

        foreach ($responses as $response) {
            foreach ($response['words'] ?? [] as $word) {
                $wordCounts[$word] = ($wordCounts[$word] ?? 0) + 1;
            }

            if (!empty($response['emoji'])) {
                $emojiCounts[$response['emoji']] = ($emojiCounts[$response['emoji']] ?? 0) + 1;
            }

            if (!empty($response['message'])) {
                $messages[] = [
                    'message' => $response['message'],
                    'submitted_at' => $response['submitted_at'] ?? null,
                    'emoji' => $response['emoji'] ?? null,
                ];
            }

            $day = substr((string) ($response['submitted_at'] ?? ''), 0, 10);
            if ($day) {
                $days[$day] = ($days[$day] ?? 0) + 1;
            }
        }

        arsort($wordCounts);
        arsort($emojiCounts);

        $createdAt = CarbonImmutable::parse($entry['created_at'] ?? now());
        $ageDays = max(1, $createdAt->diffInDays(now()) + 1);

        return [
            'total_responses' => count($responses),
            'word_counts' => $wordCounts,
            'emoji_counts' => $emojiCounts,
            'top_words' => array_slice($wordCounts, 0, 6, true),
            'top_emoji' => array_key_first($emojiCounts),
            'latest_messages' => array_slice(array_reverse($messages), 0, 8),
            'average_per_day' => round(count($responses) / $ageDays, 1),
            'daily_counts' => $days,
            'max_word_count' => max($wordCounts ?: [1]),
        ];
    }

    public function cleanupOlderThan(int $days): int
    {
        $this->ensureDirectories();
        $deleted = 0;
        $cutoff = now()->subDays($days);

        foreach (File::glob($this->entriesPath().DIRECTORY_SEPARATOR.'*.json') ?: [] as $path) {
            $entry = json_decode(File::get($path), true);
            $createdAt = CarbonImmutable::parse($entry['created_at'] ?? '@'.File::lastModified($path));

            if ($createdAt->lessThan($cutoff)) {
                if (is_array($entry) && !empty($entry['photo']) && is_string($entry['photo'])) {
                    $photoPath = $this->basePath().DIRECTORY_SEPARATOR.$entry['photo'];
                    if (File::exists($photoPath)) {
                        File::delete($photoPath);
                    }
                }

                File::delete($path);
                $deleted++;
            }
        }

        return $deleted;
    }

    private function exists(string $id): bool
    {
        return File::exists($this->entryPath($id));
    }

    private function write(string $id, array $entry): void
    {
        File::put($this->entryPath($id), json_encode($entry, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
    }

    private function ensureDirectories(): void
    {
        File::ensureDirectoryExists($this->entriesPath(), 0755, true);
        File::ensureDirectoryExists($this->uploadsPath(), 0755, true);
    }

    private function basePath(): string
    {
        return storage_path('app/discover');
    }

    private function entriesPath(): string
    {
        return $this->basePath().DIRECTORY_SEPARATOR.'entries';
    }

    private function uploadsPath(): string
    {
        return $this->basePath().DIRECTORY_SEPARATOR.'uploads';
    }

    private function entryPath(string $id): string
    {
        return $this->entriesPath().DIRECTORY_SEPARATOR.$id.'.json';
    }
}

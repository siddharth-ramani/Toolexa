<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class TextUtilityController extends Controller
{
    private array $slugs = [
        'word-counter',
        'character-counter',
        'remove-duplicate-lines',
        'remove-extra-spaces',
        'text-repeater',
        'base64-encoder',
        'base64-decoder',
        'url-encoder-decoder',
        'md5-hash-generator',
        'lorem-ipsum-generator',
    ];

    public function index(string $slug)
    {
        abort_unless(in_array($slug, $this->slugs, true), 404);

        return view('tools.text-utility', ['slug' => $slug]);
    }

    public function process(Request $request, string $slug)
    {
        abort_unless(in_array($slug, $this->slugs, true), 404);

        $validated = $request->validate($this->rules($slug));
        $result = match ($slug) {
            'word-counter' => $this->wordCounter($validated['text']),
            'character-counter' => $this->characterCounter($validated['text']),
            'remove-duplicate-lines' => $this->removeDuplicateLines($validated['text'], $request->boolean('case_sensitive')),
            'remove-extra-spaces' => $this->removeExtraSpaces($validated['text'], $request->boolean('remove_empty_lines')),
            'text-repeater' => $this->repeatText($validated),
            'base64-encoder' => $this->base64Encode($validated['text']),
            'base64-decoder' => $this->base64Decode($validated['text']),
            'url-encoder-decoder' => $this->urlEncodeDecode($validated),
            'md5-hash-generator' => $this->md5Hash($validated['text']),
            'lorem-ipsum-generator' => $this->loremIpsum($validated),
        };

        return view('tools.text-utility', [
            'slug' => $slug,
            'validated' => $validated,
            'result' => $result,
        ]);
    }

    private function rules(string $slug): array
    {
        $rules = $slug === 'lorem-ipsum-generator'
            ? []
            : ['text' => 'required|string|max:20000'];

        if ($slug === 'text-repeater') {
            $rules['times'] = 'required|integer|min:1|max:500';
            $rules['separator'] = 'required|string|in:space,new_line,comma,custom';
            $rules['custom_separator'] = 'nullable|string|max:100';
        }

        if ($slug === 'url-encoder-decoder') {
            $rules['operation'] = 'required|string|in:encode,decode';
        }

        if ($slug === 'lorem-ipsum-generator') {
            $rules['lorem_type'] = 'required|string|in:paragraphs,sentences,words';
            $rules['quantity'] = 'required|integer|min:1|max:100';
        }

        return $rules;
    }

    private function wordCounter(string $text): array
    {
        $plainText = trim($text);
        preg_match_all('/[\pL\pN]+(?:[\'-][\pL\pN]+)*/u', $plainText, $words);
        preg_match_all('/[^.!?]+[.!?]+|[^.!?]+$/u', $plainText, $sentences);
        $paragraphs = collect(preg_split('/\R{2,}/u', $plainText) ?: [])
            ->filter(fn ($paragraph) => trim($paragraph) !== '')
            ->count();
        $wordCount = count($words[0]);

        return [
            'type' => 'stats',
            'items' => [
                'Word Count' => $wordCount,
                'Character Count' => Str::length($text),
                'Characters Without Spaces' => Str::length(preg_replace('/\s+/u', '', $text) ?? ''),
                'Sentence Count' => collect($sentences[0])->filter(fn ($sentence) => trim($sentence) !== '')->count(),
                'Paragraph Count' => $paragraphs,
                'Reading Time' => $this->formatMinutes($wordCount / 200),
                'Speaking Time' => $this->formatMinutes($wordCount / 130),
            ],
        ];
    }

    private function characterCounter(string $text): array
    {
        preg_match_all('/\pL/u', $text, $letters);
        preg_match_all('/\pN/u', $text, $digits);
        preg_match_all('/\s/u', $text, $spaces);
        preg_match_all('/[^\pL\pN\s]/u', $text, $special);

        return [
            'type' => 'stats',
            'items' => [
                'Total Characters' => Str::length($text),
                'Characters Without Spaces' => Str::length(preg_replace('/\s+/u', '', $text) ?? ''),
                'Letters' => count($letters[0]),
                'Digits' => count($digits[0]),
                'Spaces' => count($spaces[0]),
                'Special Characters' => count($special[0]),
            ],
        ];
    }

    private function removeDuplicateLines(string $text, bool $caseSensitive): array
    {
        $seen = [];
        $lines = preg_split('/\R/u', $text) ?: [];
        $unique = [];

        foreach ($lines as $line) {
            $key = $caseSensitive ? $line : Str::lower($line);

            if (array_key_exists($key, $seen)) {
                continue;
            }

            $seen[$key] = true;
            $unique[] = $line;
        }

        return [
            'type' => 'text',
            'label' => 'Cleaned Text',
            'text' => implode("\n", $unique),
            'summary' => [
                'Original Lines' => count($lines),
                'Unique Lines' => count($unique),
                'Removed Lines' => max(0, count($lines) - count($unique)),
            ],
        ];
    }

    private function removeExtraSpaces(string $text, bool $removeEmptyLines): array
    {
        $lines = preg_split('/\R/u', $text) ?: [];
        $cleanedLines = array_map(function ($line) {
            return trim(preg_replace('/[ \t]+/u', ' ', $line) ?? '');
        }, $lines);

        if ($removeEmptyLines) {
            $cleanedLines = array_values(array_filter($cleanedLines, fn ($line) => $line !== ''));
        }

        return [
            'type' => 'text',
            'label' => 'Cleaned Text',
            'text' => implode("\n", $cleanedLines),
            'summary' => [
                'Original Characters' => Str::length($text),
                'Cleaned Characters' => Str::length(implode("\n", $cleanedLines)),
                'Lines' => count($cleanedLines),
            ],
        ];
    }

    private function repeatText(array $input): array
    {
        $separator = match ($input['separator']) {
            'space' => ' ',
            'new_line' => "\n",
            'comma' => ', ',
            'custom' => $input['custom_separator'] ?? '',
        };
        $times = (int) $input['times'];
        $output = implode($separator, array_fill(0, $times, $input['text']));

        return [
            'type' => 'text',
            'label' => 'Repeated Text',
            'text' => $output,
            'summary' => [
                'Repeat Count' => $times,
                'Output Characters' => Str::length($output),
                'Separator' => Str::headline($input['separator']),
            ],
        ];
    }

    private function base64Encode(string $text): array
    {
        return [
            'type' => 'text',
            'label' => 'Base64 Output',
            'text' => base64_encode($text),
            'summary' => [
                'Input Characters' => Str::length($text),
                'Output Characters' => Str::length(base64_encode($text)),
            ],
        ];
    }

    private function base64Decode(string $text): array
    {
        $decoded = base64_decode($text, true);

        if ($decoded === false) {
            throw ValidationException::withMessages([
                'text' => 'Please enter a valid Base64 string.',
            ]);
        }

        return [
            'type' => 'text',
            'label' => 'Decoded Text',
            'text' => $decoded,
            'summary' => [
                'Input Characters' => Str::length($text),
                'Output Characters' => Str::length($decoded),
            ],
        ];
    }

    private function urlEncodeDecode(array $input): array
    {
        $operation = $input['operation'];
        $output = $operation === 'encode'
            ? rawurlencode($input['text'])
            : rawurldecode($input['text']);

        return [
            'type' => 'text',
            'label' => $operation === 'encode' ? 'Encoded URL' : 'Decoded URL',
            'text' => $output,
            'summary' => [
                'Operation' => Str::headline($operation),
                'Input Characters' => Str::length($input['text']),
                'Output Characters' => Str::length($output),
            ],
        ];
    }

    private function md5Hash(string $text): array
    {
        return [
            'type' => 'text',
            'label' => 'MD5 Hash',
            'text' => md5($text),
            'summary' => [
                'Input Characters' => Str::length($text),
                'Hash Length' => 32,
            ],
        ];
    }

    private function loremIpsum(array $input): array
    {
        $words = [
            'lorem', 'ipsum', 'dolor', 'sit', 'amet', 'consectetur', 'adipiscing', 'elit',
            'sed', 'do', 'eiusmod', 'tempor', 'incididunt', 'ut', 'labore', 'et', 'dolore',
            'magna', 'aliqua', 'enim', 'ad', 'minim', 'veniam', 'quis', 'nostrud',
            'exercitation', 'ullamco', 'laboris', 'nisi', 'aliquip', 'commodo', 'consequat',
        ];
        $quantity = (int) $input['quantity'];
        $type = $input['lorem_type'];

        $output = match ($type) {
            'words' => $this->loremWords($words, $quantity),
            'sentences' => $this->loremSentences($words, $quantity),
            'paragraphs' => $this->loremParagraphs($words, $quantity),
        };

        return [
            'type' => 'text',
            'label' => 'Generated Lorem Ipsum',
            'text' => $output,
            'summary' => [
                'Type' => Str::headline($type),
                'Quantity' => $quantity,
                'Output Characters' => Str::length($output),
            ],
        ];
    }

    private function loremWords(array $words, int $quantity): string
    {
        $output = [];

        for ($index = 0; $index < $quantity; $index++) {
            $output[] = $words[$index % count($words)];
        }

        return implode(' ', $output);
    }

    private function loremSentences(array $words, int $quantity): string
    {
        $sentences = [];

        for ($index = 0; $index < $quantity; $index++) {
            $sentence = $this->loremWords($words, 12);
            $sentences[] = ucfirst($sentence).'.';
        }

        return implode(' ', $sentences);
    }

    private function loremParagraphs(array $words, int $quantity): string
    {
        $paragraphs = [];

        for ($index = 0; $index < $quantity; $index++) {
            $paragraphs[] = $this->loremSentences($words, 4);
        }

        return implode("\n\n", $paragraphs);
    }

    private function formatMinutes(float $minutes): string
    {
        if ($minutes <= 0) {
            return '0 min';
        }

        if ($minutes < 1) {
            return '< 1 min';
        }

        return (string) ceil($minutes).' min';
    }
}

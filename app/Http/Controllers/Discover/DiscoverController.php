<?php

namespace App\Http\Controllers\Discover;

use App\Http\Controllers\Controller;
use App\Support\Discover\DiscoverRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class DiscoverController extends Controller
{
    private DiscoverRepository $discover;

    public function __construct()
    {
        $this->discover = DiscoverRepository::default();
    }

    public function create()
    {
        $features = $this->discover->feature()::catalog();

        return view('discover.index', [
            'features' => $features,
            'feature' => $this->discover->feature(),
            'canonicalUrl' => route('discover.index'),
            'schemaJsonLd' => [
                [
                    '@context' => 'https://schema.org',
                    '@type' => 'WebPage',
                    'name' => 'Discover Fun Interactive Experiences | Toolexa',
                    'description' => 'Create fun shareable experiences, collect anonymous responses, and enjoy interactive Discover features only on Toolexa.',
                    'url' => route('discover.index'),
                ],
                [
                    '@context' => 'https://schema.org',
                    '@type' => 'BreadcrumbList',
                    'itemListElement' => [
                        [
                            '@type' => 'ListItem',
                            'position' => 1,
                            'name' => 'Home',
                            'item' => url('/'),
                        ],
                        [
                            '@type' => 'ListItem',
                            'position' => 2,
                            'name' => 'Discover',
                            'item' => route('discover.index'),
                        ],
                    ],
                ],
            ],
        ]);
    }

    public function feature(string $slug)
    {
        abort_unless($slug === $this->discover->feature()->slug, 404);

        return view('discover.create', [
            'feature' => $this->discover->feature(),
            'canonicalUrl' => route('discover.feature.create', $slug),
        ]);
    }

    public function demo(string $slug)
    {
        abort_unless($slug === $this->discover->feature()->slug, 404);

        $entry = [
            'id' => 'DemoPreview',
            'name' => 'Rahul',
            'theme' => 'colorful',
            'created_at' => now()->subDays(4)->toIso8601String(),
            'responses' => [
                ['words' => ['Helpful', 'Funny', 'Kind'], 'emoji' => '😀', 'message' => 'Always positive and fun to talk with.', 'submitted_at' => now()->subDays(3)->toIso8601String()],
                ['words' => ['Creative', 'Leader', 'Smart'], 'emoji' => '🚀', 'message' => 'Big ideas, calm execution.', 'submitted_at' => now()->subDays(2)->toIso8601String()],
                ['words' => ['Supportive', 'Good Listener', 'Trustworthy'], 'emoji' => '❤️', 'message' => 'The friend everyone needs.', 'submitted_at' => now()->subDay()->toIso8601String()],
                ['words' => ['Confident', 'Problem Solver', 'Motivating'], 'emoji' => '🔥', 'message' => 'Turns problems into plans.', 'submitted_at' => now()->toIso8601String()],
            ],
        ];

        return view('discover.results', [
            'entry' => $entry,
            'feature' => $this->discover->feature(),
            'analytics' => $this->discover->analytics($entry),
            'shareUrl' => route('discover.feature.create', $slug),
            'robotsMeta' => 'noindex,nofollow',
            'canonicalUrl' => route('discover.feature.demo', $slug),
            'isDemo' => true,
        ]);
    }

    public function store(Request $request, string $slug)
    {
        $feature = $this->discover->feature();
        abort_unless($slug === $feature->slug, 404);

        $validated = $request->validate([
            'name' => ['required', 'string', 'min:2', 'max:40', 'regex:/^[\pL\pM\s.\'-]+$/u'],
            'theme' => ['required', Rule::in($feature->themes)],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $entry = $this->discover->create(
            trim($validated['name']),
            $validated['theme'],
            $request->file('photo')
        );

        $this->rememberOwnerToken($entry['id'], $entry['owner_token']);

        return redirect()->route('discover.share', [$entry['id'], $entry['owner_token']]);
    }

    public function storeDefault(Request $request)
    {
        return $this->store($request, $this->discover->feature()->slug);
    }

    public function show(string $id)
    {
        $entry = $this->discover->find($id);
        abort_if(!$entry, 404);

        return view('discover.respond', [
            'entry' => $entry,
            'feature' => $this->discover->feature(),
            'robotsMeta' => 'noindex,nofollow',
            'canonicalUrl' => route('discover.show', $entry['id']),
            'socialImageUrl' => !empty($entry['photo']) ? route('discover.photo', $entry['id']) : null,
        ]);
    }

    public function submit(Request $request, string $id)
    {
        $entry = $this->discover->find($id);
        abort_if(!$entry, 404);

        $feature = $this->discover->feature();
        $validated = $request->validate([
            'words' => ['required', 'array', 'size:3'],
            'words.*' => ['required', 'string', Rule::in($feature->words)],
            'emoji' => ['nullable', 'string', Rule::in($feature->emojis)],
            'message' => ['nullable', 'string', 'max:120'],
        ]);

        if (count(array_unique($validated['words'])) !== 3) {
            return back()->withErrors(['words' => 'Please choose three different words.'])->withInput();
        }

        $browserId = $request->cookie('discover_browser_id') ?: bin2hex(random_bytes(16));
        $fingerprint = hash('sha256', $request->ip().'|'.$request->userAgent().'|'.$browserId.'|'.$id);

        if ($this->discover->recentlyResponded($entry, $fingerprint)) {
            return back()->withErrors(['rate' => 'You already responded recently. Please wait a few minutes.']);
        }

        $this->discover->appendResponse($id, [
            'words' => array_values($validated['words']),
            'emoji' => $validated['emoji'] ?? null,
            'message' => trim($validated['message'] ?? ''),
            'submitted_at' => now()->toIso8601String(),
            'fingerprint' => $fingerprint,
        ]);

        return redirect()
            ->route('discover.thanks', $id)
            ->withCookie(Cookie::make('discover_browser_id', $browserId, 60 * 24 * 365, null, null, false, true, false, 'Lax'));
    }

    public function thanks(string $id)
    {
        $entry = $this->discover->find($id);
        abort_if(!$entry, 404);

        $ownerToken = session('discover_owner_tokens.'.$entry['id']);
        $resultsUrl = is_string($ownerToken) && hash_equals($entry['owner_token'] ?? '', $ownerToken)
            ? route('discover.results', [$entry['id'], $ownerToken])
            : null;

        return view('discover.thanks', [
            'entry' => $entry,
            'feature' => $this->discover->feature(),
            'resultsUrl' => $resultsUrl,
            'robotsMeta' => 'noindex,nofollow',
            'canonicalUrl' => route('discover.show', $entry['id']),
        ]);
    }

    public function share(string $id, string $token)
    {
        $entry = $this->discover->find($id);
        abort_if(!$entry || !hash_equals($entry['owner_token'] ?? '', $token), 404);

        $this->rememberOwnerToken($entry['id'], $token);

        return view('discover.share', [
            'entry' => $entry,
            'feature' => $this->discover->feature(),
            'shareUrl' => route('discover.show', $entry['id']),
            'resultsUrl' => route('discover.results', [$entry['id'], $token]),
            'shareTitle' => 'Describe '.$entry['name'].' in three words',
            'shareCaption' => "Describe {$entry['name']} in three words 👀\n\nChoose anonymously and help {$entry['name']} discover how friends see them.\n\n{$this->discover->feature()->promptFor($entry['name'])}\n",
            'robotsMeta' => 'noindex,nofollow',
            'canonicalUrl' => route('discover.share', [$entry['id'], $token]),
        ]);
    }

    public function results(string $id, string $token)
    {
        $entry = $this->discover->find($id);
        abort_if(!$entry || !hash_equals($entry['owner_token'] ?? '', $token), 404);

        $this->rememberOwnerToken($entry['id'], $token);

        return view('discover.results', [
            'entry' => $entry,
            'feature' => $this->discover->feature(),
            'analytics' => $this->discover->analytics($entry),
            'shareUrl' => route('discover.show', $entry['id']),
            'robotsMeta' => 'noindex,nofollow',
            'canonicalUrl' => route('discover.results', [$entry['id'], $token]),
        ]);
    }

    public function photo(string $id): Response
    {
        $entry = $this->discover->find($id);
        abort_if(!$entry, 404);

        $path = $this->discover->photoPath($entry);
        abort_if(!$path, 404);

        return response()->file($path, [
            'Cache-Control' => 'private, max-age=3600',
        ]);
    }

    private function rememberOwnerToken(string $id, string $token): void
    {
        session()->put('discover_owner_tokens.'.$id, $token);
    }
}

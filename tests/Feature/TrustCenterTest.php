<?php

namespace Tests\Feature;

use Tests\TestCase;

class TrustCenterTest extends TestCase
{
    public function test_trust_center_contains_complete_transparency_content(): void
    {
        $response = $this->get('/trust')->assertOk();

        foreach ([
            'Trust & Transparency',
            'Our Mission',
            'How We Build Our Tools',
            'Content Review Process',
            'Accuracy Policy',
            'Editorial Standards',
            'Privacy First',
            'Supported Browsers',
            'Open Source & Standards',
            'Feedback and Corrections',
            'Frequently Asked Questions',
            'Trust Center Last Updated',
        ] as $section) {
            $response->assertSee($section);
        }

        $response
            ->assertSee(route('page.show', 'contact'), false)
            ->assertSee(route('trust.editorial-policy'), false)
            ->assertSee(route('trust.accuracy-policy'), false);
    }

    public function test_each_trust_policy_has_unique_seo_and_schema(): void
    {
        $pages = [
            '/trust' => 'Trust Center | How Toolexa Builds and Reviews Tools',
            '/editorial-policy' => 'Editorial Policy | Toolexa Content Standards',
            '/accuracy-policy' => 'Accuracy Policy | Toolexa Calculator Standards',
            '/how-we-test-tools' => 'How We Test Tools | Toolexa Quality Process',
        ];

        foreach ($pages as $url => $title) {
            $response = $this->get($url)->assertOk();
            $response
                ->assertSee('<title>'.$title.'</title>', false)
                ->assertSee('<link rel="canonical"', false)
                ->assertSee('"@type":"BreadcrumbList"', false)
                ->assertSee('property="og:title"', false)
                ->assertSee('name="twitter:title"', false);
        }
    }

    public function test_trust_center_has_ten_or_more_faqs_and_faq_schema(): void
    {
        $response = $this->get('/trust')->assertOk();
        $html = $response->getContent();

        $this->assertGreaterThanOrEqual(10, substr_count($html, '<details>'));
        $this->assertStringContainsString('"@type":"FAQPage"', $html);
        $this->assertStringContainsString('"@type":"Question"', $html);
    }

    public function test_trust_pages_are_linked_and_in_the_sitemap(): void
    {
        $footer = $this->get('/')->assertOk();
        $sitemap = $this->get('/sitemap.xml')->assertOk();

        foreach (array_keys(config('trust.pages')) as $page) {
            $url = route('trust.'.$page);
            $sitemap->assertSee($url, false);
        }

        $footer->assertSee(route('trust.trust'), false)->assertSee('Trust Center');
    }
}

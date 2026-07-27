<?php

namespace Tests\Feature;

use Tests\TestCase;

class AuthorReviewerSystemTest extends TestCase
{
    public function test_author_profile_contains_identity_expertise_and_recent_content(): void
    {
        $this->get('/authors/toolexa-editorial-team')
            ->assertOk()
            ->assertSee('Toolexa Editorial Team')
            ->assertSee('Biography')
            ->assertSee('Mission')
            ->assertSee('Areas of Expertise')
            ->assertSee('Latest Articles')
            ->assertSee('Latest Tools Reviewed')
            ->assertSee('Latest Comparisons')
            ->assertSee('"@type":"ProfilePage"', false)
            ->assertSee('"@type":"Person"', false)
            ->assertSee('<link rel="canonical"', false);
    }

    public function test_blog_post_displays_complete_author_review_metadata_and_history(): void
    {
        $response = $this->get('/blog/how-to-calculate-gst-in-india')->assertOk();
        $html = $response->getContent();

        $response
            ->assertSee('Written by')
            ->assertSee('Toolexa Editorial Team')
            ->assertSee('Reviewed by')
            ->assertSee('Toolexa Review Team')
            ->assertSee('Published')
            ->assertSee('Updated')
            ->assertSee('July 2026')
            ->assertSee('min read')
            ->assertSee('Reviewed for accuracy')
            ->assertSee('Educational purposes')
            ->assertSee('Content history')
            ->assertSee('View Profile')
            ->assertSee('"reviewedBy"', false);

        $this->assertLessThan(strpos($html, '<p>'.$this->articleExcerpt().'</p>'), strpos($html, 'Written by'));
    }

    public function test_tool_metadata_appears_below_title_with_person_schema(): void
    {
        $html = $this->get('/tools/emi-calculator')
            ->assertOk()
            ->assertSee('Verified by')
            ->assertSee('Toolexa Editorial Team')
            ->assertSee('Finance')
            ->assertSee('min read')
            ->assertSee('"@type":"Person"', false)
            ->assertSee('"reviewedBy"', false)
            ->getContent();

        $this->assertLessThan(strpos($html, 'Verified by'), strpos($html, '<h1>EMI Calculator</h1>'));
    }

    public function test_comparison_displays_author_reviewer_and_updated_date(): void
    {
        $this->get('/compare/jpg-vs-png')
            ->assertOk()
            ->assertSee('Written by')
            ->assertSee('Reviewed by')
            ->assertSee('Updated')
            ->assertSee('July 2026')
            ->assertSee('"reviewedBy"', false)
            ->assertSee('"@type":"Person"', false);
    }

    public function test_footer_and_sitemap_link_editorial_resources(): void
    {
        $home = $this->get('/')->assertOk();
        $home
            ->assertSee(route('authors.show', 'toolexa-editorial-team'), false)
            ->assertSee('Editorial Policy')
            ->assertSee('Trust Center')
            ->assertSee('How We Test Tools')
            ->assertSee('Accuracy Policy');

        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertSee(route('authors.show', 'toolexa-editorial-team'), false);
    }

    private function articleExcerpt(): string
    {
        return \App\Support\BlogRepository::find('how-to-calculate-gst-in-india')['excerpt'];
    }
}

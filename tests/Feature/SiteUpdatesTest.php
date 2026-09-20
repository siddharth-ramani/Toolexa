<?php

namespace Tests\Feature;

use Tests\TestCase;

class SiteUpdatesTest extends TestCase
{
    public function test_public_update_log_is_complete_and_discoverable(): void
    {
        $this->get('/updates')
            ->assertOk()
            ->assertSee('Toolexa Site Updates')
            ->assertSee('Quality and transparency review')
            ->assertSee('Report an issue or request a tool')
            ->assertSee('CollectionPage')
            ->assertSee('BreadcrumbList');

        $this->get('/')
            ->assertOk()
            ->assertSee(route('updates.index'), false)
            ->assertDontSee('Newsletter delivery will be connected soon.');

        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertSee(route('updates.index'), false);
    }
}

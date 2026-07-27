<?php

namespace Tests\Feature;

use App\Services\SiteAudit\AuditExportService;
use App\Services\SiteAudit\PageInventoryService;
use App\Services\SiteAudit\SiteAuditService;
use Mockery;
use Tests\TestCase;

class SiteAuditEngineTest extends TestCase
{
    public function test_admin_audit_routes_are_private_and_not_publicly_discoverable(): void
    {
        config()->set('site_audit.admin.username', 'audit-admin');
        config()->set('site_audit.admin.password', 'strong-secret');

        $this->get('/admin/site-audit')
            ->assertUnauthorized()
            ->assertHeader('WWW-Authenticate')
            ->assertHeader('X-Robots-Tag', 'noindex, nofollow');

        $this->get('/sitemap.xml')->assertDontSee('/admin/site-audit');
        $this->get('/')->assertDontSee('/admin/site-audit');
    }

    public function test_real_audit_scans_the_complete_current_inventory(): void
    {
        config()->set('cache.default', 'array');
        config()->set('site_audit.external_link_checks', false);

        $inventory = app(PageInventoryService::class)->all();
        $report = app(SiteAuditService::class)->report(true);

        $this->assertCount(count($inventory), $report['pages']);
        $this->assertSame(count($inventory), $report['summary']['total_pages']);
        $this->assertGreaterThan(0, $report['summary']['average_word_count']);
        $this->assertGreaterThan(0, $report['summary']['average_internal_links']);

        foreach ($report['pages'] as $page) {
            $this->assertIsInt($page['score'], $page['path']);
            $this->assertGreaterThanOrEqual(0, $page['score'], $page['path']);
            $this->assertLessThanOrEqual(100, $page['score'], $page['path']);
            $this->assertSame([
                'metadata', 'eeat', 'content', 'internal_linking', 'structured_data',
                'accessibility', 'performance', 'trust', 'broken_links', 'duplicates',
            ], array_keys($page['modules']), $page['path']);
            $this->assertArrayHasKey('word_count', $page['metrics']);
            $this->assertArrayHasKey('uniqueness_score', $page['metrics']);
            $this->assertArrayHasKey('content_depth_score', $page['metrics']);
            $this->assertArrayHasKey('recommendations', $page);
        }
    }

    public function test_authenticated_dashboard_renders_filters_scores_and_recommendations(): void
    {
        $this->bindAuditFixture();

        $this->withBasicAuth('audit-admin', 'strong-secret')
            ->get('/admin/site-audit?health=warnings&type=tool')
            ->assertOk()
            ->assertHeader('X-Robots-Tag', 'noindex, nofollow')
            ->assertSee('Site-wide E-E-A-T')
            ->assertSee('Total Pages')
            ->assertSee('Average Score')
            ->assertSee('Page Health Reports')
            ->assertSee('GST Calculator')
            ->assertSee('Actionable Recommendations')
            ->assertSee('Official References');
    }

    public function test_manual_refresh_requires_post_and_rebuilds_report(): void
    {
        $mock = $this->bindAuditFixture();
        $mock->shouldReceive('report')->once()->with(true)->andReturn($this->fixture());

        $this->withBasicAuth('audit-admin', 'strong-secret')
            ->post('/admin/site-audit/refresh')
            ->assertRedirect(route('admin.site-audit.index'));

        $this->withBasicAuth('audit-admin', 'strong-secret')
            ->get('/admin/site-audit/refresh')
            ->assertMethodNotAllowed();
    }

    public function test_csv_excel_and_pdf_exports_are_real_downloads(): void
    {
        $this->bindAuditFixture();

        $this->withBasicAuth('audit-admin', 'strong-secret')
            ->get('/admin/site-audit/export/csv')
            ->assertOk()
            ->assertHeader('content-type', 'text/csv; charset=UTF-8');

        $this->withBasicAuth('audit-admin', 'strong-secret')
            ->get('/admin/site-audit/export/excel')
            ->assertOk()
            ->assertHeader('content-type', 'application/vnd.ms-excel; charset=UTF-8')
            ->assertSee('GST Calculator');

        $pdf = $this->withBasicAuth('audit-admin', 'strong-secret')
            ->get('/admin/site-audit/export/pdf')
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf');
        $this->assertStringStartsWith('%PDF-1.4', $pdf->getContent());
    }

    private function bindAuditFixture(): Mockery\MockInterface
    {
        config()->set('site_audit.admin.username', 'audit-admin');
        config()->set('site_audit.admin.password', 'strong-secret');
        $mock = Mockery::mock(SiteAuditService::class);
        $mock->shouldReceive('report')->zeroOrMoreTimes()->withNoArgs()->andReturn($this->fixture());
        $this->app->instance(SiteAuditService::class, $mock);

        return $mock;
    }

    private function fixture(): array
    {
        $checks = [['label' => 'Official References', 'pass' => false, 'detail' => '0 official references']];
        $modules = [
            'metadata' => $checks, 'eeat' => $checks, 'content' => $checks, 'internal_linking' => $checks,
            'structured_data' => $checks, 'accessibility' => $checks, 'performance' => $checks,
            'trust' => $checks, 'broken_links' => $checks, 'duplicates' => $checks,
        ];
        $page = [
            'type' => 'tool', 'name' => 'GST Calculator', 'path' => '/tools/gst-calculator',
            'url' => url('/tools/gst-calculator'), 'score' => 84, 'health' => 'Good',
            'metrics' => ['word_count' => 1800, 'heading_count' => 18, 'image_count' => 1, 'faq_count' => 10, 'internal_links' => 12, 'external_references' => 0, 'reading_time' => 8, 'uniqueness_score' => 91, 'content_depth_score' => 95],
            'modules' => $modules,
            'module_scores' => array_fill_keys(array_keys($modules), 84),
            'recommendations' => [['module' => 'Content', 'issue' => 'Official References', 'action' => 'Add GST Portal reference.']],
            'broken_links' => [],
        ];

        return [
            'generated_at' => now()->toIso8601String(), 'duration_ms' => 120, 'pages' => [$page],
            'summary' => ['total_pages' => 1, 'healthy_pages' => 0, 'warnings' => 1, 'critical_issues' => 0, 'average_score' => 84, 'average_word_count' => 1800, 'average_internal_links' => 12, 'average_faq_count' => 10],
            'duplicate_groups' => [], 'external_links_checked' => 3,
        ];
    }
}

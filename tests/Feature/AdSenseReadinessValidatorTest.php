<?php

namespace Tests\Feature;

use App\Services\AdSense\AdSenseReadinessService;
use Illuminate\Support\Facades\Storage;
use Mockery;
use Tests\TestCase;

class AdSenseReadinessValidatorTest extends TestCase
{
    public function test_validator_is_private_and_not_publicly_discoverable(): void
    {
        config()->set('site_audit.admin.username', 'audit-admin');
        config()->set('site_audit.admin.password', 'strong-secret');

        $this->get('/admin/adsense-validator')
            ->assertUnauthorized()
            ->assertHeader('X-Robots-Tag', 'noindex, nofollow');

        $this->get('/sitemap.xml')->assertDontSee('/admin/adsense-validator');
        $this->get('/')->assertDontSee('/admin/adsense-validator');
    }

    public function test_real_validator_uses_page_audit_evidence_and_bounded_scores(): void
    {
        Storage::fake('local');
        config()->set('cache.default', 'array');
        config()->set('site_audit.external_link_checks', false);

        $report = app(AdSenseReadinessService::class)->report(true);

        $this->assertGreaterThanOrEqual(0, $report['score']);
        $this->assertLessThanOrEqual(100, $report['score']);
        $this->assertContains($report['status'], ['ready', 'almost_ready', 'not_ready']);
        $this->assertSame([
            'trust', 'content', 'tool_quality', 'blog_quality', 'comparison_quality',
            'topic_quality', 'technical_seo', 'performance', 'accessibility', 'mobile',
            'internal_linking', 'policy_compliance',
        ], array_keys($report['modules']));
        $this->assertSame($report['summary']['total_pages'], count($report['pages']));
        $this->assertNotEmpty($report['modules']['technical_seo']);
        $this->assertNotEmpty($report['history']);
        Storage::disk('local')->assertExists('private/adsense-validator-history.json');
    }

    public function test_dashboard_displays_status_modules_actions_and_history(): void
    {
        $this->bindFixture();

        $this->withBasicAuth('audit-admin', 'strong-secret')
            ->get('/admin/adsense-validator')
            ->assertOk()
            ->assertHeader('X-Robots-Tag', 'noindex, nofollow')
            ->assertSee('AdSense Readiness Validator')
            ->assertSee('Almost Ready')
            ->assertSee('Validation Modules')
            ->assertSee('Action Items')
            ->assertSee('Audit History')
            ->assertSee('Missing official references');
    }

    public function test_refresh_is_post_only_and_exports_are_downloadable(): void
    {
        $mock = $this->bindFixture();
        $mock->shouldReceive('report')->once()->with(true)->andReturn($this->fixture());

        $this->withBasicAuth('audit-admin', 'strong-secret')
            ->post('/admin/adsense-validator/refresh')
            ->assertRedirect(route('admin.adsense-validator.index'));
        $this->withBasicAuth('audit-admin', 'strong-secret')
            ->get('/admin/adsense-validator/refresh')
            ->assertMethodNotAllowed();

        $this->withBasicAuth('audit-admin', 'strong-secret')
            ->get('/admin/adsense-validator/export/csv')
            ->assertOk()
            ->assertHeader('content-type', 'text/csv; charset=UTF-8');
        $this->withBasicAuth('audit-admin', 'strong-secret')
            ->get('/admin/adsense-validator/export/excel')
            ->assertOk()
            ->assertHeader('content-type', 'application/vnd.ms-excel; charset=UTF-8');
        $pdf = $this->withBasicAuth('audit-admin', 'strong-secret')
            ->get('/admin/adsense-validator/export/pdf')
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf');
        $this->assertStringStartsWith('%PDF-1.4', $pdf->getContent());
    }

    private function bindFixture(): Mockery\MockInterface
    {
        config()->set('site_audit.admin.username', 'audit-admin');
        config()->set('site_audit.admin.password', 'strong-secret');
        $mock = Mockery::mock(AdSenseReadinessService::class);
        $mock->shouldReceive('report')->zeroOrMoreTimes()->withNoArgs()->andReturn($this->fixture());
        $this->app->instance(AdSenseReadinessService::class, $mock);

        return $mock;
    }

    private function fixture(): array
    {
        $check = ['label' => 'Official references present', 'pass' => false, 'detail' => '1 page affected', 'pages' => ['/tools/gst-calculator']];
        $modules = array_fill_keys([
            'trust', 'content', 'tool_quality', 'blog_quality', 'comparison_quality',
            'topic_quality', 'technical_seo', 'performance', 'accessibility', 'mobile',
            'internal_linking', 'policy_compliance',
        ], [$check]);

        return [
            'generated_at' => now()->toIso8601String(), 'source_generated_at' => now()->toIso8601String(),
            'score' => 84, 'status' => 'almost_ready', 'status_label' => 'Almost Ready',
            'status_reason' => 'Resolve the remaining evidence gaps.',
            'modules' => $modules, 'module_scores' => array_fill_keys(array_keys($modules), 84),
            'actions' => [[
                'priority' => 'High', 'module' => 'Content', 'issue' => 'Missing official references',
                'action' => 'Add official references.', 'pages' => ['/tools/gst-calculator'], 'affected' => 1,
            ]],
            'summary' => ['total_pages' => 1, 'healthy_pages' => 0, 'critical_issues' => 0, 'warnings' => 1, 'passed_checks' => 10, 'total_checks' => 12],
            'pages' => [['name' => 'GST Calculator', 'path' => '/tools/gst-calculator', 'type' => 'tool', 'score' => 84, 'health' => 'Good', 'failed_checks' => 1, 'word_count' => 1800]],
            'history' => [['generated_at' => now()->toIso8601String(), 'score' => 84, 'status' => 'almost_ready', 'critical_issues' => 0, 'warnings' => 1, 'healthy_pages' => 0]],
        ];
    }
}

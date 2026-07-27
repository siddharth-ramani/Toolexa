@extends('layouts.admin')

@section('title', 'Site-wide E-E-A-T Audit | Toolexa Admin')

@section('content')
    <header class="audit-dashboard-hero">
        <div>
            <span>Internal quality control</span>
            <h1>Site-wide E-E-A-T & Structured Data Audit</h1>
            <p>Generated {{ \Carbon\Carbon::parse($report['generated_at'])->format('M j, Y g:i A') }} from actual rendered page output in {{ number_format($report['duration_ms']) }} ms.</p>
        </div>
        <div class="audit-header-actions">
            <form method="POST" action="{{ route('admin.site-audit.refresh') }}">@csrf<button class="btn btn-primary" type="submit">Refresh Audit</button></form>
            <a class="btn" href="{{ route('admin.site-audit.export', array_merge(['format' => 'csv'], request()->only('health', 'type', 'q'))) }}">CSV</a>
            <a class="btn" href="{{ route('admin.site-audit.export', array_merge(['format' => 'excel'], request()->only('health', 'type', 'q'))) }}">Excel</a>
            <a class="btn" href="{{ route('admin.site-audit.export', array_merge(['format' => 'pdf'], request()->only('health', 'type', 'q'))) }}">PDF</a>
        </div>
    </header>

    @if(session('status'))<p class="audit-flash" role="status">{{ session('status') }}</p>@endif

    <section class="audit-summary-grid" aria-label="Site audit summary">
        <x-audit.summary-card label="Total Pages" :value="$report['summary']['total_pages']" />
        <x-audit.summary-card label="Healthy Pages" :value="$report['summary']['healthy_pages']" tone="good" />
        <x-audit.summary-card label="Warnings" :value="$report['summary']['warnings']" tone="warning" />
        <x-audit.summary-card label="Critical Issues" :value="$report['summary']['critical_issues']" tone="critical" />
        <x-audit.summary-card label="Average Score" :value="$report['summary']['average_score'].'/100'" tone="score" />
        <x-audit.summary-card label="Average Words" :value="number_format($report['summary']['average_word_count'])" />
        <x-audit.summary-card label="Average Internal Links" :value="$report['summary']['average_internal_links']" />
        <x-audit.summary-card label="Average FAQs" :value="$report['summary']['average_faq_count']" />
    </section>

    <section class="audit-overview-panel">
        <div class="audit-donut" style="--audit-score: {{ $report['summary']['average_score'] }}"><strong>{{ $report['summary']['average_score'] }}</strong><span>Average health</span></div>
        <div>
            <h2>Current site health</h2>
            <p>{{ $report['external_links_checked'] }} unique external destinations were checked. {{ count($report['duplicate_groups']) }} duplicate metadata groups were detected.</p>
            <div class="audit-legend"><span><i class="excellent"></i>Excellent 90–100</span><span><i class="good"></i>Good 75–89</span><span><i class="warning"></i>Needs Improvement 50–74</span><span><i class="critical"></i>Critical 0–49</span></div>
        </div>
    </section>

    <form class="audit-filters" method="GET" action="{{ route('admin.site-audit.index') }}">
        <label><span>Page or URL</span><input class="form-control" type="search" name="q" value="{{ $filters['q'] }}" placeholder="Search report"></label>
        <label><span>Health</span><select class="form-control" name="health"><option value="">All scores</option><option value="critical" @selected($filters['health'] === 'critical')>Critical</option><option value="warnings" @selected($filters['health'] === 'warnings')>Warnings</option><option value="excellent" @selected($filters['health'] === 'excellent')>Excellent</option></select></label>
        <label><span>Page type</span><select class="form-control" name="type"><option value="">All page types</option>@foreach($types as $type)<option value="{{ $type }}" @selected($filters['type'] === $type)>{{ \Illuminate\Support\Str::headline($type) }}</option>@endforeach</select></label>
        <button class="btn btn-primary" type="submit">Apply Filters</button>
        <a class="btn" href="{{ route('admin.site-audit.index') }}">Reset</a>
    </form>

    <section class="audit-page-list" aria-labelledby="audit-pages-heading">
        <div class="audit-list-heading"><div><span>{{ $pages->total() }} matching pages</span><h2 id="audit-pages-heading">Page Health Reports</h2></div></div>
        @forelse($pages as $page)
            <details class="audit-page-card">
                <summary>
                    <x-audit.score :score="$page['score']" :health="$page['health']" />
                    <div class="audit-page-identity"><span>{{ \Illuminate\Support\Str::headline($page['type']) }}</span><strong>{{ $page['name'] }}</strong><small>{{ $page['path'] }}</small></div>
                    <div class="audit-page-metrics"><span><b>{{ number_format($page['metrics']['word_count']) }}</b> words</span><span><b>{{ $page['metrics']['internal_links'] }}</b> links</span><span><b>{{ $page['metrics']['faq_count'] }}</b> FAQs</span><span><b>{{ count($page['recommendations']) }}</b> actions</span></div>
                    <span class="audit-expand">Inspect</span>
                </summary>
                <div class="audit-page-detail">
                    <div class="audit-module-grid">
                        @foreach($page['modules'] as $module => $checks)
                            <x-audit.module :name="$module" :score="$page['module_scores'][$module]" :checks="$checks" />
                        @endforeach
                    </div>
                    <section class="audit-recommendations">
                        <h3>Actionable Recommendations</h3>
                        @if(count($page['recommendations']))
                            <ol>@foreach($page['recommendations'] as $recommendation)<li><span>{{ $recommendation['module'] }}</span><strong>{{ $recommendation['issue'] }}</strong><p>{{ $recommendation['action'] }}</p></li>@endforeach</ol>
                        @else
                            <p>No priority recommendations were generated from the current audit rules.</p>
                        @endif
                    </section>
                    @if(count($page['broken_links']))
                        <section class="audit-broken-links"><h3>Broken Resources</h3><ul>@foreach($page['broken_links'] as $link)<li><code>{{ $link['url'] }}</code> — {{ $link['status'] ?? 'Connection failed' }}</li>@endforeach</ul></section>
                    @endif
                    <a class="btn btn-sm" href="{{ $page['url'] }}" target="_blank" rel="noopener">Open audited page</a>
                </div>
            </details>
        @empty
            <div class="audit-empty"><h2>No matching pages</h2><p>Change the health, type or search filters.</p></div>
        @endforelse
        {{ $pages->links('partials.pagination') }}
    </section>
@endsection

@extends('layouts.admin')

@section('title', 'AdSense Readiness Validator | Toolexa Admin')

@section('content')
    @php
        $statusIcon = ['ready' => '✓', 'almost_ready' => '!', 'not_ready' => '×'][$report['status']];
        $priorityCounts = collect($report['actions'])->countBy('priority');
    @endphp

    <header class="audit-dashboard-hero adsense-validator-hero">
        <div>
            <span>Internal publishing gate</span>
            <h1>AdSense Readiness Validator</h1>
            <p>Generated {{ \Carbon\Carbon::parse($report['generated_at'])->format('M j, Y g:i A') }} from the current rendered-page audit. Scores are evidence-based and missing signals remain failed.</p>
        </div>
        <div class="audit-header-actions">
            <form method="POST" action="{{ route('admin.adsense-validator.refresh') }}">@csrf<button class="btn btn-primary" type="submit">Run New Audit</button></form>
            <a class="btn" href="{{ route('admin.adsense-validator.export', 'csv') }}">CSV</a>
            <a class="btn" href="{{ route('admin.adsense-validator.export', 'excel') }}">Excel</a>
            <a class="btn" href="{{ route('admin.adsense-validator.export', 'pdf') }}">PDF</a>
        </div>
    </header>

    @if(session('status'))<p class="audit-flash" role="status">{{ session('status') }}</p>@endif

    <section class="adsense-readiness-banner is-{{ $report['status'] }}" aria-labelledby="readiness-heading">
        <div class="adsense-score-ring" style="--audit-score: {{ $report['score'] }}"><strong>{{ $report['score'] }}%</strong><span>Overall</span></div>
        <div>
            <span class="adsense-status-icon" aria-hidden="true">{{ $statusIcon }}</span>
            <h2 id="readiness-heading">{{ $report['status_label'] }}</h2>
            <p>{{ $report['status_reason'] }}</p>
        </div>
    </section>

    <section class="audit-summary-grid" aria-label="AdSense readiness summary">
        <x-audit.summary-card label="Overall Score" :value="$report['score'].'%'" tone="score" />
        <x-audit.summary-card label="Critical Issues" :value="$report['summary']['critical_issues']" tone="critical" />
        <x-audit.summary-card label="Warnings" :value="$report['summary']['warnings']" tone="warning" />
        <x-audit.summary-card label="Healthy Pages" :value="$report['summary']['healthy_pages']" tone="good" />
        <x-audit.summary-card label="Pages Audited" :value="$report['summary']['total_pages']" />
        <x-audit.summary-card label="Checks Passed" :value="$report['summary']['passed_checks'].'/'.$report['summary']['total_checks']" />
    </section>

    <section class="audit-overview-panel adsense-module-overview">
        <div>
            <span class="audit-kicker">Module scoring</span>
            <h2>Readiness by requirement</h2>
            <p>Each score is the percentage of real checks currently passing. Expand a module to see the evidence and affected URLs.</p>
        </div>
        <div class="adsense-module-bars">
            @foreach($report['module_scores'] as $module => $score)
                <div class="adsense-module-bar">
                    <span>{{ \Illuminate\Support\Str::headline($module) }}</span>
                    <div role="progressbar" aria-label="{{ \Illuminate\Support\Str::headline($module) }}" aria-valuenow="{{ $score }}" aria-valuemin="0" aria-valuemax="100"><i style="width: {{ $score }}%"></i></div>
                    <strong>{{ $score }}</strong>
                </div>
            @endforeach
        </div>
    </section>

    <section class="adsense-module-grid" aria-labelledby="module-details-heading">
        <div class="audit-list-heading"><div><span>Evidence detail</span><h2 id="module-details-heading">Validation Modules</h2></div></div>
        @foreach($report['modules'] as $module => $checks)
            <details class="adsense-module-card">
                <summary>
                    <span>{{ \Illuminate\Support\Str::headline($module) }}</span>
                    <strong>{{ $report['module_scores'][$module] }}/100</strong>
                    <small>{{ collect($checks)->where('pass', false)->count() }} failed</small>
                </summary>
                <ul>
                    @foreach($checks as $check)
                        <li @class(['is-pass' => $check['pass'], 'is-fail' => ! $check['pass']])>
                            <span aria-hidden="true">{{ $check['pass'] ? '✓' : '×' }}</span>
                            <div>
                                <strong>{{ $check['label'] }}</strong>
                                <small>{{ $check['detail'] }}</small>
                                @if(count($check['pages']))
                                    <details class="adsense-affected-pages">
                                        <summary>{{ count($check['pages']) }} affected {{ \Illuminate\Support\Str::plural('page', count($check['pages'])) }}</summary>
                                        <code>{{ implode(', ', array_slice($check['pages'], 0, 30)) }}{{ count($check['pages']) > 30 ? ' …' : '' }}</code>
                                    </details>
                                @endif
                            </div>
                        </li>
                    @endforeach
                </ul>
            </details>
        @endforeach
    </section>

    <section class="audit-recommendations adsense-actions" aria-labelledby="actions-heading">
        <div class="audit-list-heading">
            <div><span>{{ count($report['actions']) }} prioritized tasks</span><h2 id="actions-heading">Action Items</h2></div>
            <div class="adsense-priority-legend">
                @foreach(['Critical', 'High', 'Medium', 'Low'] as $priority)
                    <span class="priority-{{ strtolower($priority) }}">{{ $priority }} {{ $priorityCounts[$priority] ?? 0 }}</span>
                @endforeach
            </div>
        </div>
        @forelse($report['actions'] as $action)
            <article class="adsense-action-item priority-{{ strtolower($action['priority']) }}">
                <span>{{ $action['priority'] }}</span>
                <div>
                    <small>{{ $action['module'] }} · {{ $action['affected'] }} affected</small>
                    <h3>{{ $action['issue'] }}</h3>
                    <p>{{ $action['action'] }}</p>
                    @if(count($action['pages']))<code>{{ implode(', ', array_slice($action['pages'], 0, 12)) }}{{ count($action['pages']) > 12 ? ' …' : '' }}</code>@endif
                </div>
            </article>
        @empty
            <div class="audit-empty"><h3>No open action items</h3><p>All current validation checks passed.</p></div>
        @endforelse
    </section>

    <section class="adsense-history" aria-labelledby="history-heading">
        <div class="audit-list-heading"><div><span>{{ count($report['history']) }} saved audits</span><h2 id="history-heading">Audit History</h2></div></div>
        @if(count($report['history']))
            <div class="adsense-history-track">
                @foreach($report['history'] as $index => $audit)
                    <article>
                        <span>Audit {{ count($report['history']) - $index }}</span>
                        <strong>{{ $audit['score'] }}%</strong>
                        <small>{{ \Carbon\Carbon::parse($audit['generated_at'])->format('M j, Y g:i A') }}</small>
                        <em>{{ \Illuminate\Support\Str::headline($audit['status']) }}</em>
                    </article>
                    @if(! $loop->last)<i aria-hidden="true">→</i>@endif
                @endforeach
            </div>
        @else
            <div class="audit-empty"><p>Run a new audit to create the first persistent comparison point.</p></div>
        @endif
    </section>

    <section class="adsense-page-table" aria-labelledby="page-readiness-heading">
        <div class="audit-list-heading"><div><span>Lowest scores first</span><h2 id="page-readiness-heading">Page Readiness</h2></div></div>
        <div class="table-responsive">
            <table>
                <thead><tr><th>Page</th><th>Type</th><th>Words</th><th>Failed checks</th><th>Health</th><th>Score</th></tr></thead>
                <tbody>
                    @foreach($report['pages'] as $page)
                        <tr>
                            <td><a href="{{ url($page['path']) }}" target="_blank" rel="noopener">{{ $page['name'] }}</a><small>{{ $page['path'] }}</small></td>
                            <td>{{ \Illuminate\Support\Str::headline($page['type']) }}</td>
                            <td>{{ number_format($page['word_count']) }}</td>
                            <td>{{ $page['failed_checks'] }}</td>
                            <td>{{ $page['health'] }}</td>
                            <td><strong>{{ $page['score'] }}</strong></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
@endsection

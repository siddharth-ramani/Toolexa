<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SiteAudit\AuditExportService;
use App\Services\SiteAudit\SiteAuditService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class SiteAuditController extends Controller
{
    public function index(Request $request, SiteAuditService $audit)
    {
        $report = $audit->report();
        $pages = $this->filteredPages($report['pages'], $request);
        $page = max(1, (int) $request->query('page', 1));
        $perPage = 25;
        $paginator = new LengthAwarePaginator(
            array_slice($pages, ($page - 1) * $perPage, $perPage),
            count($pages),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query(), 'pageName' => 'page']
        );

        return response()->view('admin.site-audit.index', [
            'report' => $report,
            'pages' => $paginator,
            'types' => collect($report['pages'])->pluck('type')->unique()->sort()->values()->all(),
            'filters' => [
                'health' => (string) $request->query('health', ''),
                'type' => (string) $request->query('type', ''),
                'q' => trim((string) $request->query('q', '')),
            ],
        ])->header('Cache-Control', 'no-store, private')->header('X-Robots-Tag', 'noindex, nofollow');
    }

    public function refresh(SiteAuditService $audit)
    {
        $audit->report(true);

        return redirect()->route('admin.site-audit.index')->with('status', 'The site audit was refreshed from current page output.');
    }

    public function export(Request $request, string $format, SiteAuditService $audit, AuditExportService $exports)
    {
        abort_unless(in_array($format, ['csv', 'excel', 'pdf'], true), 404);
        $report = $audit->report();
        $pages = $this->filteredPages($report['pages'], $request);

        return match ($format) {
            'csv' => $exports->csv($pages),
            'excel' => $exports->excel($pages),
            'pdf' => $exports->pdf($pages, $report['summary']),
        };
    }

    private function filteredPages(array $pages, Request $request): array
    {
        $health = (string) $request->query('health', '');
        $type = (string) $request->query('type', '');
        $query = mb_strtolower(trim((string) $request->query('q', '')));

        return collect($pages)
            ->when($health === 'critical', fn ($items) => $items->where('score', '<', 50))
            ->when($health === 'warnings', fn ($items) => $items->whereBetween('score', [50, 89]))
            ->when($health === 'excellent', fn ($items) => $items->where('score', '>=', 90))
            ->when($type !== '', fn ($items) => $items->where('type', $type))
            ->when($query !== '', fn ($items) => $items->filter(fn (array $page) => str_contains(mb_strtolower($page['name'].' '.$page['path']), $query)))
            ->sortBy('score')
            ->values()
            ->all();
    }
}

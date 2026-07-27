<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AdSense\AdSenseReadinessService;
use App\Services\SiteAudit\AuditExportService;

class AdSenseValidatorController extends Controller
{
    public function index(AdSenseReadinessService $validator)
    {
        return response()
            ->view('admin.adsense-validator.index', ['report' => $validator->report()])
            ->header('Cache-Control', 'no-store, private')
            ->header('X-Robots-Tag', 'noindex, nofollow');
    }

    public function refresh(AdSenseReadinessService $validator)
    {
        $validator->report(true);

        return redirect()
            ->route('admin.adsense-validator.index')
            ->with('status', 'A new readiness audit was completed and added to history.');
    }

    public function export(string $format, AdSenseReadinessService $validator, AuditExportService $exports)
    {
        abort_unless(in_array($format, ['csv', 'excel', 'pdf'], true), 404);
        $report = $validator->report();

        return match ($format) {
            'csv' => $exports->adsenseCsv($report),
            'excel' => $exports->adsenseExcel($report),
            'pdf' => $exports->adsensePdf($report),
        };
    }
}

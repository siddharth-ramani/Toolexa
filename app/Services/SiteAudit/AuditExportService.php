<?php

namespace App\Services\SiteAudit;

use Symfony\Component\HttpFoundation\StreamedResponse;

class AuditExportService
{
    public function adsenseCsv(array $report): StreamedResponse
    {
        return response()->streamDownload(function () use ($report) {
            $output = fopen('php://output', 'wb');
            fputcsv($output, ['Priority', 'Module', 'Issue', 'Recommended Action', 'Affected Pages', 'Page URLs']);
            foreach ($report['actions'] as $action) {
                fputcsv($output, [
                    $action['priority'], $action['module'], $action['issue'], $action['action'],
                    $action['affected'], implode(' | ', $action['pages']),
                ]);
            }
            fclose($output);
        }, 'toolexa-adsense-readiness-'.now()->format('Y-m-d-His').'.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function adsenseExcel(array $report): \Illuminate\Http\Response
    {
        $headings = ['Priority', 'Module', 'Issue', 'Recommended Action', 'Affected Pages', 'Page URLs'];
        $head = collect($headings)->map(fn (string $heading) => '<th>'.e($heading).'</th>')->implode('');
        $rows = collect($report['actions'])->map(fn (array $action) => '<tr>'.collect([
            $action['priority'], $action['module'], $action['issue'], $action['action'],
            $action['affected'], implode(' | ', $action['pages']),
        ])->map(fn ($value) => '<td>'.e((string) $value).'</td>')->implode('').'</tr>')->implode('');
        $html = '<!doctype html><html><head><meta charset="UTF-8"></head><body>'
            .'<h1>Toolexa AdSense Readiness — '.$report['score'].'%</h1>'
            .'<table border="1"><thead><tr>'.$head.'</tr></thead><tbody>'.$rows.'</tbody></table></body></html>';

        return response($html, 200, [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="toolexa-adsense-readiness-'.now()->format('Y-m-d-His').'.xls"',
        ]);
    }

    public function adsensePdf(array $report): \Illuminate\Http\Response
    {
        $lines = [
            'Toolexa AdSense Readiness Validator',
            'Generated: '.now()->toDateTimeString(),
            'Overall: '.$report['score'].'% | '.$report['status_label'],
            'Critical: '.$report['summary']['critical_issues'].' | Warnings: '.$report['summary']['warnings'],
            '',
            'Module scores',
        ];
        foreach ($report['module_scores'] as $module => $score) {
            $lines[] = str($module)->headline().' | '.$score.'/100';
        }
        $lines[] = '';
        $lines[] = 'Prioritized action items';
        foreach ($report['actions'] as $action) {
            $line = implode(' | ', [$action['priority'], $action['module'], $action['issue'], $action['affected'].' pages', $action['action']]);
            foreach ($this->wrap($line, 105) as $wrapped) {
                $lines[] = $wrapped;
            }
        }
        $pdf = $this->makePdf(array_chunk($lines, 48));

        return response($pdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="toolexa-adsense-readiness-'.now()->format('Y-m-d-His').'.pdf"',
        ]);
    }

    public function csv(array $pages): StreamedResponse
    {
        return response()->streamDownload(function () use ($pages) {
            $output = fopen('php://output', 'wb');
            fputcsv($output, $this->headings());
            foreach ($pages as $page) {
                fputcsv($output, $this->row($page));
            }
            fclose($output);
        }, 'toolexa-site-audit-'.now()->format('Y-m-d-His').'.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function excel(array $pages): \Illuminate\Http\Response
    {
        $rows = collect($pages)->map(function (array $page) {
            return '<tr>'.collect($this->row($page))->map(fn ($value) => '<td>'.e((string) $value).'</td>')->implode('').'</tr>';
        })->implode('');
        $head = collect($this->headings())->map(fn (string $heading) => '<th>'.e($heading).'</th>')->implode('');
        $html = '<!doctype html><html><head><meta charset="UTF-8"></head><body><table border="1"><thead><tr>'.$head.'</tr></thead><tbody>'.$rows.'</tbody></table></body></html>';

        return response($html, 200, [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="toolexa-site-audit-'.now()->format('Y-m-d-His').'.xls"',
        ]);
    }

    public function pdf(array $pages, array $summary): \Illuminate\Http\Response
    {
        $lines = [
            'Toolexa Site-wide E-E-A-T & Structured Data Audit',
            'Generated: '.now()->toDateTimeString(),
            'Total pages: '.$summary['total_pages'].' | Healthy: '.$summary['healthy_pages'].' | Average score: '.$summary['average_score'],
            '',
            'Score | Health | Type | Page | Path | Top recommendation',
        ];
        foreach ($pages as $page) {
            $recommendation = $page['recommendations'][0]['action'] ?? 'No priority recommendation';
            $line = implode(' | ', [$page['score'], $page['health'], $page['type'], $page['name'], $page['path'], $recommendation]);
            foreach ($this->wrap($line, 105) as $wrapped) {
                $lines[] = $wrapped;
            }
        }
        $pdf = $this->makePdf(array_chunk($lines, 48));

        return response($pdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="toolexa-site-audit-'.now()->format('Y-m-d-His').'.pdf"',
        ]);
    }

    private function headings(): array
    {
        return ['Score', 'Health', 'Type', 'Page', 'Path', 'Words', 'Headings', 'Images', 'FAQs', 'Internal Links', 'Official References', 'Reading Time', 'Uniqueness', 'Depth', 'Broken Links', 'Recommendations'];
    }

    private function row(array $page): array
    {
        return [
            $page['score'], $page['health'], $page['type'], $page['name'], $page['path'],
            $page['metrics']['word_count'], $page['metrics']['heading_count'], $page['metrics']['image_count'],
            $page['metrics']['faq_count'], $page['metrics']['internal_links'], $page['metrics']['external_references'],
            $page['metrics']['reading_time'], $page['metrics']['uniqueness_score'], $page['metrics']['content_depth_score'],
            count($page['broken_links']), collect($page['recommendations'])->pluck('action')->implode(' | '),
        ];
    }

    private function makePdf(array $pages): string
    {
        $pageCount = count($pages);
        $fontObject = 3 + ($pageCount * 2);
        $objects = [
            1 => '<< /Type /Catalog /Pages 2 0 R >>',
            2 => '<< /Type /Pages /Count '.$pageCount.' /Kids ['.collect(range(0, $pageCount - 1))->map(fn (int $index) => (3 + $index).' 0 R')->implode(' ').'] >>',
        ];
        foreach ($pages as $index => $lines) {
            $pageObject = 3 + $index;
            $contentObject = 3 + $pageCount + $index;
            $commands = "BT\n/F1 9 Tf\n36 806 Td\n";
            foreach ($lines as $line) {
                $commands .= '('.$this->pdfEscape($line).") Tj\n0 -15 Td\n";
            }
            $commands .= 'ET';
            $objects[$pageObject] = '<< /Type /Page /Parent 2 0 R /MediaBox [0 0 595 842] /Resources << /Font << /F1 '.$fontObject.' 0 R >> >> /Contents '.$contentObject.' 0 R >>';
            $objects[$contentObject] = '<< /Length '.strlen($commands)." >>\nstream\n".$commands."\nendstream";
        }
        $objects[$fontObject] = '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>';
        ksort($objects);
        $pdf = "%PDF-1.4\n";
        $offsets = [0];
        foreach ($objects as $number => $object) {
            $offsets[$number] = strlen($pdf);
            $pdf .= $number." 0 obj\n".$object."\nendobj\n";
        }
        $xref = strlen($pdf);
        $pdf .= 'xref'."\n".'0 '.(count($objects) + 1)."\n0000000000 65535 f \n";
        for ($number = 1; $number <= count($objects); $number++) {
            $pdf .= sprintf('%010d 00000 n ', $offsets[$number])."\n";
        }
        $pdf .= 'trailer << /Size '.(count($objects) + 1).' /Root 1 0 R >>'."\nstartxref\n".$xref."\n%%EOF";

        return $pdf;
    }

    private function pdfEscape(string $value): string
    {
        $ascii = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $value) ?: $value;

        return str_replace(['\\', '(', ')'], ['\\\\', '\\(', '\\)'], $ascii);
    }

    private function wrap(string $value, int $length): array
    {
        return explode("\n", wordwrap($value, $length, "\n", true));
    }
}

<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\EmailTemplate;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Shared\Converter;
use PhpOffice\PhpWord\SimpleType\Jc;
use Spatie\Browsershot\Browsershot;

class ReportController extends Controller
{
    public function exportOptions()
    {
        $months = collect(range(1, 12))->map(function ($month) {
            return [
                'value' => $month,
                'label' => Carbon::create()->month($month)->format('F')
            ];
        });

        $years = collect(range(date('Y') - 5, date('Y')))->reverse()->values();

        return view('reports.export-options', compact('months', 'years'));
    }

    public function preview(Request $request)
    {
        $month = $request->get('month', Carbon::now()->month);
        $year = $request->get('year', Carbon::now()->year);

        $data = $this->getMonthlyReportData($month, $year);

        return view('reports.preview', compact('data', 'month', 'year'));
    }

    // ADD EMAIL METHODS HERE
    public function emailForm()
    {
        $months = collect(range(1, 12))->map(function ($month) {
            return [
                'value' => $month,
                'label' => Carbon::create()->month($month)->format('F')
            ];
        });

        $years = collect(range(date('Y') - 5, date('Y')))->reverse()->values();

        return view('reports.email-form', compact('months', 'years'));
    }

    public function sendEmail(Request $request)
    {
        $request->validate([
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer',
            'recipients' => 'required|array|min:1',
            'recipients.*.email' => 'required|email',
            'recipients.*.type' => 'required|in:to,cc,bcc',
            'customMessage' => 'nullable|string|max:1000'
        ]);

        $month = (int)$request->month;
        $year = (int)$request->year;
        $customMessage = $request->customMessage ?? '';

        try {
            // Log email attempt
            Log::info('Email sending attempt started', [
                'month' => $month,
                'year' => $year,
                'recipients' => $request->recipients
            ]);

            // Generate PDF content first
            $data = $this->getMonthlyReportData($month, $year);
            $chartImages = $this->generateChartImagesForPDF($data, $year, $month);
            $chartFiles = $this->saveChartImagesToFiles($chartImages, $month, $year);

            // Generate PDF content without triggering download
            $html = $this->createFullReportHTML($data, $chartFiles);
            $pdfContent = Browsershot::html($html)
                ->setOption('no-sandbox', true)
                ->setOption('disable-web-security', true)
                ->format('A4')
                ->margins(10, 10, 10, 10)
                ->showBackground()
                ->waitUntilNetworkIdle()
                ->delay(2000)
                ->pdf();

            Log::info('PDF generated successfully', ['size' => strlen($pdfContent)]);

            // Clean up temporary files
            $tempDir = storage_path("app/temp/reports/$year-$month");
            $this->cleanupTempFiles($tempDir);

            // Prepare email template rendering (monthly_report_notification)
            $emailTemplate = EmailTemplate::where('key', 'monthly_report_notification')
                ->where('is_active', true)
                ->first();

            $monthName = Carbon::createFromDate($year, $month, 1)->format('F');
            $period = "{$monthName} {$year}";

            // Extract metrics for variables
            $extractMetric = function (string $label) use ($data): string {
                if (!empty($data['metrics']) && is_array($data['metrics'])) {
                    foreach ($data['metrics'] as $metric) {
                        if (($metric['label'] ?? null) === $label) {
                            return (string)($metric['value'] ?? '');
                        }
                    }
                }
                return '';
            };

            // Build summary HTML
            $summaryHtml = '';
            if (!empty($data['summary']) && is_array($data['summary'])) {
                $items = [];
                foreach ($data['summary'] as $point) {
                    $items[] = '<li>' . e($point) . '</li>';
                }
                if (!empty($items)) {
                    $summaryHtml = '<ul>' . implode('', $items) . '</ul>';
                }
            }

            // Variables for template rendering
            $variables = [
                'report_month' => $month,
                'report_month_name' => $monthName,
                'report_year' => $year,
                'report_period' => $period,
                'total_complaints' => $extractMetric('Total Complaints'),
                'resolved_count' => $extractMetric('Resolved'),
                'pending_count' => $extractMetric('Pending'),
                'custom_message' => $customMessage,
                'summary_html' => $summaryHtml,
                'generated_date' => now()->format('Y-m-d H:i'),
            ];

            // Default subject/body
            $subject = "Monthly Report - {$monthName} {$year}";
            $reportPeriod = $variables['report_period'];
            $totalComplaints = $variables['total_complaints'];
            $resolvedCount = $variables['resolved_count'];
            $pendingCount = $variables['pending_count'];
            $generatedDate = $variables['generated_date'];
            $customMessageHtml = $customMessage !== '' ? '<p>' . e($customMessage) . '</p>' : '';
            $summaryHtmlOut = $summaryHtml;

            $htmlBody = <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{$subject}</title>
</head>
<body style="font-family: Arial, sans-serif; color: #111827;">
    <h2 style="margin-bottom: 0;">Monthly Report</h2>
    <p style="margin-top: 4px; color: #6b7280;">{$reportPeriod}</p>
    {$customMessageHtml}
    <p><strong>Total Complaints:</strong> {$totalComplaints}</p>
    <p><strong>Resolved:</strong> {$resolvedCount}</p>
    <p><strong>Pending:</strong> {$pendingCount}</p>
    {$summaryHtmlOut}
    <hr style="margin: 20px 0;">
    <p style="font-size: 12px; color: #6b7280;">Generated on {$generatedDate}</p>
    <p style="font-size: 12px; color: #6b7280;">The detailed PDF report is attached.</p>
</body>
</html>
HTML;

            // If template exists, render with variables
            if ($emailTemplate) {
                $rendered = $emailTemplate->renderWithSampleData($variables);
                $subject = $rendered['subject'] ?? $subject;
                $htmlBody = $rendered['body'] ?? $htmlBody;
            }

            // Separate recipients by type
            $toRecipients = collect($request->recipients)->where('type', 'to')->pluck('email')->toArray();
            $ccRecipients = collect($request->recipients)->where('type', 'cc')->pluck('email')->toArray();
            $bccRecipients = collect($request->recipients)->where('type', 'bcc')->pluck('email')->toArray();

            Log::info('Recipients separated', [
                'to' => $toRecipients,
                'cc' => $ccRecipients,
                'bcc' => $bccRecipients
            ]);

            // Ensure we have at least one recipient (TO, CC, or BCC)
            $totalRecipientCount = count($toRecipients) + count($ccRecipients) + count($bccRecipients);
            if ($totalRecipientCount === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'At least one recipient is required.'
                ], 422);
            }

            // Determine TO (supports BCC-only by using system email as TO)
            $toAddress = !empty($toRecipients) ? $toRecipients[0] : config('mail.from.address');
            $additionalToAsCc = !empty($toRecipients) && count($toRecipients) > 1 ? array_slice($toRecipients, 1) : [];

            // Attachment filename
            $filename = 'monthly-report-' . $year . '-' . str_pad((string)$month, 2, '0', STR_PAD_LEFT) . '.pdf';

            // Send HTML email directly (aligned with incident behavior)
            Mail::html($htmlBody, function ($message) use (
                $toAddress,
                $additionalToAsCc,
                $ccRecipients,
                $bccRecipients,
                $subject,
                $pdfContent,
                $filename
            ) {
                $message->to($toAddress)
                    ->subject($subject)
                    ->attachData($pdfContent, $filename, ['mime' => 'application/pdf']);

                if (!empty($additionalToAsCc)) {
                    $message->cc($additionalToAsCc);
                }
                if (!empty($ccRecipients)) {
                    $message->cc($ccRecipients);
                }
                if (!empty($bccRecipients)) {
                    $message->bcc($bccRecipients);
                }
            });

            Log::info('Report email sent successfully');

            // Success response
            if (empty($toRecipients) && !empty($bccRecipients)) {
                return response()->json([
                    'success' => true,
                    'message' => "Report for {$monthName} {$year} successfully sent to {$totalRecipientCount} recipient(s) via BCC (privacy protected)."
                ]);
            } else {
                return response()->json([
                    'success' => true,
                    'message' => "Report for {$monthName} {$year} successfully sent to {$totalRecipientCount} recipient(s)."
                ]);
            }

        } catch (Exception $e) {
            Log::error('Email sending failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'month' => $month,
                'year' => $year,
                'recipients' => $request->recipients
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to send email: ' . $e->getMessage()
            ], 500);
        }
    }

    public function generatePDF(Request $request)
    {
        $month = $request->get('month', Carbon::now()->month);
        $year = $request->get('year', Carbon::now()->year);

        // Get comprehensive report data
        $data = $this->getMonthlyReportData($month, $year);

        try {
            // Generate chart images first
            $chartImages = $this->generateChartImagesForPDF($data, $year, $month);

            Log::info('Chart images generated', [
                'trends_size' => !empty($chartImages['trends']) ? strlen($chartImages['trends']) : 0,
                'pie_size' => !empty($chartImages['pie']) ? strlen($chartImages['pie']) : 0
            ]);

            // Save chart images to temporary files to avoid GD extension requirement
            $chartFiles = $this->saveChartImagesToFiles($chartImages, $month, $year);

            // Generate PDF with chart file references
            return $this->createPDFWithChartFiles($data, $chartFiles, $year, $month);

        } catch (Exception $e) {
            Log::error('PDF generation failed', ['error' => $e->getMessage()]);
            // Fallback to simple PDF if chart generation fails
            return $this->generateFallbackPDF($data, $year, $month);
        }
    }

    public function generateWord(Request $request)
    {
        $month = $request->get('month', Carbon::now()->month);
        $year = $request->get('year', Carbon::now()->year);

        $data = $this->getMonthlyReportData($month, $year);

        try {
            // Generate chart images using Browsershot for Word document
            $chartImages = $this->generateChartImagesForWord($data, $year, $month);

            return $this->createWordDocument($data, $chartImages, $year, $month);

        } catch (Exception $e) {
            // Fallback to text-based charts
            return $this->createWordDocumentWithTextCharts($data, $year, $month);
        }
    }

    private function generateChartImagesForPDF($data, $year, $month)
    {
        $chartImages = [];

        try {
            // First check if we can use Browsershot
            $this->checkBrowsershot();

            // Generate trends chart image
            Log::info('Generating trends chart...');
            $trendsHtml = view('reports.chart-images.trends-chart', compact('data'))->render();

            $chartImages['trends'] = Browsershot::html($trendsHtml)
                ->setOption('no-sandbox', true)
                ->setOption('disable-web-security', true)
                ->setOption('disable-features', 'VizDisplayCompositor')
                ->windowSize(800, 400)
                ->waitUntilNetworkIdle()
                ->delay(4000) // Increased delay
                ->screenshot();

            Log::info('Trends chart generated', ['size' => strlen($chartImages['trends'])]);

            // Generate pie chart image if there's data
            if ($data['complaintTypes']->count() > 0) {
                Log::info('Generating pie chart...');
                $pieHtml = view('reports.chart-images.pie-chart', compact('data'))->render();

                $chartImages['pie'] = Browsershot::html($pieHtml)
                    ->setOption('no-sandbox', true)
                    ->setOption('disable-web-security', true)
                    ->setOption('disable-features', 'VizDisplayCompositor')
                    ->windowSize(400, 400)
                    ->waitUntilNetworkIdle()
                    ->delay(4000)
                    ->screenshot();

                Log::info('Pie chart generated', ['size' => strlen($chartImages['pie'])]);
            }

        } catch (Exception $e) {
            Log::error('Chart image generation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            $chartImages = [];
        }

        return $chartImages;
    }

    private function saveChartImagesToFiles($chartImages, $month, $year)
    {
        $chartFiles = [];

        try {
            // Create storage directory if it doesn't exist
            $reportDir = storage_path("app/temp/reports/$year-$month");
            if (!file_exists($reportDir)) {
                mkdir($reportDir, 0755, true);
            }

            // Save trends chart
            if (!empty($chartImages['trends'])) {
                $trendsPath = $reportDir . '/trends-chart.png';
                file_put_contents($trendsPath, $chartImages['trends']);
                $chartFiles['trends'] = $trendsPath;
            }

            // Save pie chart
            if (!empty($chartImages['pie'])) {
                $piePath = $reportDir . '/pie-chart.png';
                file_put_contents($piePath, $chartImages['pie']);
                $chartFiles['pie'] = $piePath;
            }

        } catch (Exception $e) {
            Log::error('Failed to save chart images to files', ['error' => $e->getMessage()]);
        }

        return $chartFiles;
    }

    private function generateChartImagesForWord($data, $year, $month)
    {
        return $this->generateChartImagesForPDF($data, $year, $month);
    }

    private function checkBrowsershot()
    {
        try {
            // Try to find Chrome/Chromium in common locations
            $possiblePaths = [
                '/usr/bin/google-chrome',
                '/usr/bin/chromium-browser',
                '/usr/bin/chromium',
                'C:\Program Files\Google\Chrome\Application\chrome.exe',
                'C:\Program Files (x86)\Google\Chrome\Application\chrome.exe',
            ];

            $chromePath = null;
            foreach ($possiblePaths as $path) {
                if (file_exists($path)) {
                    $chromePath = $path;
                    break;
                }
            }

            if ($chromePath) {
                Log::info('Chrome found at: ' . $chromePath);
                // Set Chrome path if found
                Browsershot::html('<html><body>test</body></html>')
                    ->setChromePath($chromePath)
                    ->screenshot();
            } else {
                // Try without setting path (might be in PATH)
                Browsershot::html('<html><body>test</body></html>')
                    ->screenshot();
            }
        } catch (Exception $e) {
            throw new Exception('Browsershot not available: ' . $e->getMessage());
        }
    }

    private function createPDFWithChartFiles($data, $chartFiles, $year, $month)
    {
        try {
            // Use Browsershot to generate the entire PDF with charts embedded as files
            $html = $this->createFullReportHTML($data, $chartFiles);

            // Save HTML to temporary file
            $tempDir = storage_path("app/temp/reports/$year-$month");
            $htmlFile = $tempDir . '/report.html';
            file_put_contents($htmlFile, $html);

            // Generate PDF using Browsershot (works with all browsers)
            $pdfContent = Browsershot::html($html)
                ->setOption('no-sandbox', true)
                ->setOption('disable-web-security', true)
                ->format('A4')
                ->margins(10, 10, 10, 10)
                ->showBackground()
                ->waitUntilNetworkIdle()
                ->delay(2000)
                ->pdf();

            // Clean up temporary files
            $this->cleanupTempFiles($tempDir);

            $filename = "monthly-report-$year-$month.pdf";

            return response($pdfContent)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');

        } catch (Exception $e) {
            Log::error('PDF creation with chart files failed', ['error' => $e->getMessage()]);

            // Fallback to DomPDF without images
            return $this->generateFallbackPDF($data, $year, $month);
        }
    }

    private function createFullReportHTML($data, $chartFiles)
    {
        $trendsImageTag = '';
        if (!empty($chartFiles['trends']) && file_exists($chartFiles['trends'])) {
            $trendsImageData = base64_encode(file_get_contents($chartFiles['trends']));
            $trendsImageTag = '<img src="data:image/png;base64,' . $trendsImageData . '" alt="Trends Chart" style="max-width: 100%; height: auto; border: 1px solid #dee2e6; border-radius: 6px; background: white; padding: 10px;">';
        }

        $pieImageTag = '';
        if (!empty($chartFiles['pie']) && file_exists($chartFiles['pie'])) {
            $pieImageData = base64_encode(file_get_contents($chartFiles['pie']));
            $pieImageTag = '<img src="data:image/png;base64,' . $pieImageData . '" alt="Complaint Categories Chart" style="max-width: 400px; height: auto; border: 1px solid #dee2e6; border-radius: 6px; background: white; padding: 10px;">';
        }

        return '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monthly Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
            line-height: 1.6;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            padding: 20px;
            background-color: #667eea;
            color: white;
            border-radius: 8px;
        }

        .company-name {
            font-size: 24px;
            font-weight: bold;
            margin: 0;
        }

        .report-title {
            font-size: 18px;
            margin: 5px 0;
        }

        .report-period {
            font-size: 14px;
            margin: 5px 0;
            opacity: 0.9;
        }

        .section-title {
            font-size: 16px;
            font-weight: bold;
            margin: 25px 0 15px 0;
            padding-bottom: 5px;
            border-bottom: 2px solid #3498db;
            color: #2c3e50;
        }

        .executive-summary {
            background-color: #f8f9fa;
            padding: 15px;
            margin: 15px 0;
            border-left: 4px solid #3498db;
            border-radius: 4px;
        }

        .metrics-grid {
            display: table;
            width: 100%;
            margin: 20px 0;
        }

        .metric-row {
            display: table-row;
        }

        .metric-cell {
            display: table-cell;
            width: 50%;
            padding: 10px;
            vertical-align: top;
        }

        .metric-card {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            padding: 15px;
            text-align: center;
            margin: 5px;
        }

        .metric-value {
            font-size: 20px;
            font-weight: bold;
            color: #27ae60;
            margin: 5px 0;
        }

        .metric-label {
            font-size: 12px;
            color: #6c757d;
            font-weight: 500;
        }

        .chart-section {
            margin: 30px 0;
            text-align: center;
            page-break-inside: avoid;
        }

        .weekly-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        .weekly-table th,
        .weekly-table td {
            border: 1px solid #dee2e6;
            padding: 8px 12px;
            text-align: left;
        }

        .weekly-table th {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #495057;
        }

        .weekly-count {
            font-weight: bold;
            color: #27ae60;
        }

        .complaint-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 12px;
            margin: 5px 0;
            background-color: #f8f9fa;
            border-radius: 4px;
            border-left: 3px solid #6c5ce7;
        }

        .complaint-name {
            font-weight: 500;
        }

        .complaint-count {
            font-weight: bold;
            color: #27ae60;
        }

        .analysis-item {
            margin: 15px 0;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 6px;
            border-left: 4px solid #6c5ce7;
        }

        .analysis-title {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 8px;
            color: #2c3e50;
        }

        .analysis-content {
            color: #495057;
            font-size: 13px;
        }

        .footer {
            text-align: center;
            color: #6c757d;
            font-style: italic;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            font-size: 12px;
        }

        ul {
            list-style-type: disc;
            padding-left: 20px;
        }

        li {
            margin: 5px 0;
        }

        @media print {
            .chart-section {
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1 class="company-name">CIS SECURITY</h1>
        <h2 class="report-title">MONTHLY REPORT</h2>
        <p class="report-period">' . $data['period'] . '</p>
    </div>

    <!-- Executive Summary -->
    <div class="executive-summary">
        <h3 class="section-title">Executive Summary</h3>
        <ul>' .
            collect($data['summary'])->map(fn($point) => "<li>$point</li>")->implode('') .
            '</ul>
    </div>

    <!-- Key Metrics -->
    <h3 class="section-title">Key Performance Indicators</h3>
    <div class="metrics-grid">' .
            collect(array_chunk($data['metrics'], 2))->map(function($chunk) {
                $cells = collect($chunk)->map(function($metric) {
                    return '<div class="metric-cell">
                        <div class="metric-card">
                            <div class="metric-value">' . $metric['value'] . '</div>
                            <div class="metric-label">' . $metric['label'] . '</div>
                        </div>
                    </div>';
                })->implode('');

                if (count($chunk) == 1) {
                    $cells .= '<div class="metric-cell"></div>';
                }

                return '<div class="metric-row">' . $cells . '</div>';
            })->implode('') .
            '</div>' .

            // Trends Chart
            (!empty($trendsImageTag) ?
                '<div class="chart-section">
            <h3 class="section-title">6-Month Complaint Trends</h3>
            ' . $trendsImageTag . '
        </div>' : '') .

            // Weekly Breakdown
            '<h3 class="section-title">Weekly Breakdown</h3>
    <table class="weekly-table">
        <thead>
            <tr>
                <th>Week Period</th>
                <th>Complaint Count</th>
            </tr>
        </thead>
        <tbody>' .
            collect($data['weeklyData'])->map(function($week) {
                return '<tr>
                        <td>' . $week['week'] . '</td>
                        <td class="weekly-count">' . $week['count'] . '</td>
                    </tr>';
            })->implode('') .
            '</tbody>
    </table>' .

            // Pie Chart
            (!empty($pieImageTag) ?
                '<div class="chart-section">
            <h3 class="section-title">Top Complaint Categories</h3>
            ' . $pieImageTag . '
        </div>' : '') .

            // Top Complaint Categories (Text)
            '<h3 class="section-title">Top Complaint Categories Breakdown</h3>' .
            ($data['complaintTypes']->count() > 0 ?
                collect($data['complaintTypes'])->map(function($type) use ($data) {
                    $total = $data['complaintTypes']->sum('count');
                    $percentage = $total > 0 ? round(($type->count / $total) * 100, 1) : 0;
                    return '<div class="complaint-item">
                        <span class="complaint-name">' . $type->nature . '</span>
                        <span class="complaint-count">' . $type->count . ' (' . $percentage . '%)</span>
                    </div>';
                })->implode('') :
                '<p style="text-align: center; color: #6c757d; padding: 20px; font-style: italic;">
            No complaints recorded for this period.
        </p>') .

            // Detailed Analysis
            '<h3 class="section-title">Detailed Analysis</h3>' .
            collect($data['analysis'])->map(function($analysis) {
                return '<div class="analysis-item">
                    <h4 class="analysis-title">' . $analysis['title'] . '</h4>
                    <p class="analysis-content">' . $analysis['content'] . '</p>
                </div>';
            })->implode('') .

            // Footer
            '<div class="footer">
        Report generated on ' . now()->format('F j, Y \a\t g:i A') . '
    </div>
</body>
</html>';
    }

    private function cleanupTempFiles($directory)
    {
        try {
            if (is_dir($directory)) {
                $files = glob($directory . '/*');
                foreach ($files as $file) {
                    if (is_file($file)) {
                        unlink($file);
                    }
                }
                rmdir($directory);
            }
        } catch (Exception $e) {
            Log::warning('Failed to cleanup temp files', ['error' => $e->getMessage()]);
        }
    }

    private function generateFallbackPDF($data, $year, $month)
    {
        $pdf = Pdf::loadView('reports.pdf-template', compact('data'))
            ->setPaper('a4', 'portrait');

        $filename = "monthly-report-$year-$month.pdf";
        return $pdf->download($filename);
    }

    private function createWordDocument($data, $chartImages, $year, $month)
    {
        $phpWord = new PhpWord();
        $phpWord->getDocInfo()->setCreator('CIS Security');
        $phpWord->getDocInfo()->setTitle('Monthly Report');

        $section = $phpWord->addSection([
            'marginLeft' => Converter::inchToTwip(1),
            'marginRight' => Converter::inchToTwip(1),
            'marginTop' => Converter::inchToTwip(1),
            'marginBottom' => Converter::inchToTwip(1),
        ]);

        // Header
        $section->addText(
            'CIS SECURITY - MONTHLY REPORT',
            ['bold' => true, 'size' => 20, 'color' => '1f2937'],
            ['alignment' => Jc::CENTER]
        );

        $section->addText(
            Carbon::createFromDate($year, $month)->format('F Y'),
            ['bold' => true, 'size' => 16, 'color' => '4b5563'],
            ['alignment' => Jc::CENTER]
        );

        $section->addTextBreak(2);

        // Executive Summary
        $section->addText('EXECUTIVE SUMMARY', ['bold' => true, 'size' => 14, 'color' => '1f2937']);
        $section->addTextBreak();

        foreach ($data['summary'] as $point) {
            $section->addText('• ' . $point, ['size' => 11]);
        }

        $section->addTextBreak(2);

        // Key Metrics Table
        $section->addText('KEY PERFORMANCE INDICATORS', ['bold' => true, 'size' => 14, 'color' => '1f2937']);
        $section->addTextBreak();

        $table = $section->addTable([
            'borderSize' => 6,
            'borderColor' => '999999',
            'cellMargin' => 80,
        ]);

        foreach (array_chunk($data['metrics'], 2) as $chunk) {
            $table->addRow();
            foreach ($chunk as $metric) {
                $cell = $table->addCell(4000, ['bgColor' => 'f8f9fa']);
                $cell->addText($metric['label'], ['size' => 10, 'color' => '6b7280']);
                $cell->addText($metric['value'], ['bold' => true, 'size' => 14, 'color' => '059669']);
            }
            if (count($chunk) == 1) {
                $table->addCell(4000);
            }
        }

        $section->addTextBreak(2);

        // Add chart images if available
        if (!empty($chartImages['trends'])) {
            $section->addText('6-MONTH COMPLAINT TRENDS', ['bold' => true, 'size' => 14, 'color' => '1f2937']);
            $section->addTextBreak();

            $temp_trends = tempnam(sys_get_temp_dir(), 'trends_chart') . '.png';
            file_put_contents($temp_trends, $chartImages['trends']);

            $section->addImage($temp_trends, [
                'width' => 400,
                'height' => 200,
                'alignment' => Jc::CENTER,
            ]);

            unlink($temp_trends);
            $section->addTextBreak(2);
        }

        // Weekly Breakdown
        $section->addText('WEEKLY BREAKDOWN', ['bold' => true, 'size' => 14, 'color' => '1f2937']);
        $section->addTextBreak();

        $weeklyTable = $section->addTable([
            'borderSize' => 6,
            'borderColor' => '999999',
            'cellMargin' => 80,
        ]);

        $weeklyTable->addRow();
        $weeklyTable->addCell(2000, ['bgColor' => 'f0f0f0'])->addText('Week', ['bold' => true]);
        $weeklyTable->addCell(2000, ['bgColor' => 'f0f0f0'])->addText('Complaints', ['bold' => true]);

        foreach ($data['weeklyData'] as $week) {
            $weeklyTable->addRow();
            $weeklyTable->addCell(2000)->addText($week['week'], ['size' => 10]);
            $weeklyTable->addCell(2000)->addText($week['count'], ['bold' => true, 'color' => '059669']);
        }

        $section->addTextBreak(2);

        // Add pie chart if available
        if (!empty($chartImages['pie'])) {
            $section->addText('TOP COMPLAINT CATEGORIES', ['bold' => true, 'size' => 14, 'color' => '1f2937']);
            $section->addTextBreak();

            $temp_pie = tempnam(sys_get_temp_dir(), 'pie_chart') . '.png';
            file_put_contents($temp_pie, $chartImages['pie']);

            $section->addImage($temp_pie, [
                'width' => 300,
                'height' => 300,
                'alignment' => Jc::CENTER,
            ]);

            unlink($temp_pie);
        } else {
            // Text fallback for complaint types
            $section->addText('TOP COMPLAINT CATEGORIES', ['bold' => true, 'size' => 14, 'color' => '1f2937']);
            $section->addTextBreak();

            if ($data['complaintTypes']->count() > 0) {
                $total = $data['complaintTypes']->sum('count');
                foreach ($data['complaintTypes'] as $type) {
                    $percentage = $total > 0 ? round(($type->count / $total) * 100, 1) : 0;
                    $section->addText(
                        $type->nature . ': ' . $type->count . ' (' . $percentage . '%)',
                        ['size' => 11]
                    );
                }
            }
        }

        $section->addTextBreak(2);

        // Analysis
        $section->addText('DETAILED ANALYSIS', ['bold' => true, 'size' => 14, 'color' => '1f2937']);
        $section->addTextBreak();

        foreach ($data['analysis'] as $analysis) {
            $section->addText($analysis['title'], ['bold' => true, 'size' => 12]);
            $section->addText($analysis['content'], ['size' => 11]);
            $section->addTextBreak();
        }

        // Footer
        $section->addTextBreak(2);
        $section->addText(
            'Report generated on ' . now()->format('F j, Y \a\t g:i A'),
            ['size' => 10, 'italic' => true],
            ['alignment' => Jc::CENTER]
        );

        try {
            $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
            $filename = "monthly-report-$year-$month.docx";
            $temp_file = tempnam(sys_get_temp_dir(), $filename);
            $objWriter->save($temp_file);

            return response()->download($temp_file, $filename)->deleteFileAfterSend(true);
        } catch (Exception $e) {
            return back()->with('error', 'Error generating Word document: ' . $e->getMessage());
        }
    }

    private function createWordDocumentWithTextCharts($data, $year, $month)
    {
        // Simple fallback Word document without charts
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        $section->addText('CIS SECURITY - MONTHLY REPORT',
            ['bold' => true, 'size' => 16],
            ['alignment' => Jc::CENTER]
        );

        $section->addText(Carbon::createFromDate($year, $month)->format('F Y'),
            ['size' => 14],
            ['alignment' => Jc::CENTER]
        );

        // Add basic report content without charts
        $section->addTextBreak(2);
        $section->addText('EXECUTIVE SUMMARY', ['bold' => true, 'size' => 12]);

        foreach ($data['summary'] as $point) {
            $section->addText('• ' . $point);
        }

        try {
            $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
            $filename = "monthly-report-$year-$month.docx";
            $temp_file = tempnam(sys_get_temp_dir(), $filename);
            $objWriter->save($temp_file);

            return response()->download($temp_file, $filename)->deleteFileAfterSend(true);
        } catch (Exception $e) {
            return back()->with('error', 'Error generating Word document: ' . $e->getMessage());
        }
    }

    private function getMonthlyReportData($month, $year)
    {
        $startDate = Carbon::createFromDate($year, $month, 1);
        $endDate = $startDate->copy()->endOfMonth();

        // Get complaints for the month
        $monthlyComplaints = Complaint::active()
            ->whereBetween('date_received', [$startDate, $endDate])
            ->get();

        // Get 6 months of trend data
        $trendsData = collect(range(5, 0))->map(function ($monthsBack) {
            $date = Carbon::now()->subMonths($monthsBack);
            $count = Complaint::active()
                ->whereYear('date_received', $date->year)
                ->whereMonth('date_received', $date->month)
                ->count();

            return [
                'month' => $date->format('M Y'),
                'count' => $count
            ];
        });

        // Get weekly breakdown for the month
        $weeks = collect();
        $currentDate = $startDate->copy();
        $weekNumber = 1;

        while ($currentDate <= $endDate) {
            $weekStart = $currentDate->copy();
            $weekEnd = $currentDate->copy()->addDays(6);
            if ($weekEnd > $endDate) {
                $weekEnd = $endDate->copy();
            }

            $weekCount = Complaint::active()
                ->whereBetween('date_received', [$weekStart, $weekEnd])
                ->count();

            $weeks->push([
                'week' => "Week $weekNumber (" . $weekStart->format('M d') . " - " . $weekEnd->format('M d') . ")",
                'count' => $weekCount
            ]);

            $currentDate = $weekEnd->copy()->addDay();
            $weekNumber++;
        }

        // Get complaint types
        $complaintTypes = Complaint::active()
            ->whereBetween('date_received', [$startDate, $endDate])
            ->selectRaw('nature, COUNT(*) as count')
            ->groupBy('nature')
            ->orderByDesc('count')
            ->limit(5)
            ->get();

        // Generate metrics
        $metrics = [
            ['label' => 'Total Complaints', 'value' => $monthlyComplaints->count()],
            ['label' => 'Resolved', 'value' => $monthlyComplaints->where('status', 'resolved')->count()],
            ['label' => 'Pending', 'value' => $monthlyComplaints->where('status', 'pending')->count()],
            ['label' => 'Avg Resolution Time', 'value' => '3.2 days']
        ];

        // Generate summary points
        $summary = [
            "Total of {$monthlyComplaints->count()} complaints received in " . $startDate->format('F Y'),
            "Top complaint category: " . ($complaintTypes->first()->nature ?? 'N/A'),
            "Resolution rate: " . round(($monthlyComplaints->where('status', 'resolved')->count() / max($monthlyComplaints->count(), 1)) * 100) . "%"
        ];

        // Generate analysis
        $analysis = [
            [
                'title' => 'Monthly Performance',
                'content' => "The month showed a total of {$monthlyComplaints->count()} complaints, indicating current operational challenges that require attention."
            ],
            [
                'title' => 'Resolution Efficiency',
                'content' => 'Most complaints were resolved within the standard timeframe, showing effective response procedures.'
            ]
        ];

        return [
            'period' => $startDate->format('F Y'),
            'metrics' => $metrics,
            'summary' => $summary,
            'trendsData' => $trendsData,
            'weeklyData' => $weeks,
            'complaintTypes' => $complaintTypes,
            'analysis' => $analysis
        ];
    }
}

<?php

namespace App\Mail;

use App\Models\EmailTemplate;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MonthlyReportMail extends Mailable
{
    use Queueable, SerializesModels;

    private int $month;
    private int $year;
    private string $pdfContent;
    private string $customMessage;
    private array $data;
    private ?array $renderedContent = null;

    /**
     * @param int $month
     * @param int $year
     * @param string $pdfContent Raw PDF binary string
     * @param string|null $customMessage
     * @param array $data Precomputed monthly report data (optional)
     */
    public function __construct(int $month, int $year, string $pdfContent, ?string $customMessage = '', array $data = [])
    {
        $this->month = $month;
        $this->year = $year;
        $this->pdfContent = $pdfContent;
        $this->customMessage = $customMessage ?? '';
        $this->data = $data;

        // Load and render the email template (monthly_report_notification)
        $template = EmailTemplate::where('key', 'monthly_report_notification')
            ->where('is_active', true)
            ->first();

        if ($template) {
            $this->renderedContent = $template->renderWithSampleData($this->getTemplateVariables());
        }
    }

    public function envelope(): Envelope
    {
        $monthName = Carbon::createFromDate($this->year, $this->month, 1)->format('F');
        $defaultSubject = "Monthly Report - {$monthName} {$this->year}";

        return new Envelope(
            subject: $this->renderedContent['subject'] ?? $defaultSubject
        );
    }

    public function content(): Content
    {
        // Send raw HTML content, no wrapper view
        $htmlBody = $this->renderedContent['body'] ?? $this->fallbackHtml();

        // Use positional args to avoid any named-arg mismatch
        return new Content(null, null, $htmlBody);
    }

    public function attachments(): array
    {
        return [
            Attachment::fromData(
                fn () => $this->pdfContent,
                $this->getPdfFilename()
            )->withMime('application/pdf'),
        ];
    }

    private function getTemplateVariables(): array
    {
        $monthName = Carbon::createFromDate($this->year, $this->month, 1)->format('F');
        $period = "{$monthName} {$this->year}";

        // Extract some common metrics if provided
        $totalComplaints = $this->extractMetric('Total Complaints');
        $resolved = $this->extractMetric('Resolved');
        $pending = $this->extractMetric('Pending');

        // Build summary HTML if available
        $summaryItems = [];
        if (!empty($this->data['summary']) && is_array($this->data['summary'])) {
            foreach ($this->data['summary'] as $point) {
                $summaryItems[] = "<li>" . e($point) . "</li>";
            }
        }
        $summaryHtml = count($summaryItems) ? "<ul>" . implode('', $summaryItems) . "</ul>" : '';

        return [
            'report_month' => $this->month,
            'report_month_name' => $monthName,
            'report_year' => $this->year,
            'report_period' => $period,
            'total_complaints' => $totalComplaints,
            'resolved_count' => $resolved,
            'pending_count' => $pending,
            'custom_message' => $this->customMessage,
            'summary_html' => $summaryHtml,
            'generated_date' => now()->format('Y-m-d H:i'),
        ];
    }

    private function extractMetric(string $label): string
    {
        if (!empty($this->data['metrics']) && is_array($this->data['metrics'])) {
            foreach ($this->data['metrics'] as $metric) {
                if (($metric['label'] ?? null) === $label) {
                    return (string)($metric['value'] ?? '');
                }
            }
        }
        return '';
    }

    private function getPdfFilename(): string
    {
        $monthName = Carbon::createFromDate($this->year, $this->month, 1)->format('F');
        $monthPadded = str_pad((string)$this->month, 2, '0', STR_PAD_LEFT);
        return "monthly-report-{$this->year}-{$monthPadded}-{$monthName}.pdf";
    }

    private function fallbackHtml(): string
    {
        $vars = $this->getTemplateVariables();

        $custom = $vars['custom_message'] ? '<p>' . e($vars['custom_message']) . '</p>' : '';

        return <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Monthly Report - {$vars['report_period']}</title>
</head>
<body style="font-family: Arial, sans-serif; color: #111827;">
    <h2 style="margin-bottom: 0;">Monthly Report</h2>
    <p style="margin-top: 4px; color: #6b7280;">{$vars['report_period']}</p>
    {$custom}
    <p><strong>Total Complaints:</strong> {$vars['total_complaints']}</p>
    <p><strong>Resolved:</strong> {$vars['resolved_count']}</p>
    <p><strong>Pending:</strong> {$vars['pending_count']}</p>
    {$vars['summary_html']}
    <hr style="margin: 20px 0;">
    <p style="font-size: 12px; color: #6b7280;">Generated on {$vars['generated_date']}</p>
    <p style="font-size: 12px; color: #6b7280;">The detailed PDF report is attached.</p>
</body>
</html>
HTML;
    }
}

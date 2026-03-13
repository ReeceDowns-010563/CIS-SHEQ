<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;

class ReportEmailMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $reportMonth,
        public string $reportYear,
        public string $pdfContent,
        public string $customMessage = ''
    ) {}

    public function envelope(): Envelope
    {
        $monthName = date('F', mktime(0, 0, 0, (int)$this->reportMonth, 1));

        return new Envelope(
            subject: "Monthly Report - {$monthName} {$this->reportYear}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.report-email',
            with: [
                'monthName' => date('F', mktime(0, 0, 0, (int)$this->reportMonth, 1)),
                'year' => $this->reportYear,
                'customMessage' => $this->customMessage
            ]
        );
    }

    public function attachments(): array
    {
        $filename = "monthly-report-{$this->reportYear}-" . str_pad($this->reportMonth, 2, '0', STR_PAD_LEFT) . ".pdf";

        return [
            Attachment::fromData(fn () => $this->pdfContent, $filename)
                ->withMime('application/pdf')
        ];
    }
}

<?php

namespace App\Mail;

use App\Models\EmailTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TestEmailMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public EmailTemplate $emailTemplate,
        public array $variables = []
    ) {}

    public function envelope(): Envelope
    {
        // Render subject with variables or sample data
        $renderedData = $this->emailTemplate->renderWithSampleData($this->variables);

        return new Envelope(
            subject: $renderedData['subject'],
        );
    }

    public function content(): Content
    {
        // Render body with variables or sample data
        $renderedData = $this->emailTemplate->renderWithSampleData($this->variables);

        return new Content(
            html: $renderedData['body'], // Use 'html' instead of 'htmlView'
            with: [
                'subject' => $renderedData['subject'],
                'body' => $renderedData['body'],
                'templateName' => $this->emailTemplate->name,
                'templateKey' => $this->emailTemplate->key,
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}

<?php

namespace App\Mail;

use App\Models\IncidentReport;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CoordinatorAssignedMail extends Mailable
{
    use Queueable, SerializesModels;

    public IncidentReport $incident;
    public User $coordinator;

    /**
     * Create a new message instance.
     */
    public function __construct(IncidentReport $incident, User $coordinator)
    {
        $this->incident = $incident;
        $this->coordinator = $coordinator;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Accident Report Assignment - #' . str_pad($this->incident->id, 6, '0', STR_PAD_LEFT),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.coordinator-assigned-txt',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}

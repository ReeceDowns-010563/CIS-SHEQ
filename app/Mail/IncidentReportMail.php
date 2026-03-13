<?php

namespace App\Mail;

use App\Models\IncidentReport;
use App\Models\EmailTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class IncidentReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public $incident;
    public $actionType; // e.g., 'created' or 'updated'
    private $emailTemplate;
    private $renderedContent;

    /**
     * Create a new message instance.
     */
    public function __construct(IncidentReport $incident, $actionType)
    {
        $this->incident = $incident->load([
            'incidentType',
            'treatmentType',
            'mechanism',
            // Removed 'bodyParts' since it's not a relationship anymore
            'injuryType',
            'agency',
            'branch',
            'site',
            'affectedEmployee',
            'affectedCustomer',
            'reportedEmployee',
            'reportedCustomer',
            'coordinator',
            'creator'
        ]);

        // Get the email template and render content
        if($actionType == 'created'){
            $key = 'incident_report_notification';
        } else if($actionType == 'completed'){
            $key = 'complete_incident_report';
        }else{
            $key = 'incident_report_notification';
        }
        
        
        $this->emailTemplate = EmailTemplate::where('key', $key)
            ->where('is_active', true)
            ->first();
            
        if ($this->emailTemplate) {
            $this->renderedContent = $this->emailTemplate->renderWithSampleData($this->getTemplateVariables());
        }
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        // Use template subject if available, otherwise fallback
        if ($this->renderedContent) {
            $subject = $this->renderedContent['subject'];
        } else {
            $subject = 'New Incident Report - ' . $this->incident->brief_description;
        }

        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        // Use template body if available, otherwise fallback to original view
        if ($this->renderedContent) {
            // Create a simple wrapper view that just outputs the HTML
            return new Content(
                // view: 'emails.template-wrapper',
                view: 'emails.incident-report',
                with: [
                    'htmlContent' => $this->renderedContent['body'],
                    'incident' => $this->incident,
                ]
            );
        }
        
        // Fallback to original view if template not found
        return new Content(
            view: 'emails.incident-report',
            with: [
                'incident' => $this->incident
            ]
        );
    }

    /**
     * Get the template variables for rendering
     */
    private function getTemplateVariables(): array
    {
        $affectedPerson = $this->getAffectedPersonString();
        $reportedBy = $this->getReportedByString();

        return [
            'incident_id' => 'INC-' . str_pad($this->incident->id, 6, '0', STR_PAD_LEFT),
            'brief_description' => $this->incident->brief_description,
            'location' => $this->incident->location,
            'date_of_occurrence' => $this->incident->date_of_occurrence ? $this->incident->date_of_occurrence->format('Y-m-d') : 'Not specified',
            'time_of_occurrence' => $this->incident->time_of_occurrence ?? 'Not specified',
            'incident_type' => $this->incident->incidentType->name ?? 'Not specified',
            'affected_person' => $affectedPerson,
            'status' => ucfirst($this->incident->status),
            'reported_by' => $reportedBy,
            'branch_name' => $this->incident->branch->display_name ?? $this->incident->branch->branch_name ?? 'Not specified',
            'site_name' => $this->incident->site->name ?? 'Not specified',
            'what_happened' => $this->incident->what_happened ?? 'Not provided',
            'additional_information' => $this->incident->additional_information ?? 'None provided',
            'incident_url' => route('incidents.show', $this->incident),
            'generated_date' => now()->format('Y-m-d H:i'),
        ];
    }

    /**
     * Get affected person string
     */
    private function getAffectedPersonString(): string
    {
        switch ($this->incident->affected_person_source) {
            case 'Employee':
                if ($this->incident->affectedEmployee) {
                    return $this->incident->affectedEmployee->first_name . ' ' . $this->incident->affectedEmployee->last_name . ' (Employee)';
                }
                break;
            case 'Customer':
                if ($this->incident->affectedCustomer) {
                    return $this->incident->affectedCustomer->name . ' (Customer)';
                }
                break;
            case 'Other':
                return $this->incident->affected_person_other . ' (Other)';
        }

        return 'Not specified';
    }

    /**
     * Get reported by string
     */
    private function getReportedByString(): string
    {
        switch ($this->incident->reported_by_source) {
            case 'Employee':
                if ($this->incident->reportedEmployee) {
                    return $this->incident->reportedEmployee->first_name . ' ' . $this->incident->reportedEmployee->last_name . ' (Employee)';
                }
                break;
            case 'Customer':
                if ($this->incident->reportedCustomer) {
                    return $this->incident->reportedCustomer->name . ' (Customer)';
                }
                break;
            case 'Other':
                return $this->incident->reported_by_other . ' (Other)';
        }

        return 'Not specified';
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}
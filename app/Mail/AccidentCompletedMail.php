<?php

namespace App\Mail;

use App\Models\EmailTemplate;
use App\Models\IncidentReport;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AccidentCompletedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public IncidentReport $incident,
        public EmailTemplate $emailTemplate,
        public string $pdfPath
    ) {}

    /**
     * Build the message.
     */
    public function build()
    {
        $variables = $this->getVariables();
        $renderedData = $this->emailTemplate->renderWithSampleData($variables);

        return $this->subject($renderedData['subject'])
            ->html($renderedData['body'])
            ->attach($this->pdfPath, [
                'as' => 'accident-investigation-report-' . str_pad($this->incident->id, 6, '0', STR_PAD_LEFT) . '.pdf',
                'mime' => 'application/pdf',
            ]);
    }

    private function getVariables(): array
    {
        return [
            'recipient_name' => 'SHEQ Team',
            'incident_id' => str_pad($this->incident->id, 6, '0', STR_PAD_LEFT),
            'incident_description' => $this->incident->brief_description ?? 'N/A',
            'date_of_occurrence' => $this->incident->date_of_occurrence ?
                \Carbon\Carbon::parse($this->incident->date_of_occurrence)->format('Y-m-d') : 'N/A',
            'location' => $this->incident->location ?? 'N/A',
            'status' => ucfirst($this->incident->status),
            'report_url' => route('incidents.show', $this->incident->id),
        ];
    }
}

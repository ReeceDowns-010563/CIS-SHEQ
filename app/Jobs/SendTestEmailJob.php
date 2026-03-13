<?php

namespace App\Jobs;

use App\Models\EmailTemplate;
use App\Models\EmailAuditLog;
use App\Services\EmailTemplateService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Exception;

class SendTestEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 60;

    public function __construct(
        private int $templateId,
        private string $recipientEmail,
        private array $variables,
        private int $userId,
        private string $ipAddress
    ) {}

    public function handle(EmailTemplateService $emailService): void
    {
        $startTime = microtime(true);
        $auditData = [
            'subject' => '',
            'recipients' => [$this->recipientEmail],
            'origin' => 'test',
            'variables' => $this->variables,
            'user_id' => $this->userId,
            'ip_address' => $this->ipAddress,
            'status' => 'pending',
        ];

        try {
            $template = EmailTemplate::findOrFail($this->templateId);
            $auditData['template_key'] = $template->key;

            // Render email content
            $rendered = $template->renderWithSampleData($this->variables);
            $auditData['subject'] = $rendered['subject'];

            // Send email
            Mail::html(
                $rendered['body'],
                function ($message) use ($rendered) {
                    $message->to($this->recipientEmail)
                        ->subject($rendered['subject']);
                }
            );

            $duration = (microtime(true) - $startTime) * 1000;

            $auditData = array_merge($auditData, [
                'status' => 'sent',
                'sent_at' => now(),
                'duration_ms' => round($duration),
            ]);

            $emailService->incrementTestSendLimit($this->userId);

        } catch (Exception $e) {
            $duration = (microtime(true) - $startTime) * 1000;

            $auditData = array_merge($auditData, [
                'status' => 'failed',
                'error_message' => $e->getMessage(),
                'duration_ms' => round($duration),
            ]);

            throw $e;
        } finally {
            $emailService->logEmailAudit($auditData);
        }
    }

    public function failed(Exception $exception): void
    {
        // Additional logging or notifications can be added here
    }
}

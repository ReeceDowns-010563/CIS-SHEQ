<?php

namespace App\Services;

use App\Models\EmailAuditLog;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class EmailAuditService
{
    public function getFilteredAuditLogs(Request $request): LengthAwarePaginator
    {
        $query = EmailAuditLog::with(['user', 'template'])
            ->orderBy('created_at', 'desc');

        // Apply filters
        if ($request->filled('status')) {
            $query->byStatus($request->get('status'));
        }

        if ($request->filled('origin')) {
            $query->byOrigin($request->get('origin'));
        }

        if ($request->filled('template_key')) {
            $query->where('template_key', $request->get('template_key'));
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->get('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->get('date_to'));
        }

        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('subject', 'like', "%{$search}%")
                    ->orWhere('template_key', 'like', "%{$search}%")
                    ->orWhereJsonContains('recipients', $search);
            });
        }

        return $query->paginate(20)->appends($request->query());
    }

    public function getFilterOptions(): array
    {
        return [
            'statuses' => [
                'pending' => 'Pending',
                'sent' => 'Sent',
                'failed' => 'Failed',
            ],
            'origins' => [
                'system' => 'System',
                'test' => 'Test',
            ],
            'template_keys' => $this->getTemplateKeys(),
        ];
    }

    public function exportToCsv(Collection $logs): string
    {
        $csv = "ID,Template Key,Subject,Recipients,Origin,Status,Sent At,Duration (ms),User,Error Message,Created At\n";

        foreach ($logs as $log) {
            $recipients = implode(';', $log->getRecipientsList());
            $sentAt = $log->sent_at ? $log->sent_at->format('Y-m-d H:i:s') : '';
            $userName = $log->user ? $log->user->name : '';
            $errorMessage = str_replace(['"', "\n", "\r"], ['""', ' ', ' '], $log->error_message ?? '');

            $csv .= sprintf('"%s","%s","%s","%s","%s","%s","%s","%s","%s","%s","%s"' . "\n",
                $log->id,
                $log->template_key ?? '',
                str_replace('"', '""', $log->subject),
                $recipients,
                $log->origin,
                $log->status,
                $sentAt,
                $log->duration_ms ?? '',
                $userName,
                $errorMessage,
                $log->created_at->format('Y-m-d H:i:s')
            );
        }

        return $csv;
    }

    private function getTemplateKeys(): array
    {
        return EmailAuditLog::distinct('template_key')
            ->whereNotNull('template_key')
            ->pluck('template_key', 'template_key')
            ->toArray();
    }
}

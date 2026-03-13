<?php

namespace App\Jobs;

use App\Models\EmailAuditLog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class PurgeOldEmailLogsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private int $retentionMonths = 18
    ) {}

    public function handle(): void
    {
        $cutoffDate = now()->subMonths($this->retentionMonths);

        $deletedCount = EmailAuditLog::where('created_at', '<', $cutoffDate)->delete();

        Log::info('Email audit logs purged', [
            'deleted_count' => $deletedCount,
            'cutoff_date' => $cutoffDate->toDateString(),
            'retention_months' => $this->retentionMonths,
        ]);
    }
}

<?php

namespace App\Console\Commands;

use App\Jobs\PurgeOldEmailLogsJob;
use Illuminate\Console\Command;

class PurgeOldEmailLogsCommand extends Command
{
    protected $signature = 'emails:purge-logs {--months=18 : Number of months to retain logs}';
    protected $description = 'Purge old email audit logs based on retention policy';

    public function handle(): int
    {
        $months = (int) $this->option('months');

        if ($months <= 0) {
            $this->error('Retention months must be greater than 0.');
            return self::FAILURE;
        }

        $this->info("Purging email logs older than {$months} months...");

        PurgeOldEmailLogsJob::dispatch($months);

        $this->info('Purge job has been queued.');

        return self::SUCCESS;
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TestDbWriteCommand extends Command
{
    protected $signature = 'test:db-write';
    protected $description = 'Test writing to database';

    public function handle()
    {
        try {
            // Simple test - insert a record
            DB::table('users')->insert([
                'name' => 'Test User ' . now()->format('Y-m-d H:i:s'),
                'email' => 'test' . time() . '@example.com',
                'password' => bcrypt('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $this->info('✅ Database write test successful!');

        } catch (\Exception $e) {
            $this->error('❌ Database write failed: ' . $e->getMessage());
        }
    }
}

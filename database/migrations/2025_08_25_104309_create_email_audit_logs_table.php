<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmailAuditLogsTable extends Migration
{
    public function up(): void
    {
        Schema::create('email_audit_logs', function (Blueprint $table) {
            $table->id();
            $table->string('template_key')->nullable()->index();
            $table->string('subject');
            $table->json('recipients');
            $table->enum('origin', ['system', 'test'])->default('system')->index();
            $table->json('variables')->nullable();
            $table->json('redacted_variables')->nullable();
            $table->string('provider_id')->nullable();
            $table->enum('status', ['pending', 'sent', 'failed'])->default('pending')->index();
            $table->text('error_message')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->integer('duration_ms')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('ip_address')->nullable();
            $table->timestamps();

            $table->index('created_at');
            $table->index(['status', 'origin']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('email_audit_logs');
    }
}

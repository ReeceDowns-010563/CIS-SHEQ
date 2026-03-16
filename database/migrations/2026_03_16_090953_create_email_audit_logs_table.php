<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('email_audit_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
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
            $table->unsignedBigInteger('user_id')->nullable()->index('email_audit_logs_user_id_foreign');
            $table->string('ip_address')->nullable();
            $table->timestamp('created_at')->nullable()->index();
            $table->timestamp('updated_at')->nullable();

            $table->index(['status', 'origin']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_audit_logs');
    }
};

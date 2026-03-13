<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmailTestSendLimitsTable extends Migration
{
    public function up(): void
    {
        Schema::create('email_test_send_limits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->integer('sends_count')->default(0);
            $table->timestamp('window_start')->nullable();
            $table->timestamps();

            $table->unique('user_id');
            $table->index(['user_id', 'window_start']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('email_test_send_limits');
    }
}

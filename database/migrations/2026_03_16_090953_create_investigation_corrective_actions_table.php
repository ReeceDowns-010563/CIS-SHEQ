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
        Schema::create('investigation_corrective_actions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('actionable_type');
            $table->unsignedBigInteger('actionable_id');
            $table->text('corrective_actions');
            $table->unsignedBigInteger('user_id')->index('investigation_corrective_actions_user_id_foreign');
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('investigation_corrective_actions');
    }
};

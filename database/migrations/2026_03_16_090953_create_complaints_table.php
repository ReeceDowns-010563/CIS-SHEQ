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
        Schema::create('complaints', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('date_received');
            $table->string('name');
            $table->string('pcn_number')->nullable();
            $table->unsignedBigInteger('site_id')->nullable()->index('complaints_site_id_foreign');
            $table->text('nature');
            $table->date('date_acknowledged')->nullable();
            $table->string('status')->default('open');
            $table->unsignedBigInteger('assigned_to')->nullable()->index('complaints_assigned_to_foreign');
            $table->text('conclusion')->nullable();
            $table->date('date_concluded')->nullable();
            $table->string('ico_complaint')->nullable();
            $table->timestamps();
            $table->boolean('archived')->default(false);
            $table->unsignedBigInteger('archived_by')->nullable()->index('complaints_archived_by_foreign');
            $table->timestamp('archived_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};

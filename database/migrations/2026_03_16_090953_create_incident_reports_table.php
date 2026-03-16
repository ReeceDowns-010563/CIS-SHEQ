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
        Schema::create('incident_reports', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('brief_description');
            $table->integer('incident_type_id')->nullable();
            $table->string('incident_type_other_description')->nullable();
            $table->string('location');
            $table->text('additional_information')->nullable();
            $table->json('attachments')->nullable();
            $table->enum('affected_person_source', ['Employee', 'Customer', 'Other']);
            $table->unsignedBigInteger('affected_employee_id')->nullable();
            $table->unsignedBigInteger('affected_customer_id')->nullable();
            $table->string('affected_person_other')->nullable();
            $table->enum('reported_by_source', ['Employee', 'Customer', 'Other']);
            $table->unsignedBigInteger('reported_employee_id')->nullable();
            $table->unsignedBigInteger('reported_customer_id')->nullable();
            $table->string('reported_by_other')->nullable();
            $table->unsignedBigInteger('treatment_type_id');
            $table->text('physician_details')->nullable();
            $table->date('date_of_occurrence');
            $table->time('time_of_occurrence');
            $table->enum('work_shift', ['Morning', 'Afternoon', 'Night', 'Split', 'Other']);
            $table->decimal('hours_worked_prior', 4)->nullable();
            $table->unsignedBigInteger('branch_id');
            $table->unsignedBigInteger('site_id')->nullable();
            $table->unsignedBigInteger('agency_id')->nullable();
            $table->longText('body_part_id')->nullable();
            $table->integer('mechanism_id')->nullable();
            $table->string('body_part_other')->nullable();
            $table->integer('injury_type_id')->nullable();
            $table->string('injury_type_other')->nullable();
            $table->text('what_happened');
            $table->unsignedBigInteger('coordinator_id')->nullable();
            $table->string('status')->default('pending');
            $table->boolean('archived')->default(false);
            $table->unsignedBigInteger('archived_by')->nullable()->index('incident_reports_archived_by_foreign');
            $table->timestamp('archived_at')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->index(['incident_type_id', 'incident_type_other_description'], 'idx_incident_type_other');
            $table->index(['archived', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incident_reports');
    }
};

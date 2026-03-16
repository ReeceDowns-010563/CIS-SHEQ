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
        Schema::table('incident_comments', function (Blueprint $table) {
            $table->foreign(['incident_id'])->references(['id'])->on('incident_reports')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['user_id'])->references(['id'])->on('users')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('incident_comments', function (Blueprint $table) {
            $table->dropForeign('incident_comments_incident_id_foreign');
            $table->dropForeign('incident_comments_user_id_foreign');
        });
    }
};

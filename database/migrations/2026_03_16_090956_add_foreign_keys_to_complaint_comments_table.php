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
        Schema::table('complaint_comments', function (Blueprint $table) {
            $table->foreign(['complaint_id'])->references(['id'])->on('complaints')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['user_id'])->references(['id'])->on('users')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('complaint_comments', function (Blueprint $table) {
            $table->dropForeign('complaint_comments_complaint_id_foreign');
            $table->dropForeign('complaint_comments_user_id_foreign');
        });
    }
};

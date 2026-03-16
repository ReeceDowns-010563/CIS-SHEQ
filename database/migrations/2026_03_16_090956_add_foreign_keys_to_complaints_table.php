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
        Schema::table('complaints', function (Blueprint $table) {
            $table->foreign(['archived_by'])->references(['id'])->on('users')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['assigned_to'])->references(['id'])->on('users')->onUpdate('restrict')->onDelete('set null');
            $table->foreign(['site_id'])->references(['id'])->on('sites')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('complaints', function (Blueprint $table) {
            $table->dropForeign('complaints_archived_by_foreign');
            $table->dropForeign('complaints_assigned_to_foreign');
            $table->dropForeign('complaints_site_id_foreign');
        });
    }
};

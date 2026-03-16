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
        Schema::table('user_site_access', function (Blueprint $table) {
            $table->foreign(['site_id'])->references(['id'])->on('sites')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['user_id'])->references(['id'])->on('users')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_site_access', function (Blueprint $table) {
            $table->dropForeign('user_site_access_site_id_foreign');
            $table->dropForeign('user_site_access_user_id_foreign');
        });
    }
};

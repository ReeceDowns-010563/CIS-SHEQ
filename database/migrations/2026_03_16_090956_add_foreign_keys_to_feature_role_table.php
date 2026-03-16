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
        Schema::table('feature_role', function (Blueprint $table) {
            $table->foreign(['feature_id'])->references(['id'])->on('features')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['role_id'])->references(['id'])->on('roles')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('feature_role', function (Blueprint $table) {
            $table->dropForeign('feature_role_feature_id_foreign');
            $table->dropForeign('feature_role_role_id_foreign');
        });
    }
};

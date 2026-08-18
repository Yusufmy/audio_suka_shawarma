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
        Schema::table('outlets', function (Blueprint $table) {
            // foreground = app sedang dibuka & dipakai
            // background = app masih hidup tapi tidak di layar
            // offline    = app tertutup/mati (atau heartbeat basi)
            $table->string('presence')
                ->default('offline')
                ->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('outlets', function (Blueprint $table) {
            $table->dropColumn('presence');
        });
    }
};

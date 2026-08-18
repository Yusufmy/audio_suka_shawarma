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
        Schema::create('outlets', function (Blueprint $table) {
            $table->id();

            // Kode unik outlet, contoh: OTL-001
            $table->string('code')->unique();

            // Nama outlet
            $table->string('name')->unique();

            // Status koneksi outlet
            $table->enum('status', [
                'online',
                'offline',
            ])->default('offline');

            // Kondisi outlet: ramai / sepi
            $table->boolean('is_busy')
                ->default(false);

            // Terakhir kali outlet mengirim heartbeat
            $table->timestamp('last_seen_at')
                ->nullable();

            // Waktu pertama/terakhir outlet berhasil pairing
            $table->timestamp('paired_at')
                ->nullable();

            // Informasi device tablet outlet
            $table->json('device_info')
                ->nullable();

            $table->softDeletes();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('outlets');
    }
};

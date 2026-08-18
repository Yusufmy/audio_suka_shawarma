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
        Schema::create('broadcast_targets', function (Blueprint $table) {
            $table->id();

            // Broadcast yang dikirim
            $table->foreignId('broadcast_id')
                ->constrained('broadcasts')
                ->cascadeOnDelete();

            // Outlet penerima
            $table->foreignId('outlet_id')
                ->constrained('outlets')
                ->cascadeOnDelete();

            // Status broadcast pada outlet ini
            $table->enum('status', [
                'pending',
                'delivered',
                'playing',
                'completed',
                'failed',
                'skipped',
            ])->default('pending');

            // Waktu broadcast berhasil diterima outlet
            $table->timestamp('delivered_at')
                ->nullable();

            // Waktu outlet selesai memutar audio
            $table->timestamp('completed_at')
                ->nullable();

            // Pesan error jika broadcast gagal
            $table->string('error_message')
                ->nullable();

            $table->timestamps();

            // Satu outlet tidak boleh menjadi target yang sama
            // lebih dari satu kali dalam satu broadcast.
            $table->unique([
                'broadcast_id',
                'outlet_id',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('broadcast_targets');
    }
};

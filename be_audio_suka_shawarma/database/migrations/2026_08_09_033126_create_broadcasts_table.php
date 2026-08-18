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
        Schema::create('broadcasts', function (Blueprint $table) {
            $table->id();

            // Operator yang membuat broadcast
            $table->foreignId('operator_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Jenis broadcast
            $table->enum('type', [
                'live',
                'upload',
                'schedule',
            ]);

            // File audio
            // NULL untuk broadcast live
            $table->foreignId('audio_file_id')
                ->nullable()
                ->constrained('audio_files')
                ->nullOnDelete();

            // Target broadcast
            $table->enum('target_mode', [
                'all',
                'specific',
            ]);

            // Status broadcast
            $table->enum('status', [
                'scheduled',
                'live',
                'completed',
                'cancelled',
                'failed',
            ])->default('scheduled');

            // Waktu broadcast dijadwalkan
            $table->timestamp('scheduled_at')
                ->nullable();

            // Waktu broadcast dimulai
            $table->timestamp('started_at')
                ->nullable();

            // Waktu broadcast selesai
            $table->timestamp('ended_at')
                ->nullable();

            // Durasi broadcast dalam detik
            $table->unsignedInteger('duration_seconds')
                ->nullable();

            // Room/channel untuk Live Audio
            $table->string('rtc_room_id')
                ->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('broadcasts');
    }
};

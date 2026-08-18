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
        Schema::create('audio_files', function (Blueprint $table) {
            $table->id();

            // Operator yang mengupload audio
            $table->foreignId('operator_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Nama asli file
            $table->string('original_name');

            // Lokasi file di storage
            $table->string('file_path');

            // Durasi audio dalam detik
            $table->unsignedInteger('duration_seconds')
                ->nullable();

            // Ukuran file dalam bytes
            $table->unsignedBigInteger('size_bytes')
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
        Schema::dropIfExists('audio_files');
    }
};

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
        Schema::create('outlet_events', function (Blueprint $table) {
            $table->id();

            // Outlet yang menghasilkan event
            $table->foreignId('outlet_id')
                ->constrained('outlets')
                ->cascadeOnDelete();

            // Jenis event
            $table->enum('type', [
                'connected',
                'disconnected',
                'reconnecting',
                'speaker_test',
                'broadcast_error',
                'session_expired',
            ]);

            // Informasi tambahan event
            $table->json('meta')
                ->nullable();

            $table->timestamp('created_at')
                ->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('outlet_events');
    }
};

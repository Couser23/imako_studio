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
        Schema::create('studio_settings', function (Blueprint $table) {
            $table->id();
            // Identitas Studio
            $table->string('name')->default('Imako Studio');
            $table->string('tagline')->nullable();
            $table->text('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('email')->nullable();
            
            // Jam Operasional & Pengaturan Booking
            $table->time('open_time')->default('09:00:00');
            $table->time('close_time')->default('18:00:00');
            $table->json('operational_days')->nullable(); // e.g., ["Sen","Sel","Rab","Kam","Jum","Sab","Min"]
            $table->json('closed_dates')->nullable(); // For temporary closures
            
            $table->integer('min_booking_hours')->default(3);
            $table->integer('max_booking_months')->default(3);
            
            // Media Sosial
            $table->string('instagram')->nullable();
            $table->string('facebook')->nullable();
            $table->string('whatsapp_link')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('studio_settings');
    }
};

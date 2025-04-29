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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id_1')->constrained()->onDelete('cascade')->unsigned();
            $table->foreignId('user_id_0')->constrained()->onDelete('cascade')->unsigned();
            $table->foreignId('service_id')->constrained()->onDelete('cascade')->unsigned();
            $table->dateTime('booking_time'); // Időpont
            $table->boolean('active')->nullable();
            $table->timestamps();
        });
     }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};

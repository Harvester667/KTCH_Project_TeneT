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
            $table->dateTime('booking_time'); // Időpont
            $table->timestamps();
        });

        // Pivot tábla a bookings és users között
        Schema::create('booking_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->onDelete('cascade'); // Kapcsolat a bookings táblával
            $table->foreignId('user_id')->onDelete('cascade'); // Kapcsolat a users táblával
            $table->timestamps();
        });

        // Pivot tábla a bookings és services között
        Schema::create('booking_service', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->onDelete('cascade'); // Kapcsolat a bookings táblával
            $table->foreignId('service_id')->onDelete('cascade'); // Kapcsolat a services táblával
            $table->timestamps();
        });  
        // Schema::create('bookings', function (Blueprint $table) {
        //     $table->id();
        //     $table->foreignId('customer_id')->constrained();
        //     $table->foreignId('employee_id')->constrained();
        //     $table->foreignId('service_id')->constrained();
        //     $table->dateTime('duration');
        //     //$table->timestamps();
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_service');
        Schema::dropIfExists('booking_user');
        Schema::dropIfExists('bookings');
    }
};

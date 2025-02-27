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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('customer', 51);
            $table->string('email', 51)->comment('Egyedi email');
            $table->string('phone', 20)->comment('Egyedi telefonszám');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password', 20);
            $table->enum('gender', ['férfi', 'nő', 'szabadon választott']);
            $table->integer('power')->default(0);
            $table->rememberToken();
            //$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};

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
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('phone')->comment('Egyedi telefonszám');
            $table->enum('gender', ['férfi', 'nő', 'szabadon választott']);
            $table->string('invoice_address')->nullable();
            $table->string('invoice_postcode')->nullable();
            $table->string('invoice_city')->nullable();
            // $table->foreignId('invoice_country_id')->nullable()->constrained('countries');
            $table->date('birth_date')->nullable();
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

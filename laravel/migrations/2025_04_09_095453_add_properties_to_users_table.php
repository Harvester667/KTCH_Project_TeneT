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
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable();
            $table->enum('gender',['férfi','nő','szabadon választott'])->nullable();
            $table->string('invoice_address')->nullable();
            $table->string('invoice_postcode')->nullable();
            $table->string('invoice_city')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('qualifications')->nullable();
            $table->string('description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn( 'phone' );
            $table->dropColumn( 'gender' );
            $table->dropColumn( 'invoice_address' );
            $table->dropColumn( 'invoice_postcode' );
            $table->dropColumn( 'invoice_city' );
            $table->dropColumn( 'birth_date' );
            $table->dropColumn( 'qualifications' );
            $table->dropColumn( 'description' );            
        });
    }
};

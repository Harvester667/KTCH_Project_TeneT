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
            $table->integer('admin');
            $table->integer('role');
            $table->boolean('active');
            $table->string('phone')->nullable();
            $table->enum('gender',['férfi','nő','szabadon választott'])->nullable();
            $table->string('invoice_address')->nullable();
            $table->string('invoice_postcode')->nullable();
            $table->string('invoice_city')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('qualifications')->nullable();
            $table->string('description')->nullable();
            $table->integer('login_counter')->default( 0 );
            $table->timestamp('banning_time')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};

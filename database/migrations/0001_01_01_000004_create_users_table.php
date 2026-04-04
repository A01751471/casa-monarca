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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            
            $table->foreignId('area_id')->nullable()->constrained('areas');
            $table->foreignId('role_id')->constrained('roles');
            
            // Ciclo de vida (Alta, Revocación, Baja, Rastreo)
            $table->enum('status', ['pendiente', 'alta', 'baja', 'revocacion'])->default('pendiente');
            $table->unsignedBigInteger('approved_by')->nullable(); // ID del que dio el Alta
            
            // Espacio para la Identidad Digital
            $table->text('public_key')->nullable(); 
            $table->rememberToken();
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

    }
};

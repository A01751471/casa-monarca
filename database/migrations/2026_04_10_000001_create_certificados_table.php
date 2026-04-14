<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('certificados', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedBigInteger('emitido_por')->nullable(); // admin que aprobó
            $table->text('public_key');
            $table->string('fingerprint', 64)->unique(); // SHA-256 del public key
            $table->string('algoritmo', 20)->default('RSA-2048');
            $table->timestamp('emitido_at');
            $table->timestamp('vence_at');
            $table->timestamp('revocado_at')->nullable();
            $table->enum('status', ['activo', 'revocado', 'vencido'])->default('activo');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certificados');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('firmas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('documento_id')->constrained('documentos')->cascadeOnDelete();
            $table->foreignId('firmado_por')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('certificado_id')->nullable()->constrained('certificados')->nullOnDelete();
            $table->text('firma_b64'); // firma criptográfica RSA en base64
            $table->timestamp('firmado_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('firmas');
    }
};

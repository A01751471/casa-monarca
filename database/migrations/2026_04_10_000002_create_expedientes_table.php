<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expedientes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('migrante_perfil_id')->nullable()->constrained('migrante_perfiles')->nullOnDelete();
            $table->foreignId('colaborador_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('area_id')->constrained('areas');
            $table->enum('status', ['sin_asignar', 'en_proceso', 'terminado'])->default('sin_asignar');
            $table->text('notas')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expedientes');
    }
};

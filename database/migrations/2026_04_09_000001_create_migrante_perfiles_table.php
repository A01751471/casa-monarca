<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('migrante_perfiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();

            // Paso 1: Datos Personales
            $table->date('fecha_atencion');
            $table->string('nombre');
            $table->string('primer_apellido');
            $table->string('segundo_apellido')->nullable();
            $table->string('telefono')->nullable();
            $table->string('genero');
            $table->string('pais_origen');
            $table->string('departamento_estado')->nullable();
            $table->string('estado_civil');
            $table->date('fecha_nacimiento');
            $table->string('rango_edad');         // '18-59', '60+'
            $table->string('grupo_poblacion');    // Adulto, Niña acompañada, NNA, etc.

            // Paso 2: Situación Migratoria
            $table->string('motivo_salida')->nullable();
            $table->integer('num_acompanantes')->default(0);
            $table->text('integrantes_grupo')->nullable();
            $table->string('documentacion')->nullable();
            $table->text('necesidades_especiales')->nullable();
            $table->string('destino_final')->nullable();

            // Control interno
            $table->enum('status', ['pendiente', 'activo', 'cerrado'])->default('pendiente');
            $table->unsignedBigInteger('registrado_por')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('migrante_perfiles');
    }
};

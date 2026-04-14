<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Sin FK a users — rastro inmutable aunque el usuario sea borrado
        Schema::create('actividad_log', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('actor_id')->nullable();   // ID snapshot, no FK
            $table->string('actor_nombre');                        // nombre snapshot
            $table->string('accion', 80);                         // aprobó_usuario, firmó_documento, etc.
            $table->string('modelo_tipo', 60)->nullable();         // App\Models\User, etc.
            $table->unsignedBigInteger('modelo_id')->nullable();
            $table->json('payload')->nullable();                   // datos antes/después
            $table->string('ip', 45)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('actividad_log');
    }
};

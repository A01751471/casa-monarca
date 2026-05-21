<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('documentos', function (Blueprint $table) {
            // Ownership: docs can now belong directly to a user (identidad)
            // or to a case (expediente). Make expediente_id nullable.
            $table->foreignId('user_id')
                  ->nullable()
                  ->after('id')
                  ->constrained('users')
                  ->nullOnDelete();

            $table->unsignedBigInteger('expediente_id')
                  ->nullable()
                  ->change();

            // categoria distinguishes case docs from personal identity docs
            $table->enum('categoria', ['expediente', 'identidad'])
                  ->default('expediente')
                  ->after('subido_por');

            // Human-readable label: "Acta de nacimiento", "Pasaporte", etc.
            $table->string('etiqueta', 100)->nullable()->after('categoria');
        });
    }

    public function down(): void
    {
        Schema::table('documentos', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id', 'categoria', 'etiqueta']);

            $table->unsignedBigInteger('expediente_id')
                  ->nullable(false)
                  ->change();
        });
    }
};

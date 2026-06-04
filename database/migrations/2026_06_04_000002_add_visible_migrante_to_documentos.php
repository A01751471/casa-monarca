<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('documentos', function (Blueprint $table) {
            // true  = coordinador aprobó (migrante puede ver y descargar)
            // false = en revisión, solo visible para staff
            $table->boolean('visible_migrante')->default(true)->after('sellado_at');
        });
    }

    public function down(): void
    {
        Schema::table('documentos', function (Blueprint $table) {
            $table->dropColumn('visible_migrante');
        });
    }
};

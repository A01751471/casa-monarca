<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('expedientes', function (Blueprint $table) {
            $table->string('folio', 20)->nullable()->unique()->after('id');
            $table->foreignId('resuelto_por')->nullable()->constrained('users')->nullOnDelete()->after('notas');
            $table->timestamp('resuelto_at')->nullable()->after('resuelto_por');
        });
    }

    public function down(): void
    {
        Schema::table('expedientes', function (Blueprint $table) {
            $table->dropForeign(['resuelto_por']);
            $table->dropColumn(['folio', 'resuelto_por', 'resuelto_at']);
        });
    }
};

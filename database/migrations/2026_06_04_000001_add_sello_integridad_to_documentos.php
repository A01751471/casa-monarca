<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('documentos', function (Blueprint $table) {
            $table->string('sello_integridad', 64)->nullable()->after('hash_sha256');
            $table->timestamp('sellado_at')->nullable()->after('sello_integridad');
        });
    }

    public function down(): void
    {
        Schema::table('documentos', function (Blueprint $table) {
            $table->dropColumn(['sello_integridad', 'sellado_at']);
        });
    }
};

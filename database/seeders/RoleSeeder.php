<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Nivel 1 — CRUD completo
        Role::Create(['name' => 'Administrador', 'nivel_acceso' => 1]);
        // Nivel 2 — CRU (sin eliminar); 5 áreas: Humanitario, Psicosocial, Legal, Comunicación, Administración
        Role::Create(['name' => 'Coordinador', 'nivel_acceso' => 2]);
        // Nivel 3 — CR (solo crear y leer)
        Role::Create(['name' => 'Operativo', 'nivel_acceso' => 3]);
        // Nivel 4 — C (solo registrar datos de beneficiarios)
        Role::Create(['name' => 'Usuario', 'nivel_acceso' => 4]);
        // Nivel 5 — R propio (acceso de solo lectura a su expediente)
        Role::Create(['name' => 'Migrante', 'nivel_acceso' => 5]);
    }
}

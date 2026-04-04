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
        Role::Create(['name' => 'Administrador', 'nivel_acceso' => 1]);
        Role::Create(['name' => 'Coordinador', 'nivel_acceso' => 2]);
        Role::Create(['name' => 'Operativo', 'nivel_acceso' => 3]);
        Role::Create(['name' => 'Colaborador Externo', 'nivel_acceso' => 4]);
        Role::Create(['name' => 'Migrante', 'nivel_acceso' => 4]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Area;

class AreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $areas = [
            ['id' => 1, 'nombre' => 'Humanitaria'],
            ['id' => 2, 'nombre' => 'PsicoSocial'],
            ['id' => 3, 'nombre' => 'Legal'],
            ['id' => 4, 'nombre' => 'Comunicación'],
            ['id' => 5, 'nombre' => 'Almacén'],
            ['id' => 6, 'nombre' => 'Tecnologías de Información'],
        ];

        foreach ($areas as $area) {
            \App\Models\Area::updateOrCreate(['id' => $area['id']], $area);
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;


class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        \App\Models\User::create([
            'name' => 'Administrador Casa Monarca',
            'email' => 'correo@casamonarca.com', // <--- CAMBIA ESTO
            'password' => Hash::make('casamonarca'), // <--- CAMBIA ESTO
            'role_requested' => 'admin',
            'status' => 'approved',
            'area_id' => null, // El admin no necesita un área específica
        ]);
    }
}

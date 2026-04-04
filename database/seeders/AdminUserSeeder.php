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
            'name' => 'Admin',
            'email' => 'correo@casamonarca.com',
            'password' => bcrypt('casamonarca'), 
            'status' => 'alta',           
            'area_id' => 6, 
            'role_id' => 1,
        ]);
    }
}

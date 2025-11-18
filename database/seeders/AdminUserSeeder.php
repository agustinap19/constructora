<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // 👑 Usuario administrador inicial
        $admin = User::firstOrCreate(
            ['email' => 'agustinapaza1817@gmail.com'],
            [
                'nombres' => 'Agustin',
                'apellido_paterno' => 'Cruz',
                'apellido_materno' => 'Mamani',
                'password' => Hash::make('123qwe'),
                'email_verified_at' => now(),
            ]
        );

        if (! $admin->hasRole('Admin')) {
            $admin->assignRole('Admin');
        }

        $this->command->info('👤 Usuario administrador creado: admin@constructora.test / password');
    }
}

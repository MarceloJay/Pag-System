<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Cria um Administrador
        $user = User::create([
            'name' => 'Admin',
            'email' => 'pagsystem@example.com',
            'password' => bcrypt('23ELXzZd'),
        ]);

        // Obtém a função de administrador (role)
        $adminRole = Role::where('id', 1)->first();

        // Vincula a função de administrador ao usuário criado
        $user->roles()->attach($adminRole);
    }
}

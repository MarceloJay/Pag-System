<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Cria a role "admin"
        Role::create(['name' => 'admin']);

        // Cria a role "cliente"
        Role::create(['name' => 'cliente']);
    }
}


<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Criar usuário admin
        User::create([
            'first_name' => 'Admin',
            'last_name' => 'Brave',
            'email' => 'admin@example.com',
            'password' => Hash::make('12345678'),
        ]);

        // Criar usuários fictícios
        User::factory(10)->create();
    }
}

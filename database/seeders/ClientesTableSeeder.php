<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Cliente;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ClientesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'name' => 'Fabiano',
            'email' => 'fabiano@gmail.com',
            'password' => Hash::make('123456789'),
        ]);

        // Crie o cliente
        Cliente::create([
            'caixa_postal' => '168FP',
            'user_id' => $user->id,
            // Outros campos
        ]);
        $user->assignRole('client');

        $user = User::create([
            'name' => 'Mario Vernazza',
            'email' => 'mario@gmail.com',
            'password' => Hash::make('123456789'),
        ]);

        // Crie o cliente
        Cliente::create([
            'caixa_postal' => '004MV',
            'user_id' => $user->id,
            // Outros campos
        ]);
        $user->assignRole('client');

        $user = User::create([
            'name' => 'Luciano',
            'email' => 'luciano@gmail.com',
            'password' => Hash::make('123456789'),
        ]);

        // Crie o cliente
        Cliente::create([
            'caixa_postal' => '001LN',
            'user_id' => $user->id,
            // Outros campos
        ]);
        $user->assignRole('client');
    }
}

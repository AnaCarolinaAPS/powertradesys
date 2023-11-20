<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Permission::create(['name' => 'editar clientes']);
        // Permission::create(['name' => 'visualizar clientes']);

        // Encontre o usuário pelo seu ID ou de outra forma adequada
        $user = User::find(1);

        // Encontre a função (role) que você deseja associar ao usuário
        $role = Role::findByName('admin'); // Substitua 'editor' pelo nome da função desejada

        // Associe a função ao usuário
        $user->assignRole($role);
        // $role->givePermissionTo(Permission::findByName('editar clientes'));
    }
}

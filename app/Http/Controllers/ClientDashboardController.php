<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\User;

class ClientDashboardController extends Controller
{
    public function index()
    {
        // Encontre a função (role) "guest"
        $role = Role::findByName('guest'); // Substitua 'guest' pelo nome da função desejada

        // Use a função 'users' para obter todos os usuários com a função (role) especificada
        $usuariosNaoClientes = $role->users;

        // Encontre a função (role) "guest"
        $role = Role::findByName('client'); // Substitua 'guest' pelo nome da função desejada
        $clientesComUsuarios = $role->users;

        return view('client.index', compact('usuariosNaoClientes', 'clientesComUsuarios')); // Isso assume que você possui uma vista chamada 'admin.dashboard.index'
    }
}

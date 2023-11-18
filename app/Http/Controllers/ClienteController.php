<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\User;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $usuariosNaoClientes = User::whereDoesntHave('cliente')
        //                         ->where('role', 'client')
        //                         ->get();
        $usuariosNaoClientes = User::whereHas('roles', function ($query) {
            $query->where('name', 'guest');
        })->get();
        $clientesComUsuarios = Cliente::with('user')->get();
        return view('admin.cliente.index', compact('usuariosNaoClientes', 'clientesComUsuarios'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Valide os dados do formulário, se necessário
        $request->validate([
            'caixa_postal' => 'required|string|max:6',
            // Outras regras de validação para outros campos
        ]);

        // Crie o cliente
        Cliente::create([
            'caixa_postal' => $request->input('caixa_postal'),
            'user_id' => $request->input('user_id'),
            // Outros campos
        ]);

        $usuario = User::find($request->input('user_id'));
        // Remova a role 'guest' (ou a role que deseja remover)
        $usuario->removeRole('guest');

        // Atribua a role 'client'
        $usuario->assignRole('client');

        // Redirecione ou faça o que for necessário após criar o cliente
        // return redirect('/alguma-rota');
        // $usuariosNaoClientes = User::whereHas('roles', function ($query) {
        //     $query->where('name', 'guest');
        // })->get();
        // $clientesComUsuarios = Cliente::with('user')->get();
        // return view('admin.cliente.index', compact('usuariosNaoClientes', 'clientesComUsuarios'));

        // Carrega a view com os detalhes do cliente
        return back()->with('success', 'Cliente criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Recupera o cliente pelo ID
        $cliente = Cliente::find($id);

        // Verifica se o cliente foi encontrado
        if (!$cliente) {
            // Redireciona ou exibe uma mensagem de erro
            return redirect('/alguma-rota')->with('error', 'Cliente não encontrado.');
        }

        // Carrega a view com os detalhes do cliente
        return back()->with('success', 'Cliente criado com sucesso!');

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cliente $cliente)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cliente $cliente)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cliente $cliente)
    {
        //
    }
}

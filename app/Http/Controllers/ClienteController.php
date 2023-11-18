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
        try {
            // Valide os dados do formulário, se necessário
            $request->validate([
                'caixa_postal' => 'required|string|max:6|unique:clientes',
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

            // Exibir toastr de sucesso
            return redirect()->route('clientes.index')->with('toastr', [
                'type'    => 'success',
                'message' => 'Cliente criado com sucesso!',
                'title'   => 'Sucesso',
            ]);
        } catch (\Exception $e) {
            // Exibir toastr de Erro
            return redirect()->route('clientes.index')->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao criar o Cliente: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
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

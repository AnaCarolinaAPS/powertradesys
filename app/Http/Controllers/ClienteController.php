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
                'apelido' => 'string',
                // Outras regras de validação para outros campos
            ]);

            // Crie o cliente
            Cliente::create([
                'caixa_postal' => $request->input('caixa_postal'),
                'user_id' => $request->input('user_id'),
                'apelido' => $request->input('apelido'),
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
        try {
            // Buscar o item pelo ID
            $cliente = Cliente::findOrFail($id);

            // Retornar a view com os detalhes do shipper
            return view('admin.cliente.show', compact('cliente'));
        } catch (\Exception $e) {
            // Exibir uma mensagem de erro ou redirecionar para uma página de erro
            return redirect()->route('clientes.index')->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao exibir os detalhes do Cliente: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cliente $cliente)
    {
        try {
            $request->validate([
                'apelido' => 'required|string|max:255|unique:clientes',
                // Adicione outras regras de validação conforme necessário
            ]);

            // Atualizar os dados
            $cliente->update([
                'apelido' => $request->input('apelido'),
                // Adicione outros campos conforme necessário
            ]);

            // Exibir toastr de sucesso
            return redirect()->back()->with('toastr', [
                'type'    => 'success',
                'message' => 'Cliente atualizado com sucesso!',
                'title'   => 'Sucesso',
            ]);
        } catch (\Exception $e) {
            // Exibir toastr de Erro
            return redirect()->back()->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao atualizar o Cliente: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cliente $cliente)
    {
        try {
            // Buscar o item pelo ID
            $cliente = Cliente::findOrFail($cliente->id);

            $user = $cliente->user;
            $user->update([
                'status' => 'inactive',
                // Adicione outros campos conforme necessário
            ]);

            // Exibir toastr de sucesso
            return redirect()->back()->with('toastr', [
                'type'    => 'success',
                'message' => 'Cliente inativado com sucesso!',
                'title'   => 'Sucesso',
            ]);
        } catch (\Exception $e) {
            // Exibir toastr de Erro
            return redirect()->back()->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao atualizar o Cliente: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Fornecedor;
use Illuminate\Http\Request;

class DespachanteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Lógica para mostrar uma lista de despachantes
        $all_items = Fornecedor::where('tipo', 'despachante')->get();
        return view('admin.despachante.index', compact('all_items'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validação dos dados do formulário
            $request->validate([
                'nome' => 'required|string|max:255|unique:fornecedors',
                'contato' => 'string|max:255',
                // Adicione outras regras de validação conforme necessário
            ]);

            // Criação de um novo Despachante no banco de dados
            $despachante = Fornecedor::create([
                'nome' => $request->input('nome'),
                'contato' => $request->input('contato'),
                'tipo' => 'despachante',
                // Adicione outros campos conforme necessário
            ]);

            // Exibir toastr de sucesso
            return redirect()->route('despachantes.show', ['despachante' => $despachante->id])->with('toastr', [
                'type'    => 'success',
                'message' => 'Despachante criado com sucesso!',
                'title'   => 'Sucesso',
            ]);
        } catch (\Exception $e) {
            // Exibir toastr de Erro
            return redirect()->route('despachantes.index')->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao criar o Despachante: <br>'. $e->getMessage(),
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
            // Buscar o despachante pelo ID
            $despachante = Fornecedor::findOrFail($id);

            // Retornar a view com os detalhes do despachante
            return view('admin.despachante.show', compact('despachante'));
        } catch (\Exception $e) {
            // Exibir uma mensagem de erro ou redirecionar para uma página de erro
            return redirect()->route('despachantes.index')->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao exibir os detalhes do Despachante: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Fornecedor $despachante)
    {
        try {

            if ($despachante->nome == $request->input('nome')) {
                // Validação dos dados do formulário
                $request->validate([
                    'contato' => 'string|max:255',
                    // Adicione outras regras de validação conforme necessário
                ]);

                // Atualizar os dados do Shipper
                $despachante->update([
                    'contato' => $request->input('contato'),
                    // Adicione outros campos conforme necessário
                ]);
            } else {
                // Validação dos dados do formulário
                $request->validate([
                    'nome' => 'required|string|max:255|unique:fornecedors',
                    'contato' => 'string|max:255',
                    // Adicione outras regras de validação conforme necessário
                ]);

                // Atualizar os dados do Shipper
                $despachante->update([
                    'nome' => $request->input('nome'),
                    'contato' => $request->input('contato'),
                    // Adicione outros campos conforme necessário
                ]);
            }

            // Exibir toastr de sucesso
            return redirect()->route('despachantes.show', ['despachante' => $despachante->id])->with('toastr', [
                'type'    => 'success',
                'message' => 'Despachante atualizado com sucesso!',
                'title'   => 'Sucesso',
            ]);
        } catch (\Exception $e) {
            // Exibir toastr de Erro
            return redirect()->route('despachantes.show', ['despachante' => $despachante->id])->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao atualizar o Despachante: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Fornecedor $despachante)
    {
        // if ($fornecedor->pacotes()->count() > 0) {
        //     return redirect()->back()->with('toastr', [
        //         'type'    => 'error',
        //         'message' => 'Não é possível excluir a Warehouse, pois ele possui pacotes associados.',
        //         'title'   => 'Erro',
        //     ]);
        // }

        try {
            // Excluir o Despachante do banco de dados
            $despachante->delete();

            // Redirecionar após a exclusão bem-sucedida
            return redirect()->route('despachantes.index')->with('toastr', [
                'type'    => 'success',
                'message' => 'Despachante excluído com sucesso!',
                'title'   => 'Sucesso',
            ]);
        } catch (\Exception $e) {
            // Exibir toastr de erro se ocorrer uma exceção
            return redirect()->back()->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao atualizar o Despachante: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
    }

}

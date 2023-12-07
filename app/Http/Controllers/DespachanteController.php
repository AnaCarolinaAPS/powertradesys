<?php

namespace App\Http\Controllers;

use App\Models\Despachante;
use Illuminate\Http\Request;

class DespachanteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $all_items = Despachante::all();
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
                'nome' => 'required|string|max:255|unique:despachantes',
                'contato' => 'string|max:255',
                // Adicione outras regras de validação conforme necessário
            ]);

            // Criação de um novo Shipper no banco de dados
            $despachante = Despachante::create([
                'nome' => $request->input('nome'),
                'contato' => $request->input('contato'),
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
            // Buscar o shipper pelo ID
            $despachante = Despachante::findOrFail($id);

            // Retornar a view com os detalhes do shipper
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
    public function update(Request $request, Despachante $despachante)
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
                    'nome' => 'required|string|max:255|unique:despachantes',
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
    public function destroy(Despachante $despachante)
    {
        // if ($fornecedor->pacotes()->count() > 0) {
        //     return redirect()->back()->with('toastr', [
        //         'type'    => 'error',
        //         'message' => 'Não é possível excluir a Warehouse, pois ele possui pacotes associados.',
        //         'title'   => 'Erro',
        //     ]);
        // }

        try {
            // Excluir o Shipper do banco de dados
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
                'message' => 'Ocorreu um erro ao atualizar a Despachante: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
    }

}

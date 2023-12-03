<?php

namespace App\Http\Controllers;

use App\Models\Fornecedor;
use Illuminate\Http\Request;

class FornecedorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $all_items = Fornecedor::all();
        return view('admin.fornecedor.index', compact('all_items'));
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
                'tipo' => 'required|in:warehouse,despachante,transporte'
                // Adicione outras regras de validação conforme necessário
            ]);

            // Criação de um novo Shipper no banco de dados
            $fornecedor = Fornecedor::create([
                'nome' => $request->input('nome'),
                'tipo' => $request->input('tipo'),
                // Adicione outros campos conforme necessário
            ]);

            // Exibir toastr de sucesso
            return redirect()->route('fornecedores.show', ['fornecedor' => $fornecedor->id])->with('toastr', [
                'type'    => 'success',
                'message' => 'Fornecedor criado com sucesso!',
                'title'   => 'Sucesso',
            ]);
        } catch (\Exception $e) {
            // Exibir toastr de Erro
            return redirect()->route('fornecedores.index')->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao criar o Fornecedor: <br>'. $e->getMessage(),
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
            $fornecedor = Fornecedor::findOrFail($id);

            // Retornar a view com os detalhes do shipper
            return view('admin.fornecedor.show', compact('fornecedor'));
        } catch (\Exception $e) {
            // Exibir uma mensagem de erro ou redirecionar para uma página de erro
            return redirect()->route('fornecedores.index')->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao exibir os detalhes do Fornecedor: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Fornecedor $fornecedor)
    {
        try {
            // Validação dos dados do formulário
            $request->validate([
                'nome' => 'required|string|max:255|unique:fornecedors',
                'tipo' => 'required|in:warehouse,despachante,transporte'
                // Adicione outras regras de validação conforme necessário
            ]);

            // Atualizar os dados do Shipper
            $fornecedor->update([
                'nome' => $request->input('nome'),
                'tipo' => $request->input('tipo'),
                // Adicione outros campos conforme necessário
            ]);

            // Exibir toastr de sucesso
            return redirect()->route('fornecedores.show', ['fornecedor' => $fornecedor->id])->with('toastr', [
                'type'    => 'success',
                'message' => 'Fornecedor atualizado com sucesso!',
                'title'   => 'Sucesso',
            ]);
        } catch (\Exception $e) {
            // Exibir toastr de Erro
            return redirect()->route('fornecedores.show', ['fornecedor' => $fornecedor->id])->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao atualizar o Fornecedor: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Fornecedor $fornecedor)
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
            $fornecedor->delete();

            // Redirecionar após a exclusão bem-sucedida
            return redirect()->route('fornecedores.index')->with('toastr', [
                'type'    => 'success',
                'message' => 'Forncedor excluído com sucesso!',
                'title'   => 'Sucesso',
            ]);
        } catch (\Exception $e) {
            // Exibir toastr de erro se ocorrer uma exceção
            return redirect()->back()->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao atualizar a Forncedor: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
    }

}

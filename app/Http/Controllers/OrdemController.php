<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ordem;
use App\Models\Motor;
use App\Models\Caixa;
use App\Models\MotorProovedor;

class OrdemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $all_items = Ordem::all();
        return view('admin.ordem.index', compact('all_items'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validação dos dados do formulário
            $request->validate([
                'referencia' => 'nullable|string|max:255|',
                'data_compra' => 'required|date',
                // Adicione outras regras de validação conforme necessário
            ]);

            // Criação de um novo item no banco de dados
            $ordem = Ordem::create([
                'referencia' => $request->input('referencia'),
                'data_compra' => $request->input('data_compra'),
                // Adicione outros campos conforme necessário
            ]);

            // Exibir toastr de sucesso
            return redirect()->route('ordem.show', ['ordem' => $ordem->id])->with('toastr', [
                'type'    => 'success',
                'message' => 'Ordem criada com sucesso!',
                'title'   => 'Sucesso',
            ]);
        } catch (\Exception $e) {
            // Exibir toastr de Erro
            return redirect()->route('ordem.index')->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao criar a Ordem: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $item = Ordem::findOrFail($id);
            $all_items = Motor::all();
            $all_caixas = Caixa::all();
            $all_proovedores = MotorProovedor::all();

            // Retornar a view com os detalhes do shipper
            return view('admin.ordem.show', compact('item', 'all_items', 'all_caixas', 'all_proovedores'));
        } catch (\Exception $e) {
            // Exibir uma mensagem de erro ou redirecionar para uma página de erro
            return redirect()->back()->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao exibir os detalhes da Ordem: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            // Validação dos dados do formulário
            $validatedData = $request->validate([
                'referencia' => 'nullable|string|max:255',
                'data_compra' => 'required|date',
                'data_recebido' => 'nullable|date',
                'data_entrega' => 'nullable|date',
                'cliente' => 'nullable|string|max:255',
                // Adicione outras regras de validação conforme necessário
            ]);

            // Encontra o item a ser atualizado
            $ordem = Ordem::find($id);
            // Atualiza o item com os valores validados
            $ordem->update($validatedData);

            // Exibir toastr de sucesso
            return redirect()->back()->with('toastr', [
                'type'    => 'success',
                'message' => 'Ordem atualizada com sucesso!',
                'title'   => 'Sucesso',
            ]);
        } catch (\Exception $e) {
            // Exibir toastr de Erro
            return redirect()->back()->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao atualizar a Ordem: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            // Encontra o item a ser atualizado
            $ordem = Ordem::findOrFail($id);
            
            // Excluir o item do banco de dados
            $ordem->delete();

            // Redirecionar após a exclusão bem-sucedida
            return redirect()->route('ordem.index')->with('toastr', [
                'type'    => 'success',
                'message' => 'Ordem excluída com sucesso!',
                'title'   => 'Sucesso',
            ]);
        } catch (\Exception $e) {
            // Exibir toastr de erro se ocorrer uma exceção
            return redirect()->route('ordem.index')->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao excluir a Ordem: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
    }
}

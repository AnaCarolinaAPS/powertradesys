<?php

namespace App\Http\Controllers;

use App\Models\OrdemItem;
use Illuminate\Http\Request;

class OrdemItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        #
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validação dos dados do formulário
            $validatedData = $request->validate([
                'ordem_id' => 'required|exists:ordems,id',
                'motor_id' => 'required|exists:motors,id',
                'quantidade' => 'nullable|numeric',
                'valor' => 'nullable|numeric',
                'tipo_moeda' => 'nullable|in:dolar,reais,guaranis',
                // Adicione outras regras de validação conforme necessário
            ]);
            
            // Criação de uma nova entidade no banco de dados
            $item = OrdemItem::create($validatedData);

            // Exibir toastr de sucesso
            return redirect()->back()->with('toastr', [
                'type'    => 'success',
                'message' => 'Item criado com sucesso!',
                'title'   => 'Sucesso',
            ]);
        } catch (\Exception $e) {
            // Exibir toastr de Erro
            return redirect()->back()->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao criar o Item: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $item = OrdemItem::find($id);
        return response()->json($item);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            // Validação dos dados do formulário
            $validatedData = $request->validate([
                'quantidade' => 'required|numeric',
                'valor' => 'nullable|numeric',
                'tipo_moeda' => 'nullable|in:dolar,reais,guaranis',
                // Adicione outras regras de validação conforme necessário
            ]);

            // Encontra o item a ser atualizado
            $item = OrdemItem::find($id);
            // Atualiza o item com os valores validados
            $item->update($validatedData);

            // Exibir toastr de sucesso
            return redirect()->back()->with('toastr', [
                'type'    => 'success',
                'message' => 'Item atualizado com sucesso!',
                'title'   => 'Sucesso',
            ]);
        } catch (\Exception $e) {
            // Exibir toastr de Erro
            return redirect()->back()->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao atualizar o Item: <br>'. $e->getMessage(),
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
            $ordem = OrdemItem::findOrFail($id);
            
            // Excluir o item do banco de dados
            $ordem->delete();

            // Redirecionar após a exclusão bem-sucedida
            return redirect()->back()->with('toastr', [
                'type'    => 'success',
                'message' => 'Item excluído com sucesso!',
                'title'   => 'Sucesso',
            ]);
        } catch (\Exception $e) {
            // Exibir toastr de erro se ocorrer uma exceção
            return redirect()->back()->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao excluir o Item: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
    }
}

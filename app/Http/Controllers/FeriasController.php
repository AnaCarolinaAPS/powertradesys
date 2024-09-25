<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ferias;

class FeriasController extends Controller
{  
    public function show($id)
    {
        $ferias = Ferias::find($id);
        return response()->json($ferias);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validação dos dados do formulário
            $validatedData = $request->validate([
                'observacao' => 'nullable|string|max:255',
                'data_inicio' => 'required|date',
                'data_fim' => 'required|date',
                'funcionario_id' => 'required|exists:funcionarios,id',
                // Adicione outras regras de validação conforme necessário
            ]);

            $ferias = Ferias::create($validatedData);

            // Exibir toastr de sucesso
            return redirect()->back()->with('toastr', [
                'type'    => 'success',
                'message' => 'Férias criadas com sucesso!',
                'title'   => 'Sucesso',
            ]);
        } catch (\Exception $e) {
            // Exibir toastr de Erro
            return redirect()->back()->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao criar Férias: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
    }

    // Atualiza um item existente no banco de dados
    public function update(Request $request, Ferias $ferias)
    {
        try {
            $validatedData = $request->validate([
                'observacao' => 'nullable|string|max:255',
                'data_inicio' => 'required|date',
                'data_fim' => 'required|date',
                // Adicione outras regras de validação conforme necessário
            ]);

            $ferias->update($validatedData);

            // Exibir toastr de sucesso
            return redirect()->back()->with('toastr', [
                'type'    => 'success',
                'message' => 'Férias atualizadas com sucesso!',
                'title'   => 'Sucesso',
            ]);
        } catch (\Exception $e) {
            // Exibir toastr de Erro
            return redirect()->back()->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao atualizar Férias: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
    }

    // Remove um funcionário do banco de dados
    public function destroy($id)
    {
        try {
            $ferias = Ferias::findOrFail($id);

            // Excluir item do banco de dados
            $ferias->delete();

            // Redirecionar após a exclusão bem-sucedida
            return redirect()->back()->with('toastr', [
                'type'    => 'success',
                'message' => 'Férias excluídas com sucesso!',
                'title'   => 'Sucesso',
            ]);
        } catch (\Exception $e) {
            // Exibir toastr de erro se ocorrer uma exceção
            return redirect()->back()->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao excluir Férias: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
    }
}

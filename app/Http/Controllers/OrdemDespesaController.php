<?php

namespace App\Http\Controllers;

use App\Models\OrdemDespesa;
use Illuminate\Http\Request;

class OrdemDespesaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validação dos dados do formulário
            $request->validate([
                'data' => 'required|date',
                'ordem_id' => 'required|exists:ordems,id',
                'motor_proovedors_id' => 'required|exists:motor_proovedors,id',
                // Adicione outras regras de validação conforme necessário
            ]);

            // Criação de um novo item no banco de dados
            $despesa = OrdemDespesa::create([
                'data' => $request->input('data'),
                'ordem_id' => $request->input('ordem_id'),
                'motor_proovedors_id' => $request->input('motor_proovedors_id'),
                // Adicione outros campos conforme necessário
            ]);

            // Exibir toastr de sucesso
            return redirect()->route('ordem_despesa.show', ['item' => $despesa->id])->with('toastr', [
                'type'    => 'success',
                'message' => 'Despesa criada com sucesso!',
                'title'   => 'Sucesso',
            ]);
        } catch (\Exception $e) {
            // Exibir toastr de Erro
            return redirect()->back()->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao criar a Despesa: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(OrdemDespesa $ordemDespesa)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, OrdemDespesa $ordemDespesa)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $despesa = OrdemDespesa::find($id);
            $ordem = $despesa->ordem_id;
            $despesa->delete();

            // Redirecionar após a exclusão bem-sucedida
            return redirect()->route('ordem.show', ['ordem' => $ordem->id])->with('toastr', [
                'type'    => 'success',
                'message' => 'Despesa excluída com sucesso!',
                'title'   => 'Sucesso',
            ]);
        } catch (\Exception $e) {
            // Exibir toastr de erro se ocorrer uma exceção
            return redirect()->back()->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao excluir a Despesa: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
    }
}

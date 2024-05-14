<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Despesa;
use App\Models\ServicosFornecedor;

class DespesaController extends Controller
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
                'fatura_carga_id' => 'required|exists:fatura_cargas,id',
                'fornecedor_id' => 'required|exists:fornecedors,id',
                // Adicione outras regras de validação conforme necessário
            ]);

            // Criação de um novo item no banco de dados
            $despesa = Despesa::create([
                'data' => $request->input('data'),
                'fatura_carga_id' => $request->input('fatura_carga_id'),
                'fornecedor_id' => $request->input('fornecedor_id'),
                // Adicione outros campos conforme necessário
            ]);

            // Exibir toastr de sucesso
            return redirect()->route('despesas.show', ['despesa' => $despesa->id])->with('toastr', [
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
    public function show(string $id)
    {
        // Lógica para mostrar um embarcador específico
        try {
            // Buscar o shipper pelo ID
            $despesa = Despesa::findOrFail($id);
            $all_servicos = ServicosFornecedor::where('fornecedor_id', $despesa->fornecedor_id)->get();

            // Retornar a view com os detalhes do shipper
            return view('admin.despesa.show', compact('despesa', 'all_servicos'));
        } catch (\Exception $e) {
            // Exibir uma mensagem de erro ou redirecionar para uma página de erro
            return redirect()->back()->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao exibir os detalhes da Despesa: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DespesaItem;
use App\Models\Despesa;
use App\Models\ServicosFornecedor;

class DespesaItemController extends Controller
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
                'despesa_id' => 'required|exists:despesas,id',
                'peso_guia' => 'required|numeric',
                // Adicione outras regras de validação conforme necessário
            ]);

            // IDs dos pacotes selecionados
            $servicosSelecionados = $request->input('servico_fornecedor_id');

             // Lógica para atualizar os pacotes com o código da carga
             foreach ($servicosSelecionados as $servicoId) {
                $servico = ServicosFornecedor::findOrFail($servicoId);
                if ($servico) {
                    $valor_servico = 0;
                    if ($servico->tipo_preco == 'kgs guia') {
                        $valor_servico = $servico->preco * $request->input('peso_guia');
                    } else if ($servico->tipo_preco == 'fixo') {
                        $valor_servico = $servico->preco;
                    } else {
                        $valor_servico = 0;
                    }

                    // Criação de um novo item no banco de dados
                    $despesaItem = DespesaItem::create([
                        'despesa_id' => $request->input('despesa_id'),
                        'servico_fornecedor_id' => $servicoId,
                        'valor' => $valor_servico,
                        // Adicione outros campos conforme necessário
                    ]);
                }
            }

            // Exibir toastr de sucesso
            return redirect()->back()->with('toastr', [
                'type'    => 'success',
                'message' => 'Item da despesa criada com sucesso!',
                'title'   => 'Sucesso',
            ]);
        } catch (\Exception $e) {
            // Exibir toastr de Erro
            return redirect()->back()->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao criar o Item da Despesa: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $despesaItem = DespesaItem::with('servico_fornecedor')->findOrFail($id);
        return response()->json($despesaItem);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            // Validação dos dados do formulário
            $request->validate([
                'id' => 'required|exists:despesa_items,id',
                'valor' => 'required|numeric',
                // Adicione outras regras de validação conforme necessário
            ]);

            $despesa = DespesaItem::findOrFail($request->input('id'));
            // Atualizar os dados do Pacote

            $despesa->update([
                'valor' => $request->input('valor'),
                // Adicione outros campos conforme necessário
            ]);

            // Exibir toastr de sucesso
            return redirect()->back()->with('toastr', [
                'type'    => 'success',
                'message' => 'Item da Despesa atualizado com sucesso!',
                'title'   => 'Sucesso',
            ]);
        } catch (\Exception $e) {
            // Exibir toastr de Erro
            return redirect()->back()->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao atualizar o Item da Despesa: <br>'. $e->getMessage(),
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
            $despesa = DespesaItem::find($id);
            $despesa->delete();

            // Redirecionar após a exclusão bem-sucedida
            return redirect()->back()->with('toastr', [
                'type'    => 'success',
                'message' => 'Item da Despesa excluída com sucesso!',
                'title'   => 'Sucesso',
            ]);
        } catch (\Exception $e) {
            // Exibir toastr de erro se ocorrer uma exceção
            return redirect()->back()->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao excluir o Item da Despesa: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
    }
}

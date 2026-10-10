<?php

namespace App\Http\Controllers;

use App\Models\OrdemDespesaItem;
use App\Models\MotorProovedorServico;
use Illuminate\Http\Request;

class OrdemDespesaItemController extends Controller
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
                'ordem_despesa_id' => 'required|exists:ordem_despesas,id',
                'motor_proovedor_servico_id' => 'required|exists:motor_proovedor_servicos,id',
                // Adicione outras regras de validação conforme necessário
            ]);

            // IDs dos serviços selecionados
            $servicosSelecionados = $request->input('motor_proovedor_servico_id');

            // Lógica para incluir os serviços como novos itens ()
            foreach ($servicosSelecionados as $servicoId) {
                $servico = MotorProovedorServico::findOrFail($servicoId);
                if ($servico) {
                    $valor_servico = 0;

                    if ($servico->preco > 0) {
                        $valor_servico = $servico->preco;
                        $tipo_moeda = $servico->tipo_moeda;
                    } else {
                        $valor_servico = 0;
                        $tipo_moeda = 'dolar';
                    }

                    // Criação de um novo item no banco de dados
                    $despesaItem = OrdemDespesaItem::create([
                        'ordem_despesa_id' => $request->input('ordem_despesa_id'),
                        'motor_proovedor_servico_id' => $servico->id,
                        'valor' => $valor_servico,
                        'tipo_moeda' => $tipo_moeda,
                        // Adicione outros campos conforme necessário
                    ]);
                }
            }

            // Exibir toastr de sucesso
            return redirect()->back()->with('toastr', [
                'type'    => 'success',
                'message' => 'Item da Despesa criada com sucesso!',
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
    public function show(string $id)
    {
        $despesaItem = OrdemDespesaItem::with('servico.motor')->findOrFail($id);
        return response()->json($despesaItem);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            // Validação dos dados do formulário
            $validatedData = $request->validate([
                'valor' => 'required|numeric',
                'tipo_moeda' => 'nullable|in:dolar,reais,guaranis',
                // Adicione outras regras de validação conforme necessário
            ]);

            $despesa = OrdemDespesaItem::findOrFail($id);
            // Atualiza o item com os valores validados
            $despesa->update($validatedData);

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
            $despesa = OrdemDespesaItem::find($id);
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

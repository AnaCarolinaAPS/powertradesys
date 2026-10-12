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
            // Encontra a entidade que vai ser excluída
            $despesa_item = OrdemDespesaItem::findOrFail($id);
            // Busca o valor total pago da despesa
            $valor_total_pago = $despesa_item->despesa->valor_pago();
            // Busca o valor total da despesa (todos os itens)
            $valor_total_despesa = $despesa_item->despesa->valor_total();
            // Verifica se existem pagamentos dessa despesa
            if ($valor_total_pago > 0){
                // Existem pagamentos associados
                $valor_sem_despesa_excluida = $valor_total_despesa - $despesa_item->valor;
                // Caso o valor fique Negativo (despesa a ser excluida é MAIOR do que os Pagamentos feitos)
                if ($valor_sem_despesa_excluida <= 0) {
                    // Redirecionar após a exclusão bem-sucedida
                    return redirect()->back()->with('toastr', [
                        'type'    => 'error',
                        'message' => 'Não é possível excluir o item da despesa, pois ela possui pagamentos associados.',
                        'title'   => 'Erro',
                    ]);
                }
                // Caso a despesa possua mais itens que justifiquem os pagamentos recebidos
                $despesa_item->delete();                
            } else {
                // Caso não tenham pagamentos atrelados a despesa, podemos excluir o item
                $despesa_item->delete();
            }             

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

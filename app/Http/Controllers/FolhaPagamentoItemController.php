<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FolhaPagamentoItem;
use App\Models\ServicosFuncionario;

class FolhaPagamentoItemController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {

            // Validação dos dados do formulário
            $request->validate([
                'folha_pagamento_id' => 'required|exists:folha_pagamentos,id',
                'data' => 'required|date',
                // Adicione outras regras de validação conforme necessário
            ]);

            // IDs dos pacotes selecionados
            $servicosSelecionados = $request->input('servico_funcionario_id');

             // Lógica para atualizar os pacotes com o código da carga
             foreach ($servicosSelecionados as $servicoId) {
                $servico = ServicosFuncionario::findOrFail($servicoId);
                if ($servico) {
                    $valor_servico = $servico->valor;

                    // Criação de um novo item no banco de dados
                    $folhaItem = FolhaPagamentoItem::create([
                        'folha_pagamento_id' => $request->input('folha_pagamento_id'),
                        'servicos_funcionario_id' => $servicoId,
                        'valor' => $valor_servico,
                        'data' => $request->input('data'),
                        // Adicione outros campos conforme necessário
                    ]);
                }
            }

            // Exibir toastr de sucesso
            return redirect()->back()->with('toastr', [
                'type'    => 'success',
                'message' => 'Item da Folha de Pagamento criado com sucesso!',
                'title'   => 'Sucesso',
            ]);
        } catch (\Exception $e) {
            // Exibir toastr de Erro
            return redirect()->back()->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao criar o Item da Folha de Pagamento: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $folhaItem = FolhaPagamentoItem::with('servicosF')->findOrFail($id);
        return response()->json($folhaItem);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            // Validação dos dados do formulário
            $request->validate([
                'id' => 'required|exists:folha_pagamento_items,id',
                'valor' => 'required|numeric',
                // Adicione outras regras de validação conforme necessário
            ]);

            $folha = FolhaPagamentoItem::findOrFail($request->input('id'));
            // Atualizar os dados do Pacote

            $folha->update([
                'valor' => $request->input('valor'),
                // Adicione outros campos conforme necessário
            ]);

            // Exibir toastr de sucesso
            return redirect()->back()->with('toastr', [
                'type'    => 'success',
                'message' => 'Item da Folha de Pagamento atualizado com sucesso!',
                'title'   => 'Sucesso',
            ]);
        } catch (\Exception $e) {
            // Exibir toastr de Erro
            return redirect()->back()->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao atualizar o Item da Folha de Pagamento: <br>'. $e->getMessage(),
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
            $folha = FolhaPagamentoItem::find($id);
            $folha->delete();

            // Redirecionar após a exclusão bem-sucedida
            return redirect()->back()->with('toastr', [
                'type'    => 'success',
                'message' => 'Item da Folha de Pagamento excluído com sucesso!',
                'title'   => 'Sucesso',
            ]);
        } catch (\Exception $e) {
            // Exibir toastr de erro se ocorrer uma exceção
            return redirect()->back()->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao excluir o Item da Folha de Pagamento: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
    }
}

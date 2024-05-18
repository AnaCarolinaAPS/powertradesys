<?php

namespace App\Http\Controllers;

use App\Models\Pagamento;
use App\Models\Invoice;
use App\Models\FluxoCaixa;
use App\Models\FechamentoCaixa;
use App\Models\Cliente;
use App\Models\Fornecedor;
use Illuminate\Http\Request;

class PagamentoController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {

            // Validação dos dados do formulário
            $request->validate([
                'tipo' => 'required|in:Pagamento,Despesa',
                'data_pagamento' => 'required|date',
                'valor' => 'required|numeric',
                'observacoes' => 'nullable|string',
                'caixa_origem_id' => 'required|exists:caixas,id',
                'valor_pgto' => 'required|numeric',
                // Adicione outras regras de validação conforme necessário
            ]);

            //Data retira Mês e Ano para buscar o fechamando do caixa de DESTINO
            $data = \Carbon\Carbon::createFromFormat('Y-m-d', $request->input('data_pagamento'));
            $ano = $data->year;
            $mes = $data->month;
            $fechamento = FechamentoCaixa::where('caixa_id', $request->input('caixa_origem_id'))->where('mes', $mes)->where('ano', $ano)->firstOrFail();

            if ($request->input('tipo') == "Pagamento") {
                // Validação dos dados do formulário
                $request->validate([
                    'cliente_id' => 'required|exists:clientes,id',
                    // Adicione outras regras de validação conforme necessário
                ]);

                $cliente = Cliente::findOrFail($request->input('cliente_id'));
                $descricao = 'Pgto '.$cliente->user->name.' de '.$request->input('valor').' U$';
                $tipo = 'entrada';
                $valor_pgto = $request->input('valor_pgto');
                $valor = $request->input('valor');

            } else {
                // Validação dos dados do formulário
                $request->validate([
                    'fornecedor_id' => 'required|exists:fornecedors,id',
                    // Adicione outras regras de validação conforme necessário
                ]);

                $fornecedor = Fornecedor::findOrFail($request->input('fornecedor_id'));
                $descricao = 'Pago '.$request->input('valor').' U$ para '.$fornecedor->nome;
                $tipo = 'despesa';
                if ($request->input('valor_pgto') > 0) {
                    $valor_pgto = $request->input('valor_pgto')*-1;
                } else {
                    $valor_pgto = $request->input('valor_pgto');
                }
                if ($valor = $request->input('valor') > 0) {
                    $valor = $request->input('valor')*-1;
                } else {
                    $valor = $request->input('valor');
                }
            }

            // ************************************
            // Cria o movimento no CAIXA
            // ************************************

            //Necessário criar um FLUXO_CAIXA (entrada de valores);
            $fluxo = FluxoCaixa::create([
                'data' => $request->input('data_pagamento'),
                'descricao' => $descricao,
                'tipo' => $tipo,
                'caixa_origem_id' => $request->input('caixa_origem_id'),
                'valor_origem' => $valor_pgto,
                'fechamento_caixa_id' => $fechamento->id,
                // Adicione outros campos conforme necessário
            ]);

            $fechamento->atualizaSaldo($valor_pgto);

            // ************************************
            // Cria o PAGAMENTO para ser atrelado a(s) Invoice(s)
            // ************************************
            if ($request->input('tipo') == "Pagamento") {
                //Cria o Pagamento
                $pagamento = Pagamento::create([
                    'data_pagamento' => $request->input('data_pagamento'),
                    'valor' => $valor,
                    'observacoes' => $request->input('observacoes'),
                    'fluxo_caixa_id' => $fluxo->id,
                    // Adicione outros campos conforme necessário
                ]);

                $valorRestante = 0;
                // Chama o método distribuirPagamento no controlador de Invoice
                $invoiceController = new InvoiceController();
                $valorRestante = $invoiceController->distribuirPagamento($cliente, $pagamento);

                //VERIFICA se o $valorRestante é MAIOR que 0, significa que o cliente ganhou um crédito
                if ($valorRestante > 0) {
                    return redirect()->back()->with('toastr', [
                        'type'    => 'info',
                        'message' => 'CLIENTE GEROU UM CREDITO!',
                        'title'   => 'Sucesso',
                    ]);
                }
            } else {
                //Cria o Pagamento
                $pagamento = Pagamento::create([
                    'data_pagamento' => $request->input('data_pagamento'),
                    'valor' => $valor*-1,
                    'observacoes' => $request->input('observacoes'),
                    'fluxo_caixa_id' => $fluxo->id,
                    // Adicione outros campos conforme necessário
                ]);

                $valorRestante = 0;
                // Chama o método distribuirPagamento no controlador de Despesa
                $despesaController = new DespesaController();
                $valorRestante = $despesaController->distribuirPagamento($fornecedor, $pagamento);

                //VERIFICA se o $valorRestante é MAIOR que 0, significa que o cliente ganhou um crédito
                if ($valorRestante > 0) {
                    return redirect()->back()->with('toastr', [
                        'type'    => 'info',
                        'message' => 'A EMPRESA GEROU UM CREDITO!',
                        'title'   => 'Sucesso',
                    ]);
                }

            }

            return redirect()->back()->with('toastr', [
                'type'    => 'success',
                'message' => 'Pagamento criado com sucesso!',
                'title'   => 'Sucesso',
            ]);
        } catch (\Exception $e) {
            // Exibir toastr de Erro
            return redirect()->back()->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao criar o Pagamento: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
    }

    public function show($id)
    {
        $pagamento = Pagamento::find($id);

        // Verifique se o pagamento foi encontrado
        if (!$pagamento) {
            return response()->json(['error' => 'Pagamento não encontrado'], 404);
        }

        $fluxo_caixa = FluxoCaixa::find($pagamento->fluxo_caixa_id);
        // Coletar informações relevantes da tabela pivot
        $informacoesPivot = [];
        foreach ($pagamento->invoices as $invoice) {
            $informacoesPivot[$invoice->id] = [
                'valor_recebido' => $pagamento->invoices->find($invoice->id)->pivot->valor_recebido,
                // Adicione mais informações da tabela pivot conforme necessário
            ];
        }

        // Adicione as informações da tabela pivot ao JSON do pagamento
        $pagamento->informacoes_pagamento = $informacoesPivot;
        $pagamento->informacoes_fluxo = $fluxo_caixa;

        return response()->json($pagamento);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $pagamento = Pagamento::find($id);

            // Excluir o Freteiro do banco de dados
            $pagamento->delete();

            // Redirecionar após a exclusão bem-sucedida
            return redirect()->back()->with('toastr', [
                'type'    => 'success',
                'message' => 'Pagamento excluído com sucesso!',
                'title'   => 'Sucesso',
            ]);
        } catch (\Exception $e) {
            // Exibir toastr de erro se ocorrer uma exceção
            return redirect()->back()->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao excluir o Pagamento: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
    }
}

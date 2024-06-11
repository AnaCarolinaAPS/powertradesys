<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Pagamento;
use App\Models\Invoice;
use App\Models\Despesa;
use App\Models\FolhaPagamento;
use App\Models\FluxoCaixa;
use App\Models\FechamentoCaixa;
use App\Models\Cliente;
use App\Models\Fornecedor;
use App\Models\Funcionario;
use Illuminate\Http\Request;

class PagamentoController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // DB::beginTransaction();
        try {

            // Validação dos dados do formulário
            $request->validate([
                'tipo' => 'required|in:Pagamento,Despesa,Salario',
                'data_pagamento' => 'required|date',
                'valor' => 'required|numeric',
                'observacoes' => 'nullable|string',
                'caixa_origem_id' => 'required|exists:caixas,id',
                'valor_pgto' => 'required|numeric',
                // Adicione outras regras de validação conforme necessário
            ]);

            //Data retira as das referentas a semana para buscar o fechamento do caixa de DESTINO
            $dataCarbon = \Carbon\Carbon::createFromFormat('Y-m-d', $request->input('data_pagamento'));
            $start_date = $dataCarbon->startOfWeek(\Carbon\Carbon::SUNDAY)->format('Y-m-d');
            $end_date = $dataCarbon->endOfWeek(\Carbon\Carbon::SATURDAY)->format('Y-m-d');

            $fechamento = FechamentoCaixa::where('caixa_id', $request->input('caixa_origem_id'))->where('start_date', $start_date)->where('end_date', $end_date)->first();

            if ($fechamento == null) {
                return redirect()->back()->with('toastr', [
                    'type'    => 'error',
                    'message' => 'A Caixa escolhida NÃO está ABERTA.',
                    'title'   => 'Erro',
                ]);
            }

            if ($request->input('tipo') == "Pagamento") {
                // Validação dos dados do formulário
                $request->validate([
                    'cliente_id' => 'required|exists:clientes,id',
                    'invoice_id' => 'required|exists:invoices,id',
                    // Adicione outras regras de validação conforme necessário
                ]);

                $cliente = Cliente::findOrFail($request->input('cliente_id'));
                $descricao = 'Pgto '.$cliente->user->name.' de '.$request->input('valor').' U$';
                $tipo = 'entrada';
                $valor_pgto = $request->input('valor_pgto');
                $valor = $request->input('valor');

            } else if ($request->input('tipo') == "Salario") {
                // Validação dos dados do formulário
                $request->validate([
                    'funcionario_id' => 'required|exists:funcionarios,id',
                    'folha_pagamento_id' => 'required|exists:folha_pagamentos,id',
                    // Adicione outras regras de validação conforme necessário
                ]);

                $funcionario = Funcionario::findOrFail($request->input('funcionario_id'));
                $descricao = 'Pago '.$request->input('valor').' '.$fechamento->caixa->moeda.' para '.$funcionario->nome;
                $tipo = 'salario';
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
            } else {
                // Validação dos dados do formulário
                $request->validate([
                    'fornecedor_id' => 'required|exists:fornecedors,id',
                    'despesa_id' => 'required|exists:despesas,id',
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
                'fechamento_origem_id' => $fechamento->id,
                'valor_origem' => $valor_pgto,
                // Adicione outros campos conforme necessário
            ]);

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

                //Faz o pagamento da INVOICE que foi inserido o pagamento
                $invoice = Invoice::findOrFail($request->input('invoice_id'));
                // Chama o método distribuirPagamento no controlador de Invoice
                $invoiceController = new InvoiceController();
                $valorRestante = $invoiceController->distribuirPagamento($cliente, $pagamento, $invoice);

                //VERIFICA se o $valorRestante é MAIOR que 0, significa que o cliente ganhou um crédito
                if ($valorRestante > 0) {
                    return redirect()->back()->with('toastr', [
                        'type'    => 'info',
                        'message' => 'CLIENTE GEROU UM CREDITO!',
                        'title'   => 'Sucesso',
                    ]);
                }
            } else if ($request->input('tipo') == "Salario") {
                //Cria o Pagamento
                $pagamentoS = Pagamento::create([
                    'data_pagamento' => $request->input('data_pagamento'),
                    'valor' => $valor*-1,
                    'observacoes' => $request->input('observacoes'),
                    'fluxo_caixa_id' => $fluxo->id,
                    'tipo' => 'Salario'
                    // Adicione outros campos conforme necessário
                ]);

                $valorRestante = 0;

                //Faz o pagamento da DESPESA que foi inserido o pagamento
                $folha = FolhaPagamento::findOrFail($request->input('folha_pagamento_id'));
                // Chama o método distribuirPagamento no controlador de Despesa
                $folhaController = new FolhaPagamentoController();
                $valorRestante = $folhaController->distribuirPagamento($funcionario, $pagamentoS, $folha);

                //VERIFICA se o $valorRestante é MAIOR que 0, significa que o cliente ganhou um crédito
                if ($valorRestante > 0) {
                    return redirect()->back()->with('toastr', [
                        'type'    => 'info',
                        'message' => 'A EMPRESA GEROU UM CREDITO!',
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
                    'tipo' => 'Despesa'
                    // Adicione outros campos conforme necessário
                ]);

                $valorRestante = 0;

                //Faz o pagamento da DESPESA que foi inserido o pagamento
                $despesa = Despesa::findOrFail($request->input('despesa_id'));
                // Chama o método distribuirPagamento no controlador de Despesa
                $despesaController = new DespesaController();
                $valorRestante = $despesaController->distribuirPagamento($fornecedor, $pagamento, $despesa);

                //VERIFICA se o $valorRestante é MAIOR que 0, significa que o cliente ganhou um crédito
                if ($valorRestante > 0) {
                    return redirect()->back()->with('toastr', [
                        'type'    => 'info',
                        'message' => 'A EMPRESA GEROU UM CREDITO!',
                        'title'   => 'Sucesso',
                    ]);
                }

            }

            // Commit da transação
            // DB::commit();

            return redirect()->back()->with('toastr', [
                'type'    => 'success',
                'message' => 'Pagamento criado com sucesso!',
                'title'   => 'Sucesso',
            ]);
        } catch (\Exception $e) {
            // DB::rollBack();
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

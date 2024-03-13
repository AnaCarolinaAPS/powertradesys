<?php

namespace App\Http\Controllers;

use App\Models\Pagamento;
use App\Models\Invoice;
use App\Models\FluxoCaixa;
use App\Models\FechamentoCaixa;
use App\Models\Cliente;
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
                'cliente_id' => 'required|exists:clientes,id',
                'data_pagamento' => 'required|date',
                'valor' => 'required|numeric',
                'observacoes' => 'nullable|string',
                'caixa_origem_id' => 'required|exists:caixas,id',
                'valor_pgto' => 'required|numeric',
                // Adicione outras regras de validação conforme necessário
            ]);

            $cliente = Cliente::findOrFail($request->input('cliente_id'));

            // ************************************
            // Cria o movimento de ENTRADA no CAIXA
            // ************************************

            //Data retira Mês e Ano para buscar o fechamando do caixa de DESTINO
            $data = \Carbon\Carbon::createFromFormat('Y-m-d', $request->input('data_pagamento'));
            $ano = $data->year;
            $mes = $data->month;
            $fechamento = FechamentoCaixa::where('caixa_id', $request->input('caixa_origem_id'))->where('mes', $mes)->where('ano', $ano)->firstOrFail();

            //Necessário criar um FLUXO_CAIXA (entrada de valores);
            $fluxo = FluxoCaixa::create([
                'data' => $request->input('data_pagamento'),
                'descricao' => 'Pgto '.$cliente->user->name.' de '.$request->input('valor').' U$',
                'tipo' => 'entrada',
                'caixa_origem_id' => $request->input('caixa_origem_id'),
                'valor_origem' => $request->input('valor_pgto'),
                'fechamento_caixa_id' => $fechamento->id,
                // Adicione outros campos conforme necessário
            ]);

            $fechamento->atualizaSaldo($request->input('valor_pgto'));

            // ************************************
            // Cria o PAGAMENTO para ser atrelado a(s) Invoice(s)
            // ************************************

            //Cria o Pagamento
            $pagamento = Pagamento::create([
                'data_pagamento' => $request->input('data_pagamento'),
                'valor' => $request->input('valor'),
                'observacoes' => $request->input('observacoes'),
                'fluxo_caixa_id' => $fluxo->id,
                // Adicione outros campos conforme necessário
            ]);

            // Filtra TODAS as invoices que tem valores em aberto
            $invoicesEmAberto = $cliente->invoices()->get()->filter(function ($invoice) {
                return $invoice->valor_pago < $invoice->valor_total();
            });

            $valorRestante = $request->input('valor');

            //Distribuir o valor pago, entre as invoices ABERTAS
            $invoicesEmAberto = $invoicesEmAberto->sortBy('data');

            foreach ($invoicesEmAberto as $aberto) {
                //Calcula o valor em ABERTO da Invoice
                $saldoAberto = $aberto->valor_total() - $aberto->valor_pago;

                // Verificar se o valor restante pode pagar totalmente a invoice atual
                if ($valorRestante >= $saldoAberto) {
                    // O valor pago é suficiente para pagar totalmente esta invoice
                    $valorRestante -= $saldoAberto;
                    // Atualiza a coluna da invoice com o pagamento
                    $aberto->atualizaPago($saldoAberto);
                    // Registrar o pagamento para esta invoice
                    $aberto->pagamentos()->attach($pagamento->id);

                //Caso ainda existam invoices não pagas, e um valor em valorRestante, é possível fazer o pagamento PARCIAL
                } else {
                    if ($valorRestante > 0) {
                        // Atualiza a coluna da invoice com o pagamento do valor RESTANTE (o que sobrou dos pagamentos)
                        $aberto->atualizaPago($valorRestante);
                        // Registrar o pagamento para esta invoice
                        $aberto->pagamentos()->attach($pagamento->id);
                        $valorRestante = 0;
                    } else {
                        //Não existem mais valores para serem registrado (quebra o foreach)
                        break;
                    }
                }
            }

            //VERIFICA se o $valorRestante é MAIOR que 0, significa que o cliente ganhou um crédito
            if ($valorRestante > 0) {
                return redirect()->back()->with('toastr', [
                    'type'    => 'info',
                    'message' => 'CLIENTE GEROU UM CREDITO!',
                    'title'   => 'Sucesso',
                ]);
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
}

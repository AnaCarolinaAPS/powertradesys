<?php

namespace App\Http\Controllers;

use App\Models\Pagamento;
use App\Models\Invoice;
use App\Models\FluxoCaixa;
use App\Models\FechamentoCaixa;
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
                'invoice_id' => 'required|exists:invoices,id',
                'data_pagamento' => 'required|date',
                'valor' => 'required|numeric',
                'observacoes' => 'nullable|string',
                'caixa_origem_id' => 'required|exists:caixas,id',
                'valor_pgto' => 'required|numeric',
                // Adicione outras regras de validação conforme necessário
            ]);

            $invoice = Invoice::findOrFail($request->input('invoice_id'));

            //Data retira Mês e Ano para buscar o fechamando do caixa de DESTINO
            $data = \Carbon\Carbon::createFromFormat('Y-m-d', $request->input('data_pagamento'));
            $ano = $data->year;
            $mes = $data->month;            
            $fechamento = FechamentoCaixa::where('caixa_id', $request->input('caixa_origem_id'))->where('mes', $mes)->where('ano', $ano)->firstOrFail();

            //Necessário criar um FLUXO_CAIXA (entrada de valores);
            $fluxo = FluxoCaixa::create([
                'data' => $request->input('data_pagamento'),
                'descricao' => 'Pgto '.$invoice->cliente->user->name.' de '.$request->input('valor').' U$ INVOICE ('.$request->input('invoice_id').')',
                'tipo' => 'entrada',
                'caixa_origem_id' => $request->input('caixa_origem_id'),
                'valor_origem' => $request->input('valor_pgto'),
                'fechamento_caixa_id' => $fechamento->id,
                // Adicione outros campos conforme necessário
            ]);

            $fechamento->atualizaSaldo($request->input('valor_pgto'));

            //Cria o Pagamento
            $pagamento = Pagamento::create([
                'invoice_id' => $request->input('invoice_id'),
                'data_pagamento' => $request->input('data_pagamento'),
                'valor' => $request->input('valor'),
                'observacoes' => $request->input('observacoes'),
                'fluxo_caixa_id' => $fluxo->id,
                // Adicione outros campos conforme necessário
            ]);

            // Exibir toastr de sucesso
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

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Invoice;
use App\Models\InvoicePacote;
use App\Models\FaturaCarga;
use App\Models\Carga;
use App\Models\Pacote;
use App\Models\Caixa;
use App\Models\Pagamento;
use App\Models\Cliente;
use App\Models\Credito;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $all_items = Carga::all();
        $all_items = Cliente::all()->filter(function ($cliente) {
            return $cliente->invoicesPendentes()->isNotEmpty();
        });

        $totalPendente = $all_items->sum(function($cliente) {
            return $cliente->invoices->sum(function($invoice) {
                return $invoice->valor_pendente();
            });
        });

        return view('admin.invoice.index', compact('all_items', 'totalPendente'));
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            // Buscar o shipper pelo ID
            $invoice = Invoice::findOrFail($id);

            $pacotesAssociadosFatura = $invoice->invoice_pacotes()->pluck('pacote_id')->toArray();

            // Obter todos os pacotes que não estão em InvoicePacotes
            $all_pacotes = Pacote::whereNotIn('id', $pacotesAssociadosFatura)
                            ->where('carga_id', $invoice->fatura_carga->carga_id)
                            ->where('cliente_id', $invoice->cliente_id)
                            ->get();

            // $all_pagamentos = Pagamento::where('invoice_id', $id)->get();

            $all_caixas = Caixa::all();
            // Retornar a view com os detalhes do shipper
            return view('admin.invoice.show', compact('invoice', 'all_pacotes', 'all_caixas'));
        } catch (\Exception $e) {
            // Exibir uma mensagem de erro ou redirecionar para uma página de erro
            return redirect()->back()->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao exibir os detalhes da Carga: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
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
                // 'numero' => 'required',
                'fatura_carga_id' => 'required|exists:fatura_cargas,id',
                'cliente_id' => 'required|exists:clientes,id',
                // Adicione outras regras de validação conforme necessário
            ]);

            // Criação de um nova Invoice no banco de dados
            $invoice = Invoice::create([
                'data' => $request->input('data'),
                // 'numero' => $request->input('numero'),
                'fatura_carga_id' => $request->input('fatura_carga_id'),
                'cliente_id' => $request->input('cliente_id'),
                // Adicione outros campos conforme necessário
            ]);

            // $invoice_cliente = Invoice::where('fatura_carga_id', $request->input('fatura_carga_id'))
            //                     ->where('cliente_id', $request->input('cliente_id'))
            //                     ->first();

            //Já existe uma invoice nessa fatura e com o mesmo código de cliente
            // if ($invoice_cliente->count() > 0) {
            //     // IDs dos pacotes associados à fatura (invoice)
            //     $pacotesAssociadosFatura = $invoice_cliente->invoice_pacotes()->pluck('pacote_id')->toArray();
            //     // Consulta para obter pacotes do cliente, excluindo os pacotes associados à fatura
            //     $pacotesCliente = $invoice->fatura_carga->carga->pacotes()
            //                     ->where('cliente_id', $request->input('cliente_id'))
            //                     ->whereNotIn('id', $pacotesAssociadosFatura)
            //                     ->get();
            // } else {
            //     $pacotesCliente = $invoice->fatura_carga->carga->pacotes()
            //                     ->where('cliente_id', $request->input('cliente_id'))
            //                     ->get();
            // }

            //Adicionar a criação de "invoicepacotes" para carga pacote marcado na carga
            // foreach ($pacotesCliente as $pacote) {
            //     $valor = $pacote->peso*$invoice->fatura_carga->servico->preco;
            //     InvoicePacote::create([
            //         'peso' => $pacote->peso,
            //         'invoice_id' => $invoice->id,
            //         'pacote_id' => $pacote->id,
            //         'valor' => $valor,
            //         // Adicione outros campos conforme necessário
            //     ]);
            // }

            // Exibir toastr de sucesso
            return redirect()->back()->with('toastr', [
                'type'    => 'success',
                'message' => 'Invoice criada com sucesso!',
                'title'   => 'Sucesso',
            ]);
        } catch (\Exception $e) {
            // Exibir toastr de Erro
            return redirect()->back()->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao criar a Invoice: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
    }

    public function update(Request $request, Invoice $invoice)
    {
        try {
            // Validação dos dados do formulário
            // $request->validate([
            //     'contato' => 'string|max:255',
            //     // Adicione outras regras de validação conforme necessário
            // ]);

            // // Atualizar os dados do Shipper
            // $invoice->update([
            //     'contato' => $request->input('contato'),
            //     // Adicione outros campos conforme necessário
            // ]);

            // Exibir toastr de sucesso
            return redirect()->back()->with('toastr', [
                'type'    => 'success',
                'message' => 'Embarcador atualizado com sucesso!',
                'title'   => 'Sucesso',
            ]);
        } catch (\Exception $e) {
            // Exibir toastr de Erro
            return redirect()->back()->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao atualizar o Embarcador: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    // public function destroy(Pacote $pacote)
    public function destroy($id)
    {
        try {
            $invoice = Invoice::find($id);
            // Excluir o Shipper do banco de dados
            $invoice->delete();

            // Redirecionar após a exclusão bem-sucedida
            return redirect()->route('faturacargas.show', ['faturacarga' => $invoice->fatura_carga_id])->with('toastr', [
                'type'    => 'success',
                'message' => 'Invoice excluída com sucesso!',
                'title'   => 'Sucesso',
            ]);
        } catch (\Exception $e) {
            // Exibir toastr de erro se ocorrer uma exceção
            return redirect()->back()->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao excluir a Invoice: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public static function criarInvoices(FaturaCarga $faturacarga)
    {
        try {
            //Encontrar os clientes que pertencem a carga
            $carga = Carga::with('clientes')->find($faturacarga->carga_id);
            $clientes = $carga->clientes->unique(); // Para obter uma coleção única de clientes

            // return "OKAY Aqui";
            foreach ($clientes as $cliente) {
                // Cria uma nova invoice para cada cliente
                $invoice = Invoice::create([
                        'data' => $faturacarga->created_at,
                        'fatura_carga_id' => $faturacarga->id,
                        'cliente_id' => $cliente->id,
                        // Adicione outros campos conforme necessário
                ]);

                // // Filtrar os pacotes pertencentes ao cliente atual
                $pacotesCliente = $carga->pacotes()->where('cliente_id', $cliente->id)->get();

                //Adicionar a criação de "invoicepacotes" para carga pacote marcado na carga
                foreach ($pacotesCliente as $pacote) {
                    $valor = $pacote->peso*$faturacarga->servico->preco;
                    InvoicePacote::create([
                        'peso' => $pacote->peso,
                        'invoice_id' => $invoice->id,
                        'pacote_id' => $pacote->id,
                        'valor' => $valor,
                        // Adicione outros campos conforme necessário
                    ]);
                }
            }

            return true;
        } catch (\Exception $e) {
            // Exibir toastr de Erro
            return $e;
        }
    }

    public function distribuirPagamento(Cliente $cliente, Pagamento $pagamento, Invoice $invoice)
    {
        //Calcula o valor do pagamento para poder distribuir entre as invoices
        $valorRestante = $pagamento->valor;

        // Calcula o valor em ABERTO da Invoice Recebida
        $saldoAberto = $invoice->valor_total() - $invoice->valor_pago();

        // Verificar se o valor do pagamento pode pagar totalmente a invoice atual
        if ($valorRestante <= $saldoAberto) {
            $invoice->pagamentos()->attach($pagamento->id, ['valor_recebido' => $valorRestante]);
        // Se o valor do pagamento for maior que o da invoice atual distribui entre invoices!
        } else {
            //Paga o Saldo em ABERTO da invoice ATUAL
            $invoice->pagamentos()->attach($pagamento->id, ['valor_recebido' => $saldoAberto]);
            //Verifica quanto sobra para repartir entre outras invoices
            $valorRestante -= $saldoAberto;

            // Filtra TODAS as invoices que tem valores em aberto
            $invoicesEmAberto = $cliente->invoices()->get()->filter(function ($invoice) {
                return $invoice->valor_pago() < $invoice->valor_total();
            });

            //Distribuir o valorRestante entre os valores das invoices
            foreach ($invoicesEmAberto as $aberto) {
                // Calcula o valor em ABERTO da Invoice da Iteração
                $saldoAberto = $aberto->valor_total() - $aberto->valor_pago();

                // Verificar se o valor restante pode pagar totalmente a invoice atual
                if ($valorRestante >= $saldoAberto) {
                    // O valor pago é suficiente para pagar totalmente esta invoice
                    $valorRestante -= $saldoAberto; // Atualiza o valor que pode ser pago para proxima invoice
                    // Atualiza a coluna da invoice com o pagamento
                    // Registrar o pagamento para esta invoice
                    $aberto->pagamentos()->attach($pagamento->id, ['valor_recebido' => $saldoAberto]);

                } else {
                    //O valor pago vai ser menor que a invoice Iterada
                    if ($valorRestante > 0) {
                        // Atualiza a coluna da invoice com o pagamento do valor RESTANTE (o que sobrou dos pagamentos)
                        // Registrar o pagamento para esta invoice
                        $aberto->pagamentos()->attach($pagamento->id, ['valor_recebido' => $valorRestante]);
                        $valorRestante -= $valorRestante; // ZERO
                        break;
                    } else {
                        // Não existem mais invoices para serem feito pagamento (quebra o foreach)
                        break;
                    }
                }
            }
        }

        // Não tem mais invoices para descontar
        if ($valorRestante > 0) {
            //CRIA um Crédito
            Credito::create([
                'valor_credito' => $valorRestante,
                'pagamento_id' => $pagamento->id,
                'cliente_id' => $invoice->cliente->id,
                // Adicione outros campos conforme necessário
            ]);
        }

        // Retorna o valor restante
        return $valorRestante;
    }

    /**
     * Display a listing of the resource.
     */
    public function indexcli($id)
    {
        try {
            $cliente = Cliente::findOrFail($id);
            session(['previous_url' => url()->previous()]);

            return view('admin.invoice.cliente', compact('cliente'));
        } catch (\Exception $e) {
            // Exibir uma mensagem de erro ou redirecionar para uma página de erro
            return redirect()->back()->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao exibir os detalhes do Cliente: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function cliCargaIndex()
    {
        // Obtenha o usuário autenticado
        $user = Auth::user();
        $all_items = Invoice::where('cliente_id', $user->cliente->id)->get();
        return view('client.carga.index', compact('all_items'));
    }

    /**
     * Display a listing of the resource.
     */
    public function cliCargaShow($id)
    {
        // Buscar o invoice pelo ID
        $invoice = Invoice::findOrFail($id);
        // Obtenha o usuário autenticado
        $user = Auth::user();

        if ($invoice->cliente_id == $user->cliente->id) {
            return view('client.carga.show', compact('invoice'));
        }
    }
}

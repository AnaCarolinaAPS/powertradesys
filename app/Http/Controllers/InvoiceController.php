<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Invoice;
use App\Models\InvoicePacote;
use App\Models\FaturaCarga;
use App\Models\Carga;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $all_items = Carga::all();
        // $all_items = Carga::select('cargas.*', DB::raw('COALESCE(SUM(pacotes.qtd), 0) as quantidade_de_pacotes'))
        //             ->leftJoin('pacotes', 'cargas.id', '=', 'pacotes.carga_id')
        //             ->whereNotNull('cargas.data_recebida') // Filtra onde data_recebida não é NULL
        //             ->groupBy('cargas.id', 'cargas.data_enviada', 'cargas.data_recebida', 'cargas.observacoes', 'cargas.created_at', 'cargas.updated_at', 'cargas.despachante_id', 'cargas.embarcador_id')
        //             ->get();

        // $all_items = Carga::select(
        //                 'cargas.*',
        //                 DB::raw('COALESCE(SUM(pacotes.qtd), 0) as quantidade_de_pacotes'),
        //                 DB::raw('COUNT(DISTINCT invoices.id) as qtd_invoices'),
        //                 DB::raw('COUNT(DISTINCT clientes.id) as qtd_clientes')
        //             )
        //             ->leftJoin('pacotes', 'cargas.id', '=', 'pacotes.carga_id')
        //             ->leftJoin('invoices', 'cargas.id', '=', 'invoices.carga_id')
        //             ->leftJoin('clientes', 'pacotes.cliente_id', '=', 'clientes.id')
        //             ->whereNotNull('cargas.data_recebida')
        //             ->groupBy(
        //                 'cargas.id',
        //                 'cargas.data_enviada',
        //                 'cargas.data_recebida',
        //                 'cargas.observacoes',
        //                 'cargas.created_at',
        //                 'cargas.updated_at',
        //                 'cargas.despachante_id',
        //                 'cargas.embarcador_id'
        //             )
        //             ->get();
        // $all_despachantes = Despachante::all();
        // $all_embarcadores = Embarcador::all();

        $all_items = Carga::all();
        return view('admin.invoice.index', compact('all_items'));
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            // Buscar o shipper pelo ID
            $invoice = Invoice::findOrFail($id);
            $all_invoices_pacotes = InvoicePacote::where('invoice_id', $id)->get();
            $resumo = InvoicePacote::where('invoice_id', $id)
                    // ->selectRaw('SUM(peso) as soma_peso, SUM(valor) as soma_valor')
                    ->selectRaw('SUM(peso) as soma_peso')
                    ->first();
           
            // Retornar a view com os detalhes do shipper
            return view('admin.invoice.show', compact('invoice', 'all_invoices_pacotes', 'resumo'));
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
                'carga_id' => 'required|exists:cargas,id',
                'cliente_id' => 'required|exists:clientes,id',
                // Adicione outras regras de validação conforme necessário
            ]);

            // Criação de um novo Shipper no banco de dados
            $invoice = Invoice::create([
                'data' => $request->input('data'),
                // 'numero' => $request->input('numero'),
                'carga_id' => $request->input('carga_id'),
                'cliente_id' => $request->input('cliente_id'),
                // Adicione outros campos conforme necessário
            ]);            

            // Exibir toastr de sucesso
            return redirect()->route('invoices.items', ['invoice' => $invoice->id])->with('toastr', [
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
     * Store a newly created resource in storage.
     */
    public static function criarInvoices(FaturaCarga $faturacarga)
    {
        try {
            //Encontrar os clientes que pertencem a carga
            $carga = Carga::with('clientes')->find($faturacarga->carga_id);
            $clientes = $carga->clientes->unique(); // Para obter uma coleção única de clientes

            foreach ($clientes as $cliente) {
                // Cria uma nova invoice para cada cliente
                $invoice = Invoice::create([
                        'data' => $faturacarga->created_at,
                        'fatura_cargas_id' => $faturacarga->id,
                        'cliente_id' => $cliente->id,
                        // Adicione outros campos conforme necessário
                ]);

                // Filtrar os pacotes pertencentes ao cliente atual
                $pacotesCliente = $carga->pacotes()->where('cliente_id', $cliente->id)->get();

                //Adicionar a criação de "invoicepacotes" para carga pacote marcado na carga
                foreach ($pacotesCliente as $pacote) {
                    InvoicePacote::create([
                        'peso' => $pacote->peso,
                        'invoice_id' => $invoice->id,
                        'pacote_id' => $pacote->id,
                        // Adicione outros campos conforme necessário
                    ]);
                }
            }

            return true;
        } catch (\Exception $e) {
            // Exibir toastr de Erro
            return false;
        }
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FaturaCarga;
use App\Models\Carga;
use App\Models\Cliente;
use App\Models\Invoice;
use Illuminate\Support\Facades\DB;

class FaturaCargaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $all_items = FaturaCarga::all();
        $all_cargas = Carga::whereNotNull('cargas.data_recebida')
                    ->whereNull('cargas.fatura_carga_id') // Verifica se não há FaturaCarga associada à carga
                    ->get();
        return view('admin.faturacarga.index', compact('all_items', 'all_cargas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validação dos dados do formulário
            $request->validate([
                'numero' => 'required',
                'carga_id' => 'required|exists:cargas,id',
                // Adicione outras regras de validação conforme necessário
            ]);

            // Criação de uma nova Fatura Carga no banco de dados
            $faturacarga = FaturaCarga::create([
                'numero' => $request->input('numero'),
                'carga_id' => $request->input('carga_id'),
                // Adicione outros campos conforme necessário
            ]);

            // Obtenha a carga associada à nova fatura usando o ID fornecido na requisição
            $carga = Carga::findOrFail($request->input('carga_id'));
            try {
                $carga->update(['fatura_carga_id' => $faturacarga->id]);
            } catch (\Throwable $th) {
                // Exibir toastr de Erro
                return redirect()->route('faturacargas.index')->with('toastr', [
                    'type'    => 'error',
                    'message' => 'Ocorreu um erro ao atualizar a Carga: <br>'. $e->getMessage(),
                    'title'   => 'Erro',
                ]);
            }

            // Chamar método de outra classe para criar as Invoices e InvoicesPacotes
            $invoices = InvoiceController::criarInvoices($faturacarga);

            if ($invoices == false) {
                // Exibir toastr de Erro
                return redirect()->route('faturacargas.index')->with('toastr', [
                    'type'    => 'error',
                    'message' => 'Ocorreu um erro ao criar as INVOICES da Fatura da Carga<br>',
                    'title'   => 'Erro',
                ]);
            }

            // Exibir toastr de sucesso
            return redirect()->route('faturacargas.show', ['faturacarga' => $faturacarga->id])->with('toastr', [
                'type'    => 'success',
                'message' => 'Fatura da Carga criada com sucesso!',
                'title'   => 'Sucesso',
            ]);
        } catch (\Exception $e) {
            // Exibir toastr de Erro
            return redirect()->route('faturacargas.index')->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao criar a Fatura da Carga: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            // Buscar o shipper pelo ID
            $faturacarga = FaturaCarga::findOrFail($id);
            $all_clientes = Cliente::all();

            $all_invoices = Invoice::leftJoin('invoice_pacotes', 'invoices.id', '=', 'invoice_pacotes.invoice_id')
                            ->leftJoin('pacotes', 'invoice_pacotes.pacote_id', '=', 'pacotes.id')
                            ->select(
                                'invoices.*',
                                DB::raw('SUM(invoice_pacotes.peso) as invoice_pacotes_sum_peso'),
                                DB::raw('SUM(pacotes.peso) as pacotes_sum_peso')
                            )
                            ->where('invoices.fatura_cargas_id', $id)
                            ->whereColumn('invoices.cliente_id', 'pacotes.cliente_id')
                            ->groupBy('invoices.id','cliente_id', 'data', 'fatura_cargas_id', 'created_at', 'updated_at') // Agrupa por invoice para evitar mais de uma linha por invoice_id
                            ->get();

            // Retornar a view com os detalhes do shipper
            return view('admin.faturacarga.show', compact('faturacarga', 'all_clientes', 'all_invoices'));
        } catch (\Exception $e) {
            // Exibir uma mensagem de erro ou redirecionar para uma página de erro
            return redirect()->route('faturacargas.index')->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao exibir os detalhes do Fatura da Carga: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

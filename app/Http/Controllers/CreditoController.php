<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Credito;
use App\Models\Invoice;

class CreditoController extends Controller
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $credito = Credito::find($id);
        return response()->json($credito);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function converter(Request $request)
    {
        try {
            // Validação dos dados do formulário
            $request->validate([
                'invoice_id' => 'required|exists:invoices,id',
                // Adicione outras regras de validação conforme necessário
            ]);

            $invoice = Invoice::findOrFail($request->input('invoice_id'));
            $creditos = $invoice->cliente->creditos;

            foreach ($creditos as $credito) {           
                $invoice->pagamentos()->attach($credito->pagamento->id, ['valor_recebido' => $credito->valor_credito]);
            }

            

            return redirect()->back()->with('toastr', [
                'type'    => 'success',
                'message' => 'Crédito convertido em Pagamento com sucesso!',
                'title'   => 'Sucesso',
            ]);
        } catch (\Exception $e) {
            // Exibir uma mensagem de erro ou redirecionar para uma página de erro
            return redirect()->back()->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao converter os Créditos em Pagamento: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
    }
}

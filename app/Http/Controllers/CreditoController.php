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

            //Invoice em que foi pedida a conversão
            $invoice = Invoice::findOrFail($request->input('invoice_id'));
            //Todos os créditos disponíveis
            $creditos = $invoice->cliente->creditos;

            //Adiciona TODOS os créditos para a invoice atual
            foreach ($creditos as $credito) {  
                //Adiciona o pagamento na invoice (baseado no valor do crédito)
                $invoice->pagamentos()->attach($credito->pagamento->id, ['valor_recebido' => $credito->valor_credito]);
                //exclui o crédito
                $credito->delete();
                // Se existirem pagamentos numa invoice (que geraram crédito) mas que não devem mais pertencer a essa invoice (valor_recebido = 0 U$) "exclui"
                $credito->pagamento->invoices()->wherePivot('valor_recebido', 0)->detach(); 
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

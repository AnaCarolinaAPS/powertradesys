<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InvoicePacote;
use App\Models\Invoice;
use App\Models\Pacote;

class InvoicePacoteController extends Controller
{
    public function show($id)
    {
        // $invoicepacote = InvoicePacote::find($id);
        $invoicepacote = InvoicePacote::leftJoin('pacotes', 'pacotes.id', '=', 'invoice_pacotes.pacote_id')
                        ->where('invoice_pacotes.id', $id)
                        ->select('invoice_pacotes.*', 'pacotes.rastreio', 'pacotes.peso AS peso_origem')
                        ->first();
        return response()->json($invoicepacote);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, InvoicePacote $pacote)
    {
        try {
            // Validação dos dados do formulário
            $request->validate([
                // 'rastreio' => 'required|string|max:255',
                'peso' => 'required|numeric',
                'valor' => 'required|numeric',
                // Adicione outras regras de validação conforme necessário
            ]);

            $pacote = InvoicePacote::findOrFail($request->input('id'));
            // Atualizar os dados do Pacote

            $pacote->update([
                'rastreio' => $request->input('rastreio'),
                'peso' => $request->input('peso'),
                'valor' => $request->input('valor'),
                // Adicione outros campos conforme necessário
            ]);

            // Exibir toastr de sucesso
            return redirect()->back()->with('toastr', [
                'type'    => 'success',
                'message' => 'Pacote atualizado com sucesso!',
                'title'   => 'Sucesso',
            ]);
        } catch (\Exception $e) {
            // Exibir toastr de Erro
            return redirect()->back()->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao atualizar o Pacote: <br>'. $e->getMessage(),
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
            $pacote = InvoicePacote::find($id);
            // Excluir o Shipper do banco de dados
            $pacote->delete();

            // Redirecionar após a exclusão bem-sucedida
            return redirect()->back()->with('toastr', [
                'type'    => 'success',
                'message' => 'Pacote excluído com sucesso!',
                'title'   => 'Sucesso',
            ]);
        } catch (\Exception $e) {
            // Exibir toastr de erro se ocorrer uma exceção
            return redirect()->back()->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao excluir o Pacote: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request){
        try {
            // IDs dos pacotes selecionados
            $pacotesSelecionados = $request->input('pacote_id');
            $invoice = Invoice::findOrFail($request->input('invoice_id'));

            // Validação dos dados do formulário
            $request->validate([
                'invoice_id' => 'required|exists:invoices,id',
                // Adicione outras regras de validação conforme necessário
            ]);

            // Lógica para atualizar os pacotes com o código da carga
            foreach ($pacotesSelecionados as $pacoteId) {
                $pacote = Pacote::findOrFail($pacoteId);

                if ($pacote) {
                    //Adicionar a criação de "invoicepacotes" para carga pacote marcado na carga
                    // $valor = $pacote->peso*$invoice->fatura_carga->servico->preco;

                    InvoicePacote::create([
                        // 'peso' => $pacote->peso,
                        'peso' => 0,
                        'peso' => 0,
                        'invoice_id' => $invoice->id,
                        'pacote_id' => $pacote->id,
                        // 'valor' => $valor,
                        'valor' => 0,
                        // Adicione outros campos conforme necessário
                    ]);
                }
            }
            // Redirecionar após a exclusão bem-sucedida
            return redirect()->back()->with('toastr', [
                'type'    => 'success',
                'message' => 'Pacotes adicionados com sucesso!',
                'title'   => 'Sucesso',
            ]);
        } catch (\Exception $e) {
            // Exibir toastr de erro se ocorrer uma exceção
            return redirect()->back()->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao adicionar os Pacotes: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
    }
}

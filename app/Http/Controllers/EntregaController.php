<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Entrega;
use App\Models\Cliente;
use App\Models\EntregaPacotes;
use App\Models\Freteiro;
use App\Models\Pacote;
use Illuminate\Support\Facades\DB;

class EntregaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $all_items = Entrega::all();
        // $all_clientes = Cliente::all();
        $all_clientes = Cliente::whereHas('pacotes', function ($query) {
            $query->where('retirado', 0)->whereHas('invoice_pacote', function ($query) {
                $query->where('peso', '>', 0);
            });
        })->get();
        $all_freteiros = Freteiro::all();
        return view('admin.entrega.index', compact('all_items', 'all_clientes', 'all_freteiros'));
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
                'hora' => 'required',
                'responsavel' => 'required|string',
                'cliente_id' => 'required|exists:clientes,id',
                'freteiro_id' => 'required|exists:freteiros,id',
                // Adicione outras regras de validação conforme necessário
            ]);

            // Criação de um novo Shipper no banco de dados
            $entrega = Entrega::create([
                'data' => $request->input('data'),
                'hora' => $request->input('hora'),
                'responsavel' => $request->input('responsavel'),
                'cliente_id' => $request->input('cliente_id'),
                'freteiro_id' => $request->input('freteiro_id'),
                // Adicione outros campos conforme necessário
            ]);

            // Exibir toastr de sucesso
            return redirect()->route('entregas.show', ['entrega' => $entrega->id])->with('toastr', [
                'type'    => 'success',
                'message' => 'Entrega criada com sucesso!',
                'title'   => 'Sucesso',
            ]);
        } catch (\Exception $e) {
            // Exibir toastr de Erro
            return redirect()->route('entregas.index')->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao criar a Entrega: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $entrega = Entrega::find($id);
        $entregaId = $entrega->id;
        $all_clientes = Cliente::all();
        // $all_clientes = Cliente::whereHas('pacotes', function ($query) {
        //     $query->where('retirado', 0);
        // })->get();
        $all_freteiros = Freteiro::all();
        // Obter os pacotes do cliente que não foram entregues
        $pacotesNaoEntregues = Pacote::with('warehouse')->where('cliente_id', $entrega->cliente_id)
            ->where('retirado', false)
            ->whereHas('invoice_pacote', function ($query) {
                $query->where('peso', '>', 0);
            })
            ->get();

        $totais = DB::table('entrega_pacotes')
            ->where('entrega_id', $id)
            ->selectRaw('SUM(peso) as total_peso, SUM(qtd) as total_qtd')
            ->first();
        return view('admin.entrega.show', compact('entrega', 'all_clientes', 'all_freteiros', 'pacotesNaoEntregues', 'totais'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Entrega $entrega)
    {
        try {
            $request->validate([
                'data' => 'required|date',
                'hora' => 'required',
                'responsavel' => 'required|string',
                'freteiro_id' => 'required|exists:freteiros,id',
                // Adicione outras regras de validação conforme necessário
            ]);

            // Atualizar os dados
            $entrega->update([
                'data' => $request->input('data'),
                'hora' => $request->input('hora'),
                'responsavel' => $request->input('responsavel'),
                'freteiro_id' => $request->input('freteiro_id'),
                // Adicione outros campos conforme necessário
            ]);

            // Exibir toastr de sucesso
            return redirect()->back()->with('toastr', [
                'type'    => 'success',
                'message' => 'Entrega atualizada com sucesso!',
                'title'   => 'Sucesso',
            ]);
        } catch (\Exception $e) {
            // Exibir toastr de Erro
            return redirect()->back()->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao atualizar a Entrega: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $entrega = Entrega::find($id);
            //Adicionar Lógica para que o freteiro não possa ser excluído caso tenha Saídas dos Pacotes no seu nome

            //Todos os pacotes relacionados com a Entrega
            $entrega_pacotes = $entrega->entrega_pacotes;

            foreach ($entrega_pacotes as $entrega_pacote){
                //Atualiza para que não tenham sido retirados
                $pacote = Pacote::find($entrega_pacote->pacote_id);
                $pacote->update([
                    'retirado' => false,
                    // Adicione outros campos conforme necessário
                ]);
            }

            // Excluir o Freteiro do banco de dados
            $entrega->delete();

            // Redirecionar após a exclusão bem-sucedida
            return redirect()->route('entregas.index')->with('toastr', [
                'type'    => 'success',
                'message' => 'Entrega excluída com sucesso!',
                'title'   => 'Sucesso',
            ]);
        } catch (\Exception $e) {
            // Exibir toastr de erro se ocorrer uma exceção
            return redirect()->back()->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao excluir a Entrega: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Entrega;
use App\Models\Cliente;
use App\Models\EntregaPacotes;
use App\Models\Freteiro;
use App\Models\Pacote;

class EntregaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $all_items = Entrega::all();
        $all_clientes = Cliente::all();
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
        $all_freteiros = Freteiro::all();
        // Obter os pacotes do cliente que não foram entregues
        $pacotesNaoEntregues = Pacote::where('cliente_id', $entrega->cliente_id)
            ->whereNotIn('id', function ($query) use ($entregaId) {
                $query->select('pacote_id')
                      ->from('entrega_pacotes')
                      ->where('entrega_id', $entregaId);
            })
            ->get();
        return view('admin.entrega.show', compact('entrega', 'all_clientes', 'all_freteiros', 'pacotesNaoEntregues'));
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

            // Excluir o Freteiro do banco de dados
            $entrega->delete();

            // Redirecionar após a exclusão bem-sucedida
            return redirect()->back()->with('toastr', [
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

<?php

namespace App\Http\Controllers;

use App\Models\PacotesPendentes;
use App\Models\Cliente;
use App\Models\Pacote;
use Illuminate\Http\Request;

class PacotesPendentesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $all_items = PacotesPendentes::all();
        $all_clientes = Cliente::all();
        return view('admin.pacotespendentes.index', compact('all_items', 'all_clientes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validação dos dados do formulário
            $request->validate([
                'rastreio' => 'required|string|max:255',
                'data_pedido' => 'required|date',
                'cliente_id' => 'nullable|exists:clientes,id',
                // Adicione outras regras de validação conforme necessário
            ]);

            // Criação de um novo item no banco de dados
            $pendente = PacotesPendentes::create([
                'rastreio' => $request->input('rastreio'),
                'data_pedido' => $request->input('data_pedido'),
                'cliente_id' => $request->input('cliente_id'),
                // Adicione outros campos conforme necessário
            ]);

            $pacote = Pacote::where('rastreio', 'like', '%' .$request->input('rastreio'. '%'))->first();

            //se existe pacote, atualiza a pendencia para o status "em sistema"
            if ($pacote) {
                $pendente->update([
                    'status' => 'em sistema',
                    // Adicione outros campos conforme necessário
                ]);
            }

            // Exibir toastr de sucesso
            return redirect()->back()->with('toastr', [
                'type'    => 'success',
                'message' => 'Pacote Pendente criado com sucesso!',
                'title'   => 'Sucesso',
            ]);

        } catch (\Exception $e) {
            // Exibir toastr de Erro
            return redirect()->back()->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao criar o Pacote Pendente: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $pacote = PacotesPendentes::find($id);
        return response()->json($pacote);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PacotesPendentes $pacotesPendentes)
    {
        try {
            // Validação dos dados do formulário
            $request->validate([
                'rastreio' => 'required|string|max:255',
                'data_pedido' => 'required|date',
                'cliente_id' => 'nullable|exists:clientes,id',
                'data_recebido' => 'nullable|date',
                'status' => 'required|in:aguardando,solicitado,buscando,em sistema,encontrado',
                // Adicione outras regras de validação conforme necessário
            ]);

            $pacote = PacotesPendentes::find($request->input('id'));
            
            $pacote->update([
                'rastreio' => $request->input('rastreio'),
                'data_recebido' => $request->input('data_recebido'),
                'status' => $request->input('status'),
                // Adicione outros campos conforme necessário
            ]);

            // Exibir toastr de sucesso
            return redirect()->back()->with('toastr', [
                'type'    => 'success',
                'message' => 'Pendencia atualizada com sucesso!',
                'title'   => 'Sucesso',
            ]);
        } catch (\Exception $e) {
            // Exibir toastr de Erro
            return redirect()->back()->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao atualizar a Pendencia: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PacotesPendentes $pacotesPendentes)
    {
        //
    }
}

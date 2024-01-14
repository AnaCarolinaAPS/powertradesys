<?php

namespace App\Http\Controllers;

use App\Models\EntregaPacotes;
use Illuminate\Http\Request;

class EntregaPacotesController extends Controller
{

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $entregapacotes = EntregaPacotes::find($id);
        return response()->json($entregapacotes);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validação dos dados do formulário
            $request->validate([
                // 'qtd' => 'nullable|numeric',
                // 'peso' => 'nullable|numeric',
                'pacote_id' => 'required|exists:pacotes,id',
                'entrega_id' => 'required|exists:entregas,id',
                // Adicione outras regras de validação conforme necessário
            ]);

            // Criação de um novo Shipper no banco de dados
            EntregaPacotes::create([
                'pacote_id' => $request->input('pacote_id'),
                'entrega_id' => $request->input('entrega_id'),
                // Adicione outros campos conforme necessário
            ]);

            // Exibir toastr de sucesso
            return redirect()->back()->with('toastr', [
                'type'    => 'success',
                'message' => 'Pacote criado com sucesso!',
                'title'   => 'Sucesso',
            ]);
        } catch (\Exception $e) {
            // Exibir toastr de Erro
            return redirect()->back()->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao criar o Pacote: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
    }
}

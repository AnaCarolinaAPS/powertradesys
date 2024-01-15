<?php

namespace App\Http\Controllers;

use App\Models\EntregaPacote;
use App\Models\Pacote;
use Illuminate\Http\Request;

class EntregaPacoteController extends Controller
{

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $entregapacotes = EntregaPacote::find($id);
        return response()->json($entregapacotes);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // IDs dos pacotes selecionados
            $pacotesSelecionados = $request->input('pacote_id');

            // Lógica para atualizar os pacotes com o código da carga
            foreach ($pacotesSelecionados as $pacoteId) {
                $pacote = Pacote::findOrFail($pacoteId);
                if ($pacote) {
                    // Validação dos dados do formulário
                    $request->validate([
                        // 'qtd' => 'nullable|numeric',
                        // 'peso' => 'nullable|numeric',
                        'entrega_id' => 'required|exists:entregas,id',
                        // Adicione outras regras de validação conforme necessário
                    ]);
                    // Criação de um novo Shipper no banco de dados
                    EntregaPacote::create([
                        'qtd' => $pacote->qtd,
                        'peso' => $pacote->peso,
                        'pacote_id' => $pacote->id,
                        'entrega_id' => $request->input('entrega_id'),
                        // Adicione outros campos conforme necessário
                    ]);
                }
            }

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

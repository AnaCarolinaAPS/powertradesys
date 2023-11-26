<?php

namespace App\Http\Controllers;

use App\Models\ServicosFornecedor;
use Illuminate\Http\Request;

class ServicosFornecedorController extends Controller
{
    //

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validação dos dados do formulário
            $request->validate([
                'descricao' => 'required|string|max:255',
                'data_inicio' => 'required|date',
                'data_fim' => 'date',
                'fornecedor_id' => 'required|exists:fornecedors,id',
                // Adicione outras regras de validação conforme necessário
            ]);

            // Criação de um novo Shipper no banco de dados
            ServicosFornecedor::create([
                'descricao' => $request->input('descricao'),
                'data_inicio' => $request->input('data_inicio'),
                'data_fim' => $request->input('data_fim'),
                'fornecedor_id' => $request->input('fornecedor_id'),
                // Adicione outros campos conforme necessário
            ]);

            // Exibir toastr de sucesso
            return redirect()->back()->with('toastr', [
                'type'    => 'success',
                'message' => 'Serviço criado com sucesso!',
                'title'   => 'Sucesso',
            ]);
        } catch (\Exception $e) {
            // Exibir toastr de Erro
            return redirect()->back()->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao criar o Serviço: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
    }
}

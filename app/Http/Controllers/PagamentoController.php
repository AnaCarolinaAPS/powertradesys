<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagamentoController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validação dos dados do formulário
            $request->validate([
                'invoice_id' => 'nullable|exists:invoices,id',
                'data_pagamento' => 'required|date',
                'valor' => 'required|numeric',
                'observacoes' => 'string',
                // Adicione outras regras de validação conforme necessário
            ]);

            //Necessário criar um FLUXO_CAIXA (entrada de valores);
            

            // Criação de um novo Freteiro no banco de dados
            // Freteiro::create([
            //     'nome' => $request->input('nome'),
            //     'contato' => $request->input('contato'),
            //     // Adicione outros campos conforme necessário
            // ]);

            // Exibir toastr de sucesso
            return redirect()->back()->with('toastr', [
                'type'    => 'success',
                'message' => 'Pagamento criado com sucesso!',
                'title'   => 'Sucesso',
            ]);
        } catch (\Exception $e) {
            // Exibir toastr de Erro
            return redirect()->back()->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao criar o Freteiro: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
    }
}

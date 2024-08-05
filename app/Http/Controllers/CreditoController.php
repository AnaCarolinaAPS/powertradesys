<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
        try {
            // Validação dos dados do formulário
            $request->validate([
                'nome' => 'required|string|max:255',
                'tipo' => 'required|in:categoria,subcategoria',
                'observacoes' => 'nullable|string',
                // Adicione outras regras de validação conforme necessário
            ]);

            Credito::create([
                'nome' => $request->input('nome'),
                'tipo' => $request->input('tipo'),
                'observacoes' => $request->input('observacoes'),
                // Adicione outros campos conforme necessário
            ]);

            // Exibir toastr de sucesso
            return redirect()->route('categorias.index')->with('toastr', [
                'type'    => 'success',
                'message' => 'Categoria criada com sucesso!',
                'title'   => 'Sucesso',
            ]);
        } catch (\Exception $e) {
            // Exibir toastr de Erro
            return redirect()->back()->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao criar um Crédito: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
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
}

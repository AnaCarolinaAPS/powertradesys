<?php

namespace App\Http\Controllers;

use App\Models\Compra;
use App\Models\Produto;
use Illuminate\Http\Request;

class CompraController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $all_items = Compra::all();
        return view('admin.compra.index', compact('all_items'));
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
                'fornecedor' => 'required|string|max:255',
                'numero_factura' => 'required|string|max:255',
                // Adicione outras regras de validação conforme necessário
            ]);

            // Criação de um novo Compra no banco de dados
            $compra = Compra::create([
                'data' => $request->input('data'),
                'fornecedor' => $request->input('fornecedor'),
                'numero_factura' => $request->input('numero_factura'),
                // Adicione outros campos conforme necessário
            ]);

            // Exibir toastr de sucesso
            return redirect()->route('compras.show', ['compra' => $compra->id])->with('toastr', [
                'type'    => 'success',
                'message' => 'Compra criada com sucesso!',
                'title'   => 'Sucesso',
            ]);
        } catch (\Exception $e) {
            // Exibir toastr de Erro
            return redirect()->back()->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao criar a Compra: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            // Buscar a Compra pelo ID
            $compra = Compra::findOrFail($id);
            $all_produtos = Produto::all();
            // Retornar a view com os detalhes do shipper
            return view('admin.compra.show', compact('compra', 'all_produtos'));
        } catch (\Exception $e) {
            // Exibir uma mensagem de erro ou redirecionar para uma página de erro
            return redirect()->back()->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao exibir os detalhes da Compra: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Compra $compra)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Compra $compra)
    {
        //
    }
}

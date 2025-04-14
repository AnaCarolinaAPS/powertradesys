<?php

namespace App\Http\Controllers;

use App\Models\ContasPagar;
use App\Models\Categoria;
use Illuminate\Http\Request;

class ContasPagarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $all_items = ContasPagar::all();
        $all_categorias = Categoria::where('tipo', 'categoria')
                            ->get();
        $all_subcategorias = Categoria::where('tipo', 'subcategoria')
                        ->get();
        return view('admin.contaspagar.index', compact('all_items', 'all_categorias', 'all_subcategorias'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            if ($request->input('descricao')) {
                // Validação dos dados do formulário
                $request->validate([
                    'descricao' => 'required|string|max:255',
                    'moeda' => 'required|in:U$,R$,G$,outros',
                    'valor' => 'required|numeric',
                    'data_vencimento' => 'required|date',
                    'categoria_id' => 'required_if:tipo,entrada,saida|exists:categorias,id',
                    'subcategoria_id' => 'required_if:tipo,entrada,saida|exists:categorias,id',
                    // Adicione outras regras de validação conforme necessário
                ]);

                // Criação de um novo registro no banco de dados
                $conta = ContasPagar::create([
                    'descricao' => $request->input('descricao'),
                    'moeda' => $request->input('moeda'),
                    'valor' => $request->input('valor'),
                    'data_vencimento' => $request->input('data_vencimento'),
                    'categoria_id' => $request->input('categoria_id'),
                    'subcategoria_id' => $request->input('subcategoria_id'),
                    // Adicione outros campos conforme necessário
                ]);
            }

            // Exibir toastr de sucesso
            return redirect()->route('contaspagar.index')->with('toastr', [
                'type'    => 'success',
                'message' => 'Conta a Pagar criada com sucesso!',
                'title'   => 'Sucesso',
            ]);
        } catch (\Exception $e) {
            // Exibir toastr de Erro
            return redirect()->route('contaspagar.index')->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao criar a Conta a Pagar: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $conta = ContasPagar::find($id);
        return response()->json($conta);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'descricao' => 'required|string|max:255',
                'valor' => 'required|numeric',
                // Adicione outras regras de validação conforme necessário
            ]);

            $contasPagar = ContasPagar::find($id);

            // Atualizar os dados
            $contasPagar->update([
                'descricao' => $request->input('descricao'),
                'valor' => $request->input('valor'),
                // Adicione outros campos conforme necessário
            ]);

            // Exibir toastr de sucesso
            return redirect()->back()->with('toastr', [
                'type'    => 'success',
                'message' => 'Conta a Pagar atualizada com sucesso!',
                'title'   => 'Sucesso',
            ]);
        } catch (\Exception $e) {
            // Exibir toastr de Erro
            return redirect()->back()->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao atualizar a Conta a Pagar: <br>'. $e->getMessage(),
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
            $conta = ContasPagar::find($id);

            // Excluir o registro do banco de dados
            $conta->delete();

            // Redirecionar após a exclusão bem-sucedida
            return redirect()->back()->with('toastr', [
                'type'    => 'success',
                'message' => 'Conta a Pagar excluída com sucesso!',
                'title'   => 'Sucesso',
            ]);
        } catch (\Exception $e) {
            // Exibir toastr de erro se ocorrer uma exceção
            return redirect()->back()->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao excluir a Conta a Pagar: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
    }
}

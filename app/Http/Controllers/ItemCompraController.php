<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produto;
use App\Models\ItemCompra;

class ItemCompraController extends Controller
{

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $item = ItemCompra::with('produto')->findOrFail($id);
        return response()->json($item);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validação dos dados do formulário
            $request->validate([
                'produto_id' => 'required|exists:produtos,id',
                'compra_id' => 'required|exists:compras,id',
                // Adicione outras regras de validação conforme necessário
            ]);

            // IDs dos pacotes selecionados
            $produtosSelecionados = $request->input('produto_id');

             // Lógica para atualizar os pacotes com o código da carga
             foreach ($produtosSelecionados as $produtosId) {
                $produto = Produto::findOrFail($produtosId);
                if ($produto) {                    
                    // Criação de um novo item no banco de dados
                    $produtoItem = ItemCompra::create([
                        'compra_id' => $request->input('compra_id'),
                        'produto_id' => $produtosId,
                        'quantidade' => 1,
                        'valor_unitario' => 0,
                        // Adicione outros campos conforme necessário
                    ]);
                }
            }

            // Exibir toastr de sucesso
            return redirect()->back()->with('toastr', [
                'type'    => 'success',
                'message' => 'Item da Compra criada com sucesso!',
                'title'   => 'Sucesso',
            ]);
        } catch (\Exception $e) {
            // Exibir toastr de Erro
            return redirect()->back()->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao criar o Item da Compra: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ItemCompra $item)
    {
        try {
            // Validação dos dados do formulário
            $request->validate([
                'quantidade' => 'required|numeric',
                'valor_unitario' => 'required|numeric',
                // Adicione outras regras de validação conforme necessário
            ]);

            $item = ItemCompra::findOrFail($request->input('id'));
            // Atualizar os dados           
            $item->update([
                'quantidade' => $request->input('quantidade'),
                'valor_unitario' => $request->input('valor_unitario'),
                // Adicione outros campos conforme necessário
            ]);

            // Exibir toastr de sucesso
            return redirect()->back()->with('toastr', [
                'type'    => 'success',
                'message' => 'Item da Compra atualizado com sucesso!',
                'title'   => 'Sucesso',
            ]);
        } catch (\Exception $e) {
            // Exibir toastr de Erro
            return redirect()->back()->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao atualizar o Item da Compra: <br>'. $e->getMessage(),
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
            $item = ItemCompra::findOrFail($id);
            // Excluir o Pacote do banco de dados
            $item->delete();

            // Redirecionar após a exclusão bem-sucedida
            return redirect()->back()->with('toastr', [
                'type'    => 'success',
                'message' => 'Item da Compra excluído com sucesso!',
                'title'   => 'Sucesso',
            ]);
        } catch (\Exception $e) {
            // Exibir toastr de erro se ocorrer uma exceção
            return redirect()->back()->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao excluir o Item da Compra: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
    }
}

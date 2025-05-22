<?php

namespace App\Http\Controllers;

use App\Models\ItemVenda;
use App\Models\Produto;
use Illuminate\Http\Request;

class ItemVendaController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validação dos dados do formulário
            $request->validate([
                'produto_id' => 'required|exists:produtos,id',
                'venda_id' => 'required|exists:vendas,id',
                // Adicione outras regras de validação conforme necessário
            ]);

            // IDs dos pacotes selecionados
            $produtosSelecionados = $request->input('produto_id');

             // Lógica para atualizar os pacotes com o código da carga
             foreach ($produtosSelecionados as $produtosId) {
                $produto = Produto::findOrFail($produtosId);
                if ($produto) {                    
                    // Criação de um novo item no banco de dados
                    $produtoItem = ItemVenda::create([
                        'venda_id' => $request->input('venda_id'),
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
                'message' => 'Item da Venda criada com sucesso!',
                'title'   => 'Sucesso',
            ]);
        } catch (\Exception $e) {
            // Exibir toastr de Erro
            return redirect()->back()->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao criar o Item da Venda: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $item = ItemVenda::with('produto')->findOrFail($id);
        return response()->json($item);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ItemVenda $itemVenda)
    {
        try {
            // Validação dos dados do formulário
            $request->validate([
                'quantidade' => 'required|numeric',
                'valor_unitario' => 'required|numeric',
                'valor_de_venta' => 'required|in:extentas,IVA5,IVA10',
                // Adicione outras regras de validação conforme necessário
            ]);            

            $item = ItemVenda::findOrFail($request->input('id'));

            $estoque_disponivel = $item->produto->quantidade_estoque() + $item->quantidade; // devolve a quantidade anterior ao estoque

            //O estoque é calculado baseado em compras e vendas, a venda não pode ser maior do que a quantidade em estoque.
            if ($request->input('quantidade') > $estoque_disponivel){
                // Exibir toastr de Erro
                return redirect()->back()->with('toastr', [
                    'type'    => 'warning',
                    'message' => 'Não existe essa quantidade em estoque do Produto<br>NÃO FOI POSSÍVEL ATUALIZAR!!<br>Quantidade Máxima: '.$item->produto->quantidade_estoque(),
                    'title'   => 'Atenção',
                ]);
            }

            // Atualizar os dados           
            $item->update([
                'quantidade' => $request->input('quantidade'),
                'valor_unitario' => $request->input('valor_unitario'),
                'valor_de_venta' => $request->input('valor_de_venta'),
                // Adicione outros campos conforme necessário
            ]);

            // Exibir toastr de sucesso
            return redirect()->back()->with('toastr', [
                'type'    => 'success',
                'message' => 'Item da Venda atualizado com sucesso!',
                'title'   => 'Sucesso',
            ]);
        } catch (\Exception $e) {
            // Exibir toastr de Erro
            return redirect()->back()->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao atualizar o Item da Venda: <br>'. $e->getMessage(),
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
            $item = ItemVenda::findOrFail($id);
            // Excluir o Pacote do banco de dados
            $item->delete();

            // Redirecionar após a exclusão bem-sucedida
            return redirect()->back()->with('toastr', [
                'type'    => 'success',
                'message' => 'Item da Venda excluído com sucesso!',
                'title'   => 'Sucesso',
            ]);
        } catch (\Exception $e) {
            // Exibir toastr de erro se ocorrer uma exceção
            return redirect()->back()->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao excluir o Item da Venda: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
    }
}

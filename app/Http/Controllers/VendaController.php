<?php

namespace App\Http\Controllers;

use App\Models\Venda;
use App\Models\Cliente;
use App\Models\Produto;
use Illuminate\Http\Request;

class VendaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $all_items = Venda::all();
        $all_clientes = Cliente::with('user')->get();
        return view('admin.venda.index', compact('all_items', 'all_clientes'));
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
                'cliente_id' => 'required|exists:clientes,id',
                'numero_factura' => 'required|string|max:255',
                'condicao_venda' => 'required|in:contado,credito',
                // Adicione outras regras de validação conforme necessário
            ]);

            // Criação de um novo Compra no banco de dados
            $venda = Venda::create([
                'data' => $request->input('data'),
                'cliente_id' => $request->input('cliente_id'),
                'numero_factura' => $request->input('numero_factura'),
                'condicao_venda' => $request->input('condicao_venda'),
                // Adicione outros campos conforme necessário
            ]);

            // Exibir toastr de sucesso
            return redirect()->route('vendas.show', ['venda' => $venda->id])->with('toastr', [
                'type'    => 'success',
                'message' => 'Compra criada com sucesso!',
                'title'   => 'Sucesso',
            ]);
        } catch (\Exception $e) {
            // Exibir toastr de Erro
            return redirect()->back()->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao criar a Venda: <br>'. $e->getMessage(),
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
            $venda = Venda::findOrFail($id);
            $all_produtos = Produto::all();
            // Retornar a view com os detalhes do shipper
            return view('admin.venda.show', compact('venda', 'all_produtos'));
        } catch (\Exception $e) {
            // Exibir uma mensagem de erro ou redirecionar para uma página de erro
            return redirect()->back()->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao exibir os detalhes da Venda: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Venda $venda)
    {
        try {
            // Validação dos dados do formulário
            $request->validate([
                'cancelado' => 'required|boolean',
                // Adicione outras regras de validação conforme necessário
            ]);

            // Atualizar os dados do Shipper
            $venda->update([
                'cancelado' => $request->input('cancelado'),
                // Adicione outros campos conforme necessário
            ]);

            // Exibir toastr de sucesso
            return redirect()->route('vendas.show', ['venda' => $venda->id])->with('toastr', [
                'type'    => 'success',
                'message' => 'Venda atualizada com sucesso!',
                'title'   => 'Sucesso',
            ]);
        } catch (\Exception $e) {
            // Exibir toastr de Erro
            return redirect()->route('vendas.show', ['venda' => $venda->id])->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao atualizar a Venda: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Venda $venda)
    {
        if ($venda->itens()->count() > 0) {
            return redirect()->back()->with('toastr', [
                'type'    => 'error',
                'message' => 'Não é possível excluir a Venda, pois ele possui produtos associados.',
                'title'   => 'Erro',
            ]);
        }

        try {
            // Excluir a Compra do banco de dados
            $venda->delete();

            // Redirecionar após a exclusão bem-sucedida
            return redirect()->route('compras.index')->with('toastr', [
                'type'    => 'success',
                'message' => 'Venda excluída com sucesso!',
                'title'   => 'Sucesso',
            ]);
        } catch (\Exception $e) {
            // Exibir toastr de erro se ocorrer uma exceção
            return redirect()->back()->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao excluir a Venda: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
    }
}

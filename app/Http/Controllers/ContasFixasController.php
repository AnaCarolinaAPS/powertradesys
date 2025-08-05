<?php

namespace App\Http\Controllers;

use App\Models\ContasFixas;
use App\Models\Categoria;
use Illuminate\Http\Request;

class ContasFixasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $all_items = ContasFixas::where('ativa', true)->get();
        $all_categorias = Categoria::where('tipo', 'categoria')
                            ->get();
        $all_subcategorias = Categoria::where('tipo', 'subcategoria')
                        ->get();
        $all_inativas = ContasFixas::where('ativa', false)->get();
        return view('admin.contafixa.index', compact('all_items', 'all_categorias', 'all_subcategorias', 'all_inativas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
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
            $conta = ContasFixas::create([
                'descricao' => $request->input('descricao'),
                'moeda' => $request->input('moeda'),
                'valor' => $request->input('valor'),
                'data_vencimento' => $request->input('data_vencimento'),
                'categoria_id' => $request->input('categoria_id'),
                'subcategoria_id' => $request->input('subcategoria_id'),
                // Adicione outros campos conforme necessário
            ]);

            // Exibir toastr de sucesso
            return redirect()->route('contasfixas.index')->with('toastr', [
                'type'    => 'success',
                'message' => 'Conta Fixa criada com sucesso!',
                'title'   => 'Sucesso',
            ]);
        } catch (\Exception $e) {
            // Exibir toastr de Erro
            return redirect()->route('contasfixas.index')->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao criar a Conta Fixa: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $conta = ContasFixas::find($id);
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
                'ativo' => 'required|boolean',
                'valor' => 'required|numeric',
                'data_vencimento' => 'required|date',
                // Adicione outras regras de validação conforme necessário
            ]);

            $contasFixas = ContasFixas::find($id);

            // Atualizar os dados
            $contasFixas->update([
                'descricao' => $request->input('descricao'),
                'ativa' => $request->input('ativo'),
                'valor' => $request->input('valor'),
                'data_vencimento' => $request->input('data_vencimento'),
                // Adicione outros campos conforme necessário
            ]);

            // Exibir toastr de sucesso
            return redirect()->back()->with('toastr', [
                'type'    => 'success',
                'message' => 'Conta Fixa atualizada com sucesso!',
                'title'   => 'Sucesso',
            ]);
        } catch (\Exception $e) {
            // Exibir toastr de Erro
            return redirect()->back()->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao atualizar a Conta Fixa: <br>'. $e->getMessage(),
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
            $conta = ContasFixas::find($id);
            //Adicionar Lógica para que o registro não possa ser excluído caso tenha algum registro de conta a pagar criado

            // Excluir o registro do banco de dados
            $conta->delete();

            // Redirecionar após a exclusão bem-sucedida
            return redirect()->back()->with('toastr', [
                'type'    => 'success',
                'message' => 'Conta Fixa excluída com sucesso!',
                'title'   => 'Sucesso',
            ]);
        } catch (\Exception $e) {
            // Exibir toastr de erro se ocorrer uma exceção
            return redirect()->back()->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao excluir a Conta Fixa: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
    }
}

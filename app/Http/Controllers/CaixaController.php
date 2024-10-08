<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Caixa;

class CaixaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $all_caixas = Caixa::all();
        return view('admin.caixa.index', compact('all_caixas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validação dos dados do formulário
            $request->validate([
                'nome' => 'required|string|max:255|unique:caixas',
                'observacoes' => 'nullable|string',
                'moeda' => 'required|in:U$,R$,G$,outros',
                // Adicione outras regras de validação conforme necessário
            ]);

            // Criação de um novo Shipper no banco de dados
            Caixa::create([
                'nome' => $request->input('nome'),
                'observacoes' => $request->input('observacoes'),
                'moeda' => $request->input('moeda'),
                // Adicione outros campos conforme necessário
            ]);

            // Exibir toastr de sucesso
            return redirect()->route('caixas.index')->with('toastr', [
                'type'    => 'success',
                'message' => 'Caixa criada com sucesso!',
                'title'   => 'Sucesso',
            ]);
        } catch (\Exception $e) {
            // Exibir toastr de Erro
            return redirect()->route('caixas.index')->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao criar a Caixa: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $caixa = Caixa::find($id);
        return response()->json($caixa);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Caixa $caixa)
    {
        try {
            if ($caixa->nome == $request->input('nome')) {
                $request->validate([
                    'observacoes' => 'nullable|string',
                    'moeda' => 'required|in:U$,R$,G$,outros',
                    'aberto' => 'required|boolean',
                    // Adicione outras regras de validação conforme necessário
                ]);
            } else {
                $request->validate([
                    'nome' => 'required|string|max:255|unique:caixas',
                    'observacoes' => 'nullable|string',
                    'moeda' => 'required|in:U$,R$,G$,outros',
                    'aberto' => 'required|boolean',
                    // Adicione outras regras de validação conforme necessário
                ]);
            }

            // Atualizar os dados
            $caixa->update([
                'nome' => $request->input('nome'),
                'observacoes' => $request->input('observacoes'),
                'moeda' => $request->input('moeda'),
                'aberto' => $request->input('aberto'),
                // Adicione outros campos conforme necessário
            ]);

            // Exibir toastr de sucesso
            return redirect()->back()->with('toastr', [
                'type'    => 'success',
                'message' => 'Caixa atualizada com sucesso!',
                'title'   => 'Sucesso',
            ]);
        } catch (\Exception $e) {
            // Exibir toastr de Erro
            return redirect()->back()->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao atualizar a Caixa: <br>'. $e->getMessage(),
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
            $caixa = Caixa::find($id);
            //Adicionar Lógica para que o freteiro não possa ser excluído caso tenha Saídas dos Pacotes no seu nome

            // Excluir o Freteiro do banco de dados
            $caixa->delete();

            // Redirecionar após a exclusão bem-sucedida
            return redirect()->back()->with('toastr', [
                'type'    => 'success',
                'message' => 'Caixa excluída com sucesso!',
                'title'   => 'Sucesso',
            ]);
        } catch (\Exception $e) {
            // Exibir toastr de erro se ocorrer uma exceção
            return redirect()->back()->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao excluir a Caixa: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
    }
}

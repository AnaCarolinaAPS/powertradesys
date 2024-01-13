<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Freteiro;

class FreteiroController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $all_freteiros = Freteiro::all();
        return view('admin.freteiro.index', compact('all_freteiros'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validação dos dados do formulário
            $request->validate([
                'nome' => 'required|string|max:255|unique:freteiros',
                'contato' => 'required|string|max:255',
                // Adicione outras regras de validação conforme necessário
            ]);

            // Criação de um novo Freteiro no banco de dados
            Freteiro::create([
                'nome' => $request->input('nome'),
                'contato' => $request->input('contato'),
                // Adicione outros campos conforme necessário
            ]);

            // Exibir toastr de sucesso
            return redirect()->route('freteiros.index')->with('toastr', [
                'type'    => 'success',
                'message' => 'Freteiro criado com sucesso!',
                'title'   => 'Sucesso',
            ]);
        } catch (\Exception $e) {
            // Exibir toastr de Erro
            return redirect()->route('freteiros.index')->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao criar o Freteiro: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $freteiro = Freteiro::find($id);
        return response()->json($freteiro);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Freteiro $freteiro)
    {
        try {

            if ($freteiro->nome == $request->input('nome')) {
                $request->validate([
                    'contato' => 'required|string',
                    // Adicione outras regras de validação conforme necessário
                ]);
            } else {
                $request->validate([
                    'nome' => 'required|string|max:255|unique:freteiros',
                    'contato' => 'required|string',
                    // Adicione outras regras de validação conforme necessário
                ]);
            }

            // Atualizar os dados
            $freteiro->update([
                'nome' => $request->input('nome'),
                'contato' => $request->input('contato'),
                // Adicione outros campos conforme necessário
            ]);

            // Exibir toastr de sucesso
            return redirect()->back()->with('toastr', [
                'type'    => 'success',
                'message' => 'Freteiro atualizado com sucesso!',
                'title'   => 'Sucesso',
            ]);
        } catch (\Exception $e) {
            // Exibir toastr de Erro
            return redirect()->back()->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao atualizar o Freteiro: <br>'. $e->getMessage(),
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
            $freteiro = Freteiro::find($id);
            //Adicionar Lógica para que o freteiro não possa ser excluído caso tenha Saídas dos Pacotes no seu nome

            // Excluir o Freteiro do banco de dados
            $freteiro->delete();

            // Redirecionar após a exclusão bem-sucedida
            return redirect()->back()->with('toastr', [
                'type'    => 'success',
                'message' => 'Freteiro excluído com sucesso!',
                'title'   => 'Sucesso',
            ]);
        } catch (\Exception $e) {
            // Exibir toastr de erro se ocorrer uma exceção
            return redirect()->back()->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao excluir o Freteiro: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
    }
}

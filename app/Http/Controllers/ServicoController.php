<?php

namespace App\Http\Controllers;

use App\Models\Servico;
use Illuminate\Http\Request;

class ServicoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $all_items = Servico::all();
        return view('admin.servico.index', compact('all_items'));
    }

    public function show($id)
    {
        $servico = Servico::find($id);
        return response()->json($servico);
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
                'data_inicio' => 'required|date',
                'data_fim' => 'nullable|date',
                'preco' => 'required|numeric',
                'tipo' => 'required|in:aereo,maritimo,compra,outros'
                // Adicione outras regras de validação conforme necessário
            ]);

            // Criação de um novo Servico no banco de dados
            Servico::create([
                'descricao' => $request->input('descricao'),
                'data_inicio' => $request->input('data_inicio'),
                'data_fim' => $request->input('data_fim'),
                'preco' => $request->input('preco'),
                'tipo' => $request->input('tipo'),
                // Adicione outros campos conforme necessário
            ]);

            // Exibir toastr de sucesso
            return redirect()->back()->with('toastr', [
                'type'    => 'success',
                'message' => 'Serviço criado com sucesso!',
                'title'   => 'Sucesso',
            ]);
        } catch (\Exception $e) {
            // Exibir toastr de Erro
            return redirect()->back()->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao criar o Serviço: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Servico $servico)
    {
        try {
            // Validação dos dados do formulário
            $request->validate([
                'descricao' => 'required|string|max:255',
                'data_inicio' => 'required|date',
                'data_fim' => 'nullable|date',
                'preco' => 'required|numeric',
                // Adicione outras regras de validação conforme necessário
            ]);

            $servico = Servico::find($request->input('id'));
            // Atualizar os dados

            $dataFim = $request->input('data_fim');
            if ($dataFim !== null) {
                $servico->update([
                    'descricao' => $request->input('descricao'),
                    'data_inicio' => $request->input('data_inicio'),
                    'data_fim' => $request->input('data_fim'),
                    'preco' => $request->input('preco'),
                    // Adicione outros campos conforme necessário
                ]);
            } else {
                $servico->update([
                    'descricao' => $request->input('descricao'),
                    'data_inicio' => $request->input('data_inicio'),
                    'preco' => $request->input('preco'),
                    // Adicione outros campos conforme necessário
                ]);
            }

            // Exibir toastr de sucesso
            return redirect()->back()->with('toastr', [
                'type'    => 'success',
                'message' => 'Serviço atualizado com sucesso!',
                'title'   => 'Sucesso',
            ]);
        } catch (\Exception $e) {
            // Exibir toastr de Erro
            return redirect()->back()->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao atualizar o Serviço: <br>'. $e->getMessage(),
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
            $servico = Servico::find($id);
            // Excluir o Servico do banco de dados
            $servico->delete();

            // Redirecionar após a exclusão bem-sucedida
            return redirect()->back()->with('toastr', [
                'type'    => 'success',
                'message' => 'Serviço excluído com sucesso!',
                'title'   => 'Sucesso',
            ]);
        } catch (\Exception $e) {
            // Exibir toastr de erro se ocorrer uma exceção
            return redirect()->back()->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao excluir o Serviço: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
    }
}

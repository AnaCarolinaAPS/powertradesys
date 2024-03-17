<?php

namespace App\Http\Controllers;

use App\Models\ServicosFornecedor;
use Illuminate\Http\Request;

class ServicosFornecedorController extends Controller
{
    public function show($id)
    {
        $servico = ServicosFornecedor::find($id);
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
                'fornecedor_id' => 'required|exists:fornecedors,id',
                // Adicione outras regras de validação conforme necessário
            ]);

            // Criação de um novo Shipper no banco de dados
            ServicosFornecedor::create([
                'descricao' => $request->input('descricao'),
                'data_inicio' => $request->input('data_inicio'),
                'data_fim' => $request->input('data_fim'),
                'fornecedor_id' => $request->input('fornecedor_id'),
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
    public function update(Request $request, ServicosFornecedor $servico)
    {
        try {
            // Validação dos dados do formulário
            $request->validate([
                'descricao' => 'required|string|max:255',
                'data_inicio' => 'required|date',
                'data_fim' => 'nullable|date',
                // Adicione outras regras de validação conforme necessário
            ]);

            $servico = ServicosFornecedor::find($request->input('id'));
            // Atualizar os dados

            $dataFim = $request->input('data_fim');
            if ($dataFim !== null) {
                $servico->update([
                    'descricao' => $request->input('descricao'),
                    'data_inicio' => $request->input('data_inicio'),
                    'data_fim' => $request->input('data_fim'),
                    // Adicione outros campos conforme necessário
                ]);
            } else {
                $servico->update([
                    'descricao' => $request->input('descricao'),
                    'data_inicio' => $request->input('data_inicio'),
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
            $servico = ServicosFornecedor::find($id);
            // Excluir o Shipper do banco de dados
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

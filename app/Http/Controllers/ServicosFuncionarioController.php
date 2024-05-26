<?php

namespace App\Http\Controllers;

use App\Models\ServicosFuncionario;
use Illuminate\Http\Request;

class ServicosFuncionarioController extends Controller
{
    public function show($id)
    {
        $servico = ServicosFuncionario::find($id);
        return response()->json($servico);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validação dos dados do formulário
            $validatedData = $request->validate([
                'descricao' => 'required|string|max:255',
                'valor' => 'required|numeric',
                'tipo' => 'required|in:fixo,variavel',
                'moeda' => 'required|in:U$,G$,R$',
                'frequencia' => 'required|in:mensal,quinzenal,semanal',
                'data_inicio' => 'required|date',
                'funcionario_id' => 'required|exists:funcionarios,id',
                // Adicione outras regras de validação conforme necessário
            ]);

            $servico = ServicosFuncionario::create($validatedData);

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

    // Atualiza um funcionário existente no banco de dados
    public function update(Request $request, ServicosFuncionario $servico)
    {
        try {
            $validatedData = $request->validate([
                'descricao' => 'required|string|max:255',
                'valor' => 'required|numeric',
                'tipo' => 'required|in:fixo,variavel',
                'moeda' => 'required|in:U$,G$,R$',
                'frequencia' => 'required|in:mensal,quinzenal,semanal',
                'data_inicio' => 'required|date',
                // Adicione outras regras de validação conforme necessário
            ]);

            $servico->update($validatedData);

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

    // Remove um funcionário do banco de dados
    public function destroy($id)
    {
        try {
            $servico = ServicosFuncionario::findOrFail($id);

            // Excluir o Freteiro do banco de dados
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

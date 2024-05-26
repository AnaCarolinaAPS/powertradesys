<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Funcionario;

class FuncionarioController extends Controller
{
    // Lista todos os funcionários
    public function index()
    {
        $all_funcionarios = Funcionario::all();
        return view('admin.funcionario.index', compact('all_funcionarios'));
    }

    // Armazena um novo funcionário no banco de dados
    public function store(Request $request)
    {
        try {
            // Validação dos dados do formulário
            $validatedData = $request->validate([
                'ci' => 'required|string|unique:funcionarios',
                'nome' => 'required|string|max:255',
                'email' => 'nullable|string|email|max:255',
                'contato' => 'nullable|string|max:255',
                'cargo' => 'required|string|max:255',
                'data_contratacao' => 'required|date',
            ]);

            $funcionario = Funcionario::create($validatedData);
            // Exibir toastr de sucesso
            return redirect()->route('funcionarios.show', ['funcionario' => $funcionario->id])->with('toastr', [
                'type'    => 'success',
                'message' => 'Funcionário criado com sucesso!',
                'title'   => 'Sucesso',
            ]);
        } catch (\Exception $e) {
            // Exibir toastr de Erro
            return redirect()->route('funcionarios.index')->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao criar o Funcionário: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
    }

    // Mostra os detalhes de um funcionário específico
    public function show(Funcionario $funcionario)
    {
        return view('admin.funcionario.show', compact('funcionario'));
    }

    // Atualiza um funcionário existente no banco de dados
    public function update(Request $request, Funcionario $funcionario)
    {
        try {
            $validatedData = $request->validate([
                'email' => 'nullable|string|email|max:255|unique:funcionarios,email,' . $funcionario->id,
                'contato' => 'nullable|string|max:255',
                'cargo' => 'required|string|max:255',
                'data_desligamento' => 'nullable|date',
            ]);

            $funcionario->update($validatedData);

            // Exibir toastr de sucesso
            return redirect()->back()->with('toastr', [
                'type'    => 'success',
                'message' => 'Funcionario atualizado com sucesso!',
                'title'   => 'Sucesso',
            ]);
        } catch (\Exception $e) {
            // Exibir toastr de Erro
            return redirect()->back()->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao atualizar o Funcionario: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
    }

    // Remove um funcionário do banco de dados
    public function destroy(Funcionario $funcionario)
    {
        try {
            $funcionario = Funcionario::find($funcionario->id);

            // Excluir o Freteiro do banco de dados
            $funcionario->delete();

            // Redirecionar após a exclusão bem-sucedida
            return redirect()->route('funcionarios.index')->with('toastr', [
                'type'    => 'success',
                'message' => 'Funcionário excluído com sucesso!',
                'title'   => 'Sucesso',
            ]);
        } catch (\Exception $e) {
            // Exibir toastr de erro se ocorrer uma exceção
            return redirect()->back()->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao excluir o Funcionário: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
    }
}

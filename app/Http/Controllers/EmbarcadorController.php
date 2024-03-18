<?php

namespace App\Http\Controllers;

use App\Models\Fornecedor;
use Illuminate\Http\Request;

class EmbarcadorController extends Controller
{
    public function index()
    {
        // Lógica para mostrar uma lista de embarcadores
        $all_items = Fornecedor::where('tipo', 'embarcador')->get();
        return view('admin.embarcador.index', compact('all_items'));
    }

    public function store(Request $request)
    {
        // Lógica para armazenar um novo embarcador
        try {
            // Validação dos dados do formulário
            $request->validate([
                'nome' => 'required|string|max:255|unique:fornecedors',
                'contato' => 'string|max:255',
                // Adicione outras regras de validação conforme necessário
            ]);

            // Criação de um novo Embarcador no banco de dados
            $embarcador = Fornecedor::create([
                'nome' => $request->input('nome'),
                'contato' => $request->input('contato'),
                'tipo' => 'embarcador',
                // Adicione outros campos conforme necessário
            ]);

            // Exibir toastr de sucesso
            return redirect()->route('embarcadores.show', ['embarcador' => $embarcador->id])->with('toastr', [
                'type'    => 'success',
                'message' => 'Embarcador criado com sucesso!',
                'title'   => 'Sucesso',
            ]);
        } catch (\Exception $e) {
            // Exibir toastr de Erro
            return redirect()->route('embarcadores.index')->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao criar o Embarcador: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
    }

    public function show($id)
    {
        // Lógica para mostrar um embarcador específico
        try {
            // Buscar o shipper pelo ID
            $embarcador = Fornecedor::findOrFail($id);

            // Retornar a view com os detalhes do shipper
            return view('admin.embarcador.show', compact('embarcador'));
        } catch (\Exception $e) {
            // Exibir uma mensagem de erro ou redirecionar para uma página de erro
            return redirect()->route('embarcadores.index')->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao exibir os detalhes do Embarcador: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
    }

    public function update(Request $request, Fornecedor $embarcador)
    {
        // Lógica para atualizar um embarcador específico
        try {

            if ($embarcador->nome == $request->input('nome')) {
                // Validação dos dados do formulário
                $request->validate([
                    'contato' => 'string|max:255',
                    // Adicione outras regras de validação conforme necessário
                ]);

                // Atualizar os dados do Shipper
                $embarcador->update([
                    'contato' => $request->input('contato'),
                    // Adicione outros campos conforme necessário
                ]);
            } else {
                // Validação dos dados do formulário
                $request->validate([
                    'nome' => 'required|string|max:255|unique:fornecedors',
                    'contato' => 'string|max:255',
                    // Adicione outras regras de validação conforme necessário
                ]);

                // Atualizar os dados do Shipper
                $embarcador->update([
                    'nome' => $request->input('nome'),
                    'contato' => $request->input('contato'),
                    // Adicione outros campos conforme necessário
                ]);
            }

            // Exibir toastr de sucesso
            return redirect()->route('embarcadores.show', ['embarcador' => $embarcador->id])->with('toastr', [
                'type'    => 'success',
                'message' => 'Embarcador atualizado com sucesso!',
                'title'   => 'Sucesso',
            ]);
        } catch (\Exception $e) {
            // Exibir toastr de Erro
            return redirect()->route('embarcadores.show', ['embarcador' => $embarcador->id])->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao atualizar o Embarcador: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
    }

    public function destroy(Fornecedor $embarcador)
    {
        // Lógica para excluir um embarcador específico
        try {
            // Excluir o Shipper do banco de dados
            $embarcador->delete();

            // Redirecionar após a exclusão bem-sucedida
            return redirect()->route('embarcadores.index')->with('toastr', [
                'type'    => 'success',
                'message' => 'Embarcador excluído com sucesso!',
                'title'   => 'Sucesso',
            ]);
        } catch (\Exception $e) {
            // Exibir toastr de erro se ocorrer uma exceção
            return redirect()->back()->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao atualizar o Embarcador: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
    }
}

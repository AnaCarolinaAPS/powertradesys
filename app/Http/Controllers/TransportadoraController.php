<?php

namespace App\Http\Controllers;

use App\Models\Fornecedor;
use Illuminate\Http\Request;

class TransportadoraController extends Controller
{
    public function index()
    {
        // Lógica para mostrar uma lista de transportadoras
        $all_items = Fornecedor::where('tipo', 'transportadora')->get();
        return view('admin.transportadora.index', compact('all_items'));
    }

    public function store(Request $request)
    {
        // Lógica para armazenar uma nova transportadora
        try {
            // Validação dos dados do formulário
            $request->validate([
                'nome' => 'required|string|max:255|unique:fornecedors',
                'contato' => 'string|max:255',
                // Adicione outras regras de validação conforme necessário
            ]);

            // Criação de uma nova Transportadora no banco de dados
            $transportadora = Fornecedor::create([
                'nome' => $request->input('nome'),
                'contato' => $request->input('contato'),
                'tipo' => 'transportadora',
                // Adicione outros campos conforme necessário
            ]);

            // Exibir toastr de sucesso
            return redirect()->route('transportadoras.show', ['transportadora' => $transportadora->id])->with('toastr', [
                'type'    => 'success',
                'message' => 'Transportadora criada com sucesso!',
                'title'   => 'Sucesso',
            ]);
        } catch (\Exception $e) {
            // Exibir toastr de Erro
            return redirect()->route('transportadoras.index')->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao criar a Transportadora: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
    }

    public function show($id)
    {
        // Lógica para mostrar uma transportadora específico
        try {
            $transportadora = Fornecedor::findOrFail($id);

            // Retornar a view com os detalhes do shipper
            return view('admin.transportadora.show', compact('transportadora'));
        } catch (\Exception $e) {
            // Exibir uma mensagem de erro ou redirecionar para uma página de erro
            return redirect()->route('transportadoras.index')->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao exibir os detalhes da Transportadora: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
    }

    public function update(Request $request, Fornecedor $transportadora)
    {
        // Lógica para atualizar uma transportadora específico
        try {

            if ($transportadora->nome == $request->input('nome')) {
                // Validação dos dados do formulário
                $request->validate([
                    'contato' => 'string|max:255',
                    // Adicione outras regras de validação conforme necessário
                ]);

                // Atualizar os dados do Shipper
                $transportadora->update([
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
                $transportadora->update([
                    'nome' => $request->input('nome'),
                    'contato' => $request->input('contato'),
                    // Adicione outros campos conforme necessário
                ]);
            }

            // Exibir toastr de sucesso
            return redirect()->route('transportadoras.show', ['transportadora' => $transportadora->id])->with('toastr', [
                'type'    => 'success',
                'message' => 'Transportadora atualizado com sucesso!',
                'title'   => 'Sucesso',
            ]);
        } catch (\Exception $e) {
            // Exibir toastr de Erro
            return redirect()->route('transportadoras.show', ['transportadora' => $transportadora->id])->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao atualizar a Transportadora: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
    }

    public function destroy(Fornecedor $transportadora)
    {
        // Lógica para excluir um embarcador específico
        try {
            // Excluir o Shipper do banco de dados
            $transportadora->delete();

            // Redirecionar após a exclusão bem-sucedida
            return redirect()->route('transportadoras.index')->with('toastr', [
                'type'    => 'success',
                'message' => 'Transportadora excluído com sucesso!',
                'title'   => 'Sucesso',
            ]);
        } catch (\Exception $e) {
            // Exibir toastr de erro se ocorrer uma exceção
            return redirect()->back()->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao atualizar a Transportadora: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\MotorProovedor;
use App\Models\Motor;
use Illuminate\Http\Request;

class MotorProovedorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $all_items = MotorProovedor::all();
        return view('admin.motorproovedor.index', compact('all_items'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validação dos dados do formulário
            $request->validate([
                'nome' => 'required|string|max:255',
                'descricao' => 'nullable|string|max:255',
                'contato' => 'nullable|string|max:255',
                // Adicione outras regras de validação conforme necessário
            ]);

            // Criação de uma nova entidade no banco de dados
            $proovedor = MotorProovedor::create([
                'nome' => $request->input('nome'),
                'descricao' => $request->input('descricao'),
                'contato' => $request->input('contato'),
                // Adicione outros campos conforme necessário
            ]);

            // Exibir toastr de sucesso
            return redirect()->route('motorproovedores.show', ['item' => $proovedor->id])->with('toastr', [
                'type'    => 'success',
                'message' => 'Proovedor criado com sucesso!',
                'title'   => 'Sucesso',
            ]);
        } catch (\Exception $e) {
            // Exibir toastr de Erro
            return redirect()->back()->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao criar o Proovedor: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Lógica para mostrar um item específico
        try {
            // Buscar o item pelo ID
            $item = MotorProovedor::findOrFail($id);
            $all_items = Motor::all();

            // Retornar a view com os detalhes do shipper
            return view('admin.motorproovedor.show', compact('item', 'all_items'));
        } catch (\Exception $e) {
            // Exibir uma mensagem de erro ou redirecionar para uma página de erro
            return redirect()->route('motorproovedores.index')->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao exibir os detalhes do Proovedor: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            // Validação dos dados do formulário
            $request->validate([
                'nome' => 'required|string|max:255',
                'descricao' => 'nullable|string|max:255',
                'contato' => 'nullable|string|max:255',
                // Adicione outras regras de validação conforme necessário
            ]);

            // Encontra o item a ser atualizado
            $proovedor = MotorProovedor::findOrFail($id);

            // Atualizar os dados
            $proovedor->update([
                'nome' => $request->input('nome'),
                'descricao' => $request->input('descricao'),
                'contato' => $request->input('contato'),
                // Adicione outros campos conforme necessário
            ]);

            // Exibir toastr de sucesso
            return redirect()->back()->with('toastr', [
                'type'    => 'success',
                'message' => 'Proovedor atualizado com sucesso!',
                'title'   => 'Sucesso',
            ]);
        } catch (\Exception $e) {
            // Exibir toastr de Erro
            return redirect()->back()->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao atualizar o Proovedor: <br>'. $e->getMessage(),
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
            // Encontra o item a ser atualizado
            $proovedor = MotorProovedor::findOrFail($id);
            
            // Excluir o item do banco de dados
            $proovedor->delete();

            // Redirecionar após a exclusão bem-sucedida
            return redirect()->route('motorproovedores.index')->with('toastr', [
                'type'    => 'success',
                'message' => 'Proovedor excluído com sucesso!',
                'title'   => 'Sucesso',
            ]);
        } catch (\Exception $e) {
            // Exibir toastr de erro se ocorrer uma exceção
            return redirect()->route('motorproovedores.index')->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao excluir o Proovedor: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
    }
}

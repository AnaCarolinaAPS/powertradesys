<?php

namespace App\Http\Controllers;

use App\Models\MotorProovedorServico;
use Illuminate\Http\Request;

class MotorProovedorServicoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validação dos dados do formulário
            $request->validate([
                'tipo_servico' => 'required|in:compra,despacho,envio,pickup,travessia,nota,outros',
                'descricao' => 'required|string|max:255',
                'preco' => 'required|numeric',
                'tipo_moeda' => 'nullable|in:dolar,reais,guaranis',
                'data_inicio' => 'required|date',
                'data_fim' => 'nullable|date',
                'motor_proovedor_id' => 'required|exists:motor_proovedors,id',
                // Adicione outras regras de validação conforme necessário
            ]);

            // Criação de um novo Shipper no banco de dados
            ServicosFornecedor::create([
                'tipo_servico' => $request->input('tipo_servico'),
                'descricao' => $request->input('descricao'),
                'preco' => $request->input('preco'),
                'tipo_moeda' => $request->input('tipo_moeda'),
                'data_inicio' => $request->input('data_inicio'),
                'data_fim' => $request->input('data_fim'),
                'motor_proovedor_id' => $request->input('motor_proovedor_id'),
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
     * Display the specified resource.
     */
    public function show($id)
    {
        $servico = MotorProovedorServico::find($id);
        return response()->json($servico);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            // Validação dos dados do formulário
            $validatedData = $request->validate([
                'tipo_servico' => 'required|in:compra,despacho,envio,pickup,travessia,nota,outros',
                'descricao' => 'required|string|max:255',
                'preco' => 'required|numeric',
                'tipo_moeda' => 'nullable|in:dolar,reais,guaranis',
                'data_inicio' => 'required|date',
                'data_fim' => 'nullable|date',
                // Adicione outras regras de validação conforme necessário
            ]);

            // Encontra o item a ser atualizado
            $servico = MotorProovedorServico::find($id);
            // Atualiza o item com os valores validados
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $servico = MotorProovedorServico::find($id);
            // Excluir o item do banco de dados
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

<?php

namespace App\Http\Controllers;

use App\Models\Caixa;
use Illuminate\Http\Request;
use App\Models\Funcionario;
use App\Models\FolhaPagamento;
use App\Models\FolhaPagamentoItem;
use App\Models\ServicosFuncionario;

class FolhaPagamentoController extends Controller
{
    // Lista todos os funcionários
    public function index()
    {
        $all_folhas = FolhaPagamento::all();
        $all_funcionarios = Funcionario::all();
        return view('admin.folhapagamento.index', compact('all_folhas', 'all_funcionarios'));
    }

    // Armazena um novo funcionário no banco de dados
    public function store(Request $request)
    {
        try {
            // Validação dos dados do formulário
            $validatedData = $request->validate([
                'funcionario_id' => 'required|exists:fornecedors,id',
                'data' => 'required|date',
            ]);

            $dataCarbon = \Carbon\Carbon::createFromFormat('Y-m-d', $request->input('data'));

            // Converta a data para o formato 'Y/m'
            $periodo = $dataCarbon->format('Y/m');
            // //Data retira as das referentas a semana para buscar o fechamento do caixa de DESTINO
            // $periodo = \Carbon\Carbon::createFromFormat('Y/m', $request->input('data'));

            //Cria um registro em "destino"
            $folha = FolhaPagamento::create([
                'funcionario_id' => $request->input('funcionario_id'),
                'periodo' => $periodo,
                // Adicione outros campos conforme necessário
            ]);

            // Exibir toastr de sucesso
            return redirect()->route('folhapagamentos.show', ['folha' => $folha->id])->with('toastr', [
                'type'    => 'success',
                'message' => 'Funcionário criado com sucesso!',
                'title'   => 'Sucesso',
            ]);
        } catch (\Exception $e) {
            // Exibir toastr de Erro
            return redirect()->route('folhapagamentos.index')->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao criar a Folha de Pagamento: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
    }

    // Mostra os detalhes da entidade específica
    public function show(FolhaPagamento $folha)
    {
        $all_servicos = ServicosFuncionario::where('funcionario_id', $folha->funcionario_id)->whereNull('data_fim')->get();
        $all_items = FolhaPagamentoItem::where('folha_pagamento_id', $folha->id)->get();
        $all_caixas = Caixa::all();
        return view('admin.folhapagamento.show', compact('folha', 'all_servicos', 'all_items', 'all_caixas'));
    }

    // Atualiza a entidade existente no banco de dados
    public function update(Request $request, FolhaPagamento $folha)
    {
        // try {
        //     $request->validate([
        //         'data' => 'required|date',
        //     ]);

        //     //Data retira as das referentas a semana para buscar o fechamento do caixa de DESTINO
        //     $periodo = \Carbon\Carbon::createFromFormat('Y/m', $request->input('data'));

        //     // Atualizar os dados
        //     $folha->update([
        //         'periodo' => $periodo,
        //         // Adicione outros campos conforme necessário
        //     ]);

        //     // $folha->update($validatedData);

        //     // Exibir toastr de sucesso
        //     return redirect()->back()->with('toastr', [
        //         'type'    => 'success',
        //         'message' => 'Folha de Pagamento atualizado com sucesso!',
        //         'title'   => 'Sucesso',
        //     ]);
        // } catch (\Exception $e) {
        //     // Exibir toastr de Erro
        //     return redirect()->back()->with('toastr', [
        //         'type'    => 'error',
        //         'message' => 'Ocorreu um erro ao atualizar a Folha de Pagamento: <br>'. $e->getMessage(),
        //         'title'   => 'Erro',
        //     ]);
        // }
    }

    // Remove um funcionário do banco de dados
    public function destroy(FolhaPagamento $folha)
    {
        try {
            $folha = FolhaPagamento::find($folha->id);

            // Excluir o Freteiro do banco de dados
            $folha->delete();

            // Redirecionar após a exclusão bem-sucedida
            return redirect()->route('folhapagamentos.index')->with('toastr', [
                'type'    => 'success',
                'message' => 'Folha de Pagamento excluída com sucesso!',
                'title'   => 'Sucesso',
            ]);
        } catch (\Exception $e) {
            // Exibir toastr de erro se ocorrer uma exceção
            return redirect()->back()->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao excluir a Folha de Pagamento: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
    }
}

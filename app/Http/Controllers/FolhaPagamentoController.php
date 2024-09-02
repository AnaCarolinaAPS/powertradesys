<?php

namespace App\Http\Controllers;

use App\Models\Caixa;
use Illuminate\Http\Request;
use App\Models\Funcionario;
use App\Models\FolhaPagamento;
use App\Models\FolhaPagamentoItem;
use App\Models\ServicosFuncionario;
use App\Models\Pagamento;

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
                'funcionario_id' => 'required|exists:funcionarios,id',
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
        $all_items = FolhaPagamentoItem::where('folha_pagamento_id', $folha->id)->orderBy('id')->get();
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

    public function distribuirPagamento(Funcionario $funcionario, Pagamento $pagamento, FolhaPagamento $folha)
    {
        //Calcula o valor do pagamento para poder distribuir entre as invoices
        $valorRestante = $pagamento->valor;

        // Calcula o valor em ABERTO da Invoice
        $saldoAberto = $folha->valor_total() - $folha->valor_pago();

        // Verificar se o valor do pagamento pode pagar totalmente a invoice atual
        if ($valorRestante <= $saldoAberto) {
            $folha->pagamentos()->attach($pagamento->id, ['valor_recebido' => $valorRestante]);
        // Se o valor do pagamento for maior que o da folha atual distribui entre invoices!
        } else {
            $folha->pagamentos()->attach($pagamento->id, ['valor_recebido' => $saldoAberto]);
            $valorRestante -= $saldoAberto;

            // Filtra TODAS as invoices que tem valores em aberto
            $folhasEmAberto = $funcionario->folhas_pagamento()->get()->filter(function ($folha) {
                return $folha->valor_pago() < $folha->valor_total();
            });

            foreach ($folhasEmAberto as $aberto) {
                // Calcula o valor em ABERTO da Invoice
                $saldoAberto = $aberto->valor_total() - $aberto->valor_pago();

                // Verificar se o valor restante pode pagar totalmente a invoice atual
                if ($valorRestante >= $saldoAberto) {
                    // O valor pago é suficiente para pagar totalmente esta invoice
                    $valorRestante -= $saldoAberto;
                    // Atualiza a coluna da invoice com o pagamento
                    // Registrar o pagamento para esta invoice
                    $aberto->pagamentos()->attach($pagamento->id, ['valor_recebido' => $saldoAberto]);

                } else {
                    if ($valorRestante > 0) {
                        // Atualiza a coluna da invoice com o pagamento do valor RESTANTE (o que sobrou dos pagamentos)
                        // Registrar o pagamento para esta invoice
                        $aberto->pagamentos()->attach($pagamento->id, ['valor_recebido' => $valorRestante]);
                        $valorRestante = 0;
                    } else {
                        // Não existem mais valores para serem registrados (quebra o foreach)
                        break;
                    }
                }
            }
        }

        // Retorna o valor restante
        return $valorRestante;
    }
}

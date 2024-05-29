<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Despesa;
use App\Models\Fornecedor;
use App\Models\ServicosFornecedor;
use App\Models\DespesaItem;
use App\Models\Pagamento;
use App\Models\Caixa;

class DespesaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validação dos dados do formulário
            $request->validate([
                'data' => 'required|date',
                'fatura_carga_id' => 'required|exists:fatura_cargas,id',
                'fornecedor_id' => 'required|exists:fornecedors,id',
                // Adicione outras regras de validação conforme necessário
            ]);

            // Criação de um novo item no banco de dados
            $despesa = Despesa::create([
                'data' => $request->input('data'),
                'fatura_carga_id' => $request->input('fatura_carga_id'),
                'fornecedor_id' => $request->input('fornecedor_id'),
                // Adicione outros campos conforme necessário
            ]);

            // Exibir toastr de sucesso
            return redirect()->route('despesas.show', ['despesa' => $despesa->id])->with('toastr', [
                'type'    => 'success',
                'message' => 'Despesa criada com sucesso!',
                'title'   => 'Sucesso',
            ]);
        } catch (\Exception $e) {
            // Exibir toastr de Erro
            return redirect()->back()->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao criar a Despesa: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Lógica para mostrar um embarcador específico
        try {
            // Buscar o shipper pelo ID
            $despesa = Despesa::findOrFail($id);
            $all_servicos = ServicosFornecedor::where('fornecedor_id', $despesa->fornecedor_id)->get();
            $all_items = DespesaItem::where('despesa_id', $despesa->id)->get();
            $all_caixas = Caixa::all();
            // Retornar a view com os detalhes do shipper
            return view('admin.despesa.show', compact('despesa', 'all_servicos', 'all_items', 'all_caixas'));
        } catch (\Exception $e) {
            // Exibir uma mensagem de erro ou redirecionar para uma página de erro
            return redirect()->back()->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao exibir os detalhes da Despesa: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $despesa = Despesa::find($id);
            $fatura_carga = $despesa->fatura_carga_id;
            $despesa->delete();

            // Redirecionar após a exclusão bem-sucedida
            return redirect()->route('faturacargas.show', ['faturacarga' => $fatura_carga])->with('toastr', [
                'type'    => 'success',
                'message' => 'Despesa excluída com sucesso!',
                'title'   => 'Sucesso',
            ]);
        } catch (\Exception $e) {
            // Exibir toastr de erro se ocorrer uma exceção
            return redirect()->back()->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao excluir a Despesa: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
    }

    public function distribuirPagamento(Fornecedor $fornecedor, Pagamento $pagamento, Despesa $despesa)
    {
        //Calcula o valor do pagamento para poder distribuir entre as invoices
        $valorRestante = $pagamento->valor;

        // Calcula o valor em ABERTO da Invoice
        $saldoAberto = $despesa->valor_total() - $despesa->valor_pago();

        // Verificar se o valor do pagamento pode pagar totalmente a invoice atual
        if ($valorRestante <= $saldoAberto) {
            $despesa->pagamentos()->attach($pagamento->id, ['valor_recebido' => $valorRestante]);
        // Se o valor do pagamento for maior que o da despesa atual distribui entre invoices!
        } else {
            $despesa->pagamentos()->attach($pagamento->id, ['valor_recebido' => $saldoAberto]);
            $valorRestante -= $saldoAberto;

            // Filtra TODAS as invoices que tem valores em aberto
            $despesasEmAberto = $fornecedor->invoices()->get()->filter(function ($despesa) {
                return $despesa->valor_pago() < $despesa->valor_total();
            });

            foreach ($despesasEmAberto as $aberto) {
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

        // Filtra TODAS as DESPESAS que tem valores em aberto
        // $despesasEmAberto = $fornecedor->despesas()->get()->filter(function ($despesa) {
        //     return $despesa->valor_pago() < $despesa->valor_total();
        // });

        // $valorRestante = $pagamento->valor;

        // // Distribuir o valor pago, entre as DESPESAS ABERTAS
        // $despesasEmAberto = $despesasEmAberto->sortByDesc('data');

        // foreach ($despesasEmAberto as $aberto) {
        //     // Calcula o valor em ABERTO da DESPESAS
        //     $saldoAberto = $aberto->valor_total() - $aberto->valor_pago();

        //     // Verificar se o valor restante pode pagar totalmente a DESPESAS atual
        //     if ($valorRestante >= $saldoAberto) {
        //         // O valor pago é suficiente para pagar totalmente esta DESPESAS
        //         $valorRestante -= $saldoAberto;
        //         // Atualiza a coluna da DESPESAS com o pagamento
        //         // Registrar o pagamento para esta DESPESAS
        //         $aberto->pagamentos()->attach($pagamento->id, ['valor_recebido' => $saldoAberto]);

        //     } else {
        //         if ($valorRestante > 0) {
        //             // Atualiza a coluna da DESPESAS com o pagamento do valor RESTANTE (o que sobrou dos pagamentos)
        //             // Registrar o pagamento para esta DESPESAS
        //             $aberto->pagamentos()->attach($pagamento->id, ['valor_recebido' => $valorRestante]);
        //             $valorRestante = 0;
        //         } else {
        //             // Não existem mais valores para serem registrados (quebra o foreach)
        //             break;
        //         }
        //     }
        // }

        // Retorna o valor restante
        return $valorRestante;
    }
}

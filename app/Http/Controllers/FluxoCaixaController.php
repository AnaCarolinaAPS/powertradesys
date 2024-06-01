<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FluxoCaixa;
use App\Models\FechamentoCaixa;
use App\Models\Caixa;
use App\Models\Categoria;
use Illuminate\Support\Facades\DB;

class FluxoCaixaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $all_items = DB::table('caixas')
                    ->select('caixas.id', 'caixas.nome', 'caixas.moeda',
                        DB::raw('COALESCE(SUM(CASE WHEN fluxo_caixas.tipo = "entrada" THEN fluxo_caixas.valor_origem ELSE 0 END), 0) as saldo_entrada'),
                        DB::raw('COALESCE(SUM(CASE WHEN fluxo_caixas.tipo = "saida" THEN fluxo_caixas.valor_origem ELSE 0 END), 0) as saldo_saida'),
                        DB::raw('COALESCE(SUM(CASE WHEN fluxo_caixas.caixa_origem_id = caixas.id AND fluxo_caixas.tipo IN ("cambio", "transferencia") THEN fluxo_caixas.valor_origem ELSE 0 END), 0) as saida_transacao'),
                        DB::raw('COALESCE(SUM(CASE WHEN fluxo_caixas.caixa_destino_id = caixas.id THEN fluxo_caixas.valor_destino ELSE 0 END), 0) as entrada_transacao')
                    )
                    ->leftJoin('fluxo_caixas', function ($join) {
                        $join->on('caixas.id', '=', 'fluxo_caixas.caixa_origem_id')
                            ->orOn('caixas.id', '=', 'fluxo_caixas.caixa_destino_id');
                    })
                    ->groupBy('caixas.id', 'caixas.nome', 'caixas.moeda')
                    ->get();
        return view('admin.fluxo.index', compact('all_items'));
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $fluxo = FluxoCaixa::findOrFail($id);
        return response()->json($fluxo);
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
                'descricao' => 'nullable|required_if:tipo,entrada,saida|string|max:255',
                'tipo' => 'required|in:entrada,saida,transferencia,cambio',
                'categoria_id' => 'required_if:tipo,entrada,saida|exists:categorias,id',
                'subcategoria_id' => 'required_if:tipo,entrada,saida|exists:categorias,id',
                'caixa_origem_id' => 'required|exists:caixas,id',
                'valor_origem' => 'required|numeric',
                'caixa_destino_id' => 'required_if:tipo,transferencia,cambio|exists:caixas,id',
                'valor_destino' => 'required_if:tipo,cambio|numeric',
                'fechamento_caixa_id' => 'required|exists:fechamento_caixas,id',
                // Adicione outras regras de validação conforme necessário
            ]);

            if ($request->input('tipo') == 'entrada') {
                if ($request->input('valor_origem') < 0) {
                    $valor_origem = $request->input('valor_origem')*-1;
                } else {
                    $valor_origem = $request->input('valor_origem');
                }
                //obrigatoriamente tem categoria e subcategoria
                $fluxo = FluxoCaixa::create([
                    'data' => $request->input('data'),
                    'descricao' => $request->input('descricao'),
                    'tipo' => $request->input('tipo'),
                    'caixa_origem_id' => $request->input('caixa_origem_id'),
                    'valor_origem' => $valor_origem,
                    'categoria_id' => $request->input('categoria_id'),
                    'subcategoria_id' => $request->input('subcategoria_id'),
                    'fechamento_caixa_id' => $request->input('fechamento_caixa_id'),
                    // Adicione outros campos conforme necessário
                ]);

                //atualiza fechamento
                $fechamento = FechamentoCaixa::findOrFail($request->input('fechamento_caixa_id'));
                // $fechamento->atualizaSaldo($valor_origem);
            } else if ($request->input('tipo') == 'saida') {
                if ($request->input('valor_origem') < 0) {
                    $valor_origem = $request->input('valor_origem');
                } else {
                    $valor_origem = $request->input('valor_origem')*-1;
                }
                //obrigatoriamente tem categoria e subcategoria
                $fluxo = FluxoCaixa::create([
                    'data' => $request->input('data'),
                    'descricao' => $request->input('descricao'),
                    'tipo' => $request->input('tipo'),
                    'caixa_origem_id' => $request->input('caixa_origem_id'),
                    'valor_origem' => $valor_origem,
                    'categoria_id' => $request->input('categoria_id'),
                    'subcategoria_id' => $request->input('subcategoria_id'),
                    'fechamento_caixa_id' => $request->input('fechamento_caixa_id'),
                    // Adicione outros campos conforme necessário
                ]);

                //atualiza fechamento
                $fechamento = FechamentoCaixa::findOrFail($request->input('fechamento_caixa_id'));
                // $fechamento->atualizaSaldo($valor_origem);
            } else if ($request->input('tipo') == 'transferencia') {
                if ($request->input('valor_origem') < 0) {
                    $valor_origem = $request->input('valor_origem');
                } else {
                    $valor_origem = $request->input('valor_origem')*-1;
                }

                if ($request->input('valor_destino') < 0) {
                    $valor_destino = $request->input('valor_origem')*-1;
                } else {
                    $valor_destino = $request->input('valor_origem');
                }

                $caixa_origem = Caixa::findOrFail($request->input('caixa_origem_id'));
                $caixa_destino = Caixa::findOrFail($request->input('caixa_destino_id'));
                $descricao = "Transferencia: ".$caixa_origem->nome." -> ".$caixa_destino->nome;
                // $descricao = "";
                //Cria um registro em "origem"
                $fluxo = FluxoCaixa::create([
                    'data' => $request->input('data'),
                    'descricao' => $descricao,
                    'tipo' => $request->input('tipo'),
                    'caixa_origem_id' => $request->input('caixa_origem_id'),
                    'valor_origem' => $valor_origem,
                    'caixa_destino_id' => $request->input('caixa_destino_id'),
                    'valor_destino' => $valor_destino,
                    'fechamento_caixa_id' => $request->input('fechamento_caixa_id'),
                    // Adicione outros campos conforme necessário
                ]);
                //atualiza fechamento
                $fechamento = FechamentoCaixa::findOrFail($request->input('fechamento_caixa_id'));
                // $fechamento->atualizaSaldo($valor_origem);

                //Data retira Mês e Ano para buscar o fechamando do caixa de DESTINO
                $data = \Carbon\Carbon::createFromFormat('Y-m-d', $request->input('data'));
                $start_date = $data->startOfWeek(\Carbon\Carbon::SUNDAY)->format('Y-m-d');
                $end_date = $data->endOfWeek(\Carbon\Carbon::SATURDAY)->format('Y-m-d');
                $fechamentoDestino = FechamentoCaixa::where('caixa_id', $request->input('caixa_destino_id'))->where('start_date', $start_date)->where('end_date', $end_date)->firstOrFail();

                //Cria um registro em "destino"
                $fluxo = FluxoCaixa::create([
                    'data' => $request->input('data'),
                    'descricao' => $descricao,
                    'tipo' => $request->input('tipo'),
                    'caixa_origem_id' => $request->input('caixa_origem_id'),
                    'valor_origem' => $valor_origem,
                    'caixa_destino_id' => $request->input('caixa_destino_id'),
                    'valor_destino' => $valor_destino,
                    'fechamento_caixa_id' => $fechamentoDestino->id,
                    // Adicione outros campos conforme necessário
                ]);

                // $fechamentoDestino->atualizaSaldo($valor_destino);
            } else if ($request->input('tipo') == 'cambio') {
                //obrigatoriamente tem caixa_destino_id e o valor é DIFERENTE
                if ($request->input('valor_origem') < 0) {
                    $valor_origem = $request->input('valor_origem');
                } else {
                    $valor_origem = $request->input('valor_origem')*-1;
                }

                if ($request->input('valor_destino') < 0) {
                    $valor_destino = $request->input('valor_destino')*-1;
                } else {
                    $valor_destino = $request->input('valor_destino');
                }

                $caixa_origem = Caixa::findOrFail($request->input('caixa_origem_id'));
                $caixa_destino = Caixa::findOrFail($request->input('caixa_destino_id'));
                $descricao = "Cambio: ".$valor_origem.$caixa_origem->moeda." ".$caixa_origem->nome." -> ".$caixa_destino->nome;
                // $descricao = "";
                //Cria um registro em "origem"
                $fluxo = FluxoCaixa::create([
                    'data' => $request->input('data'),
                    'descricao' => $descricao,
                    'tipo' => $request->input('tipo'),
                    'caixa_origem_id' => $request->input('caixa_origem_id'),
                    'valor_origem' => $valor_origem,
                    'caixa_destino_id' => $request->input('caixa_destino_id'),
                    'valor_destino' => $valor_destino,
                    'fechamento_caixa_id' => $request->input('fechamento_caixa_id'),
                    // Adicione outros campos conforme necessário
                ]);
                //atualiza fechamento
                $fechamento = FechamentoCaixa::findOrFail($request->input('fechamento_caixa_id'));
                // $fechamento->atualizaSaldo($valor_origem);

                //Data retira Mês e Ano para buscar o fechamando do caixa de DESTINO
                $data = \Carbon\Carbon::createFromFormat('Y-m-d', $request->input('data'));
                $start_date = $data->startOfWeek(\Carbon\Carbon::SUNDAY)->format('Y-m-d');
                $end_date = $data->endOfWeek(\Carbon\Carbon::SATURDAY)->format('Y-m-d');
                $fechamentoDestino = FechamentoCaixa::where('caixa_id', $request->input('caixa_destino_id'))->where('start_date', $start_date)->where('end_date', $end_date)->firstOrFail();

                //Cria um registro em "destino"
                $fluxo = FluxoCaixa::create([
                    'data' => $request->input('data'),
                    'descricao' => $descricao,
                    'tipo' => $request->input('tipo'),
                    'caixa_origem_id' => $request->input('caixa_origem_id'),
                    'valor_origem' => $valor_origem,
                    'caixa_destino_id' => $request->input('caixa_destino_id'),
                    'valor_destino' => $valor_destino,
                    'fechamento_caixa_id' => $fechamentoDestino->id,
                    // Adicione outros campos conforme necessário
                ]);

                // $fechamentoDestino->atualizaSaldo($valor_destino);
            }
            // Exibir toastr de sucesso
            return redirect()->back()->with('toastr', [
                'type'    => 'success',
                'message' => 'Transação criada com sucesso!',
                'title'   => 'Sucesso',
            ]);
        } catch (\Exception $e) {
            // Exibir toastr de Erro
            return redirect()->back()->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao criar a Transação: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FluxoCaixa $fluxocaixa)
    {
        try {

            // Validação dos dados do formulário
            $request->validate([
                'data' => 'required|date',
                'descricao' => 'nullable|required_if:tipo,entrada,saida|string|max:255',
                'categoria_id' => 'required_if:tipo,entrada,saida|exists:categorias,id',
                'subcategoria_id' => 'required_if:tipo,entrada,saida|exists:categorias,id',
                // Adicione outras regras de validação conforme necessário
            ]);

            // Atualizar os dados
            $fluxocaixa->update([
                'data' => $request->input('data'),
                'descricao' => $request->input('descricao'),
                'categoria_id' => $request->input('categoria_id'),
                'subcategoria_id' => $request->input('subcategoria_id'),
                // Adicione outros campos conforme necessário
            ]);

            // Exibir toastr de sucesso
            return redirect()->back()->with('toastr', [
                'type'    => 'success',
                'message' => 'Registro atualizado com sucesso!',
                'title'   => 'Sucesso',
            ]);
        } catch (\Exception $e) {
            // Exibir toastr de Erro
            return redirect()->back()->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao atualizar o Registro: <br>'. $e->getMessage(),
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
            $fluxocaixa = FluxoCaixa::find($id);

            //Encontra a qual fechamento o registro pertence
            $fechamento = FechamentoCaixa::findOrFail($fluxocaixa->fechamento_caixa_id);
            //Troca o valor + ou - do valor_origem para ajustar o saldo e Atualiza
            // $fechamento->atualizaSaldo($fluxocaixa->valor_origem*-1);

            if ($fluxocaixa->tipo == 'transferencia' || $fluxocaixa->tipo == 'cambio') {
                //Busca a copia da transferencia / cambio
                $fluxo_destino = FluxoCaixa::whereNot('fechamento_caixa_id', $fluxocaixa->fechamento_caixa_id)
                                            ->where('descricao', $fluxocaixa->descricao)
                                            ->where('caixa_origem_id', $fluxocaixa->caixa_origem_id)
                                            ->where('valor_origem', $fluxocaixa->valor_origem)
                                            ->where('caixa_destino_id', $fluxocaixa->caixa_destino_id)
                                            ->where('valor_destino', $fluxocaixa->valor_destino)
                                            ->where('data', $fluxocaixa->data)
                                            ->where('created_at', $fluxocaixa->created_at)
                                            ->where('updated_at', $fluxocaixa->updated_at)
                                            ->where('id', '!=', $fluxocaixa->id)
                                            ->first();

                $fechamento_destino = FechamentoCaixa::findOrFail($fluxo_destino->fechamento_caixa_id);
                //Troca o valor + ou - do valor_origem para ajustar o saldo e Atualiza
                // $fechamento_destino->atualizaSaldo($fluxo_destino->valor_destino*-1);
                $fluxo_destino->delete();
            }

            if ($fluxocaixa->tipo == 'entrada' && $fluxocaixa->categoria_id == null) {
                //veio de pagamento de cliente
            }

            // Excluir o Freteiro do banco de dados
            $fluxocaixa->delete();

            // Redirecionar após a exclusão bem-sucedida
            return redirect()->back()->with('toastr', [
                'type'    => 'success',
                'message' => 'Freteiro excluído com sucesso!',
                'title'   => 'Sucesso',
            ]);
        } catch (\Exception $e) {
            // Exibir toastr de erro se ocorrer uma exceção
            return redirect()->back()->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao excluir o Freteiro: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
    }

}

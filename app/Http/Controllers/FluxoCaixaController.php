<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FluxoCaixa;
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
        // $all_items = DB::table('caixas')
        //             ->select('caixas.id', 'caixas.nome', 'caixas.moeda',
        //                     DB::raw('COALESCE(SUM(entradas.valor_origem), 0) as total_entradas'),
        //                     DB::raw('COALESCE(SUM(saidas.valor_origem), 0) as total_saidas'),
        //                     DB::raw('(COALESCE(SUM(entradas.valor_origem), 0) + COALESCE(SUM(saidas.valor_origem    ), 0)) as saldo'))
        //             ->leftJoin('fluxo_caixas as entradas', function($join) {
        //                 $join->on('caixas.id', '=', 'entradas.caixa_origem_id')
        //                     ->where('entradas.tipo', '=', 'entrada');
        //             })
        //             ->leftJoin('fluxo_caixas as saidas', function($join) {
        //                 $join->on('caixas.id', '=', 'saidas.caixa_origem_id')
        //                     ->where('saidas.tipo', '=', 'saida');
        //             })
        //             ->groupBy('caixas.id', 'caixas.nome', 'caixas.moeda')
        //             ->get();

        // $all_items = DB::table('caixas')
        //             ->select('caixas.id', 'caixas.nome', 'caixas.moeda',
        //                     DB::raw('COALESCE(SUM(fluxo_caixas.valor_origem), 0) as saldo'),
        //             )->leftJoin('fluxo_caixas', 'caixas.id', '=', 'fluxo_caixas.caixa_origem_id')
        //             ->groupBy('caixas.id', 'caixas.nome', 'caixas.moeda')
        //             ->get();

        $all_items = DB::table('caixas')
                        ->select('caixas.id', 'caixas.nome', 'caixas.moeda',
                            DB::raw('COALESCE(SUM(fluxo_caixas.valor_origem), 0) as saldo_origem'),
                            DB::raw('COALESCE(SUM(fluxo_caixas.valor_destino), 0) as saldo_destino')
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
        try {
            // Buscar o shipper pelo ID
            $caixa = Caixa::findOrFail($id);
            $all_items = FluxoCaixa::where('caixa_origem_id', $id)->orWhere('caixa_destino_id', $id)->get();
            $all_categorias = Categoria::where('tipo', 'categoria')
                            ->get();
            $all_subcategorias = Categoria::where('tipo', 'subcategoria')
                            ->get();
            $all_caixas = Caixa::all();

            // Retornar a view com os detalhes do shipper
            return view('admin.fluxo.show', compact('caixa', 'all_items', 'all_categorias', 'all_subcategorias', 'all_caixas'));
        } catch (\Exception $e) {
            // Exibir uma mensagem de erro ou redirecionar para uma página de erro
            return redirect()->back()->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao exibir os detalhes da Caixa: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
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
                'descricao' => 'required|string|max:255',
                'tipo' => 'required|in:entrada,saida,transferencia,cambio',
                'categoria_id' => 'required_if:tipo,entrada,saida|exists:categorias,id',
                'subcategoria_id' => 'required_if:tipo,entrada,saida|exists:categorias,id',
                'caixa_origem_id' => 'required|exists:caixas,id',
                'valor_origem' => 'required|numeric',
                'caixa_destino_id' => 'required_if:tipo,transferencia,cambio|exists:caixas,id',
                'valor_destino' => 'required_if:tipo,cambio|numeric',
                // Adicione outras regras de validação conforme necessário
            ]);

            if ($request->input('tipo') == 'entrada') {
                if ($request->input('valor_origem') < 0) {
                    $valor_origem = $request->input('valor_origem')*-1;
                } else {
                    $valor_origem = $request->input('valor_origem');
                }
                //obrigatoriamente tem categoria e subcategoria
                FluxoCaixa::create([
                    'data' => $request->input('data'),
                    'descricao' => $request->input('descricao'),
                    'tipo' => $request->input('tipo'),
                    'caixa_origem_id' => $request->input('caixa_origem_id'),
                    'valor_origem' => $valor_origem,
                    'categoria_id' => $request->input('categoria_id'),
                    'subcategoria_id' => $request->input('subcategoria_id'),
                    // Adicione outros campos conforme necessário
                ]);
            } else if ($request->input('tipo') == 'saida') {
                if ($request->input('valor_origem') < 0) {
                    $valor_origem = $request->input('valor_origem');
                } else {
                    $valor_origem = $request->input('valor_origem')*-1;
                }
                //obrigatoriamente tem categoria e subcategoria
                FluxoCaixa::create([
                    'data' => $request->input('data'),
                    'descricao' => $request->input('descricao'),
                    'tipo' => $request->input('tipo'),
                    'caixa_origem_id' => $request->input('caixa_origem_id'),
                    'valor_origem' => $valor_origem,
                    'categoria_id' => $request->input('categoria_id'),
                    'subcategoria_id' => $request->input('subcategoria_id'),
                    // Adicione outros campos conforme necessário
                ]);
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
                //obrigatoriamente tem caixa_destino_id
                FluxoCaixa::create([
                    'data' => $request->input('data'),
                    'descricao' => $request->input('descricao'),
                    'tipo' => $request->input('tipo'),
                    'caixa_origem_id' => $request->input('caixa_origem_id'),
                    'valor_origem' => $valor_origem,
                    'caixa_destino_id' => $request->input('caixa_destino_id'),
                    'valor_destino' => $valor_destino,
                    // Adicione outros campos conforme necessário
                ]);
            } else if ($request->input('tipo') == 'cambio') {
                //obrigatoriamente tem caixa_destino_id e o valor é DIFERENTE
                FluxoCaixa::create([
                    'data' => $request->input('data'),
                    'descricao' => $request->input('descricao'),
                    'tipo' => $request->input('tipo'),
                    'caixa_origem_id' => $request->input('caixa_origem_id'),
                    'valor_origem' => $request->input('valor_origem'),
                    'caixa_destino_id' => $request->input('caixa_destino_id'),
                    'valor_destino' => $request->input('valor_destino'),
                    // Adicione outros campos conforme necessário
                ]);
            }

            // Exibir toastr de sucesso
            return redirect()->back()->with('toastr', [
                'type'    => 'success',
                'message' => 'Transação criada com sucesso!',
                'title'   => 'Sucesso',
            ]);
        } catch (\Exception $e) {
            // Exibir toastr de Erro
            return redirect()->route('fluxo_caixa.index')->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao criar a Transação: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
    }
}

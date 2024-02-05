<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FluxoCaixa;
use App\Models\Caixa;
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
                            DB::raw('COALESCE(SUM(entradas.valor_origem), 0) as total_entradas'),
                            DB::raw('COALESCE(SUM(saidas.valor_origem), 0) as total_saidas'),
                            DB::raw('(COALESCE(SUM(entradas.valor_origem), 0) - COALESCE(SUM(saidas.valor_origem    ), 0)) as saldo'))
                    ->leftJoin('fluxo_caixas as entradas', function($join) {
                        $join->on('caixas.id', '=', 'entradas.caixa_origem_id')
                            ->where('entradas.tipo', '=', 'entrada');
                    })
                    ->leftJoin('fluxo_caixas as saidas', function($join) {
                        $join->on('caixas.id', '=', 'saidas.caixa_origem_id')
                            ->where('saidas.tipo', '=', 'saida');
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

            // Retornar a view com os detalhes do shipper
            return view('admin.fluxo.show', compact('caixa', 'all_items'));
        } catch (\Exception $e) {
            // Exibir uma mensagem de erro ou redirecionar para uma página de erro
            return redirect()->back()->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao exibir os detalhes da Caixa: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
    }
}

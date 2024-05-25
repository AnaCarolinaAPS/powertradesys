<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FechamentoCaixa;
use App\Models\FluxoCaixa;
use App\Models\Categoria;
use App\Models\Caixa;
use Illuminate\Support\Facades\DB;

class FechamentoCaixaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $all_items = DB::table('caixas')
        //             ->select('caixas.id', 'caixas.nome', 'caixas.moeda',
        //                 DB::raw('COALESCE(SUM(CASE WHEN fluxo_caixas.tipo = "entrada" THEN fluxo_caixas.valor_origem ELSE 0 END), 0) as saldo_entrada'),
        //                 DB::raw('COALESCE(SUM(CASE WHEN fluxo_caixas.tipo = "saida" THEN fluxo_caixas.valor_origem ELSE 0 END), 0) as saldo_saida'),
        //                 DB::raw('COALESCE(SUM(CASE WHEN fluxo_caixas.caixa_origem_id = caixas.id AND fluxo_caixas.tipo IN ("cambio", "transferencia") THEN fluxo_caixas.valor_origem ELSE 0 END), 0) as saida_transacao'),
        //                 DB::raw('COALESCE(SUM(CASE WHEN fluxo_caixas.caixa_destino_id = caixas.id THEN fluxo_caixas.valor_destino ELSE 0 END), 0) as entrada_transacao')
        //             )
        //             ->leftJoin('fluxo_caixas', function ($join) {
        //                 $join->on('caixas.id', '=', 'fluxo_caixas.caixa_origem_id')
        //                     ->orOn('caixas.id', '=', 'fluxo_caixas.caixa_destino_id');
        //             })
        //             ->groupBy('caixas.id', 'caixas.nome', 'caixas.moeda')
        //             ->get();
        $all_items = FechamentoCaixa::all();
        $all_caixas = Caixa::all();
        return view('admin.fechamentocaixa.index', compact('all_items', 'all_caixas'));
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            // Buscar o item pelo ID
            $fechamento = FechamentoCaixa::findOrFail($id);
            $all_items = FluxoCaixa::where('fechamento_caixa_id', $id)->orderBy('data', 'desc')->get();
            $all_categorias = Categoria::where('tipo', 'categoria')
                            ->get();
            $all_subcategorias = Categoria::where('tipo', 'subcategoria')
                            ->get();
            $all_caixas = Caixa::where('id', '!=', $id)->get();//Caixa::all();

            $soma_categorias = FluxoCaixa::select('categoria_id', DB::raw('SUM(valor_origem) as total_saida'))
                            ->where('tipo', 'saida')
                            ->where('fechamento_caixa_id', $id)
                            ->groupBy('categoria_id')
                            ->get();

            // Forma arrays para montagem do gráfico:
            // Inicializar arrays para armazenar os dados do gráfico
            $labels = [];
            $data = [];
            $backgroundColor = [];
            $borderColor = [];

            // Iterar sobre os resultados da consulta
            foreach ($soma_categorias as $categoria) {
                // Adicionar categoria_id como label
                $labels[] = $categoria->categoria->nome;
                // Adicionar total_saida como dado
                $data[] = $categoria->total_saida;
                // Gerar cores aleatórias para o gráfico
                $red = mt_rand(0, 255);
                $green = mt_rand(0, 255);
                $blue = mt_rand(0, 255);
                $backgroundColor[] = "rgba($red, $green, $blue, 0.5)";
                $borderColor[] = "rgba($red, $green, $blue, 1)";
            }

            // Criar um array associativo com todas as informações
            $data_grafico = [
                'labels' => $labels,
                'data' => $data,
                'backgroundColor' => $backgroundColor,
                'borderColor' => $borderColor
            ];

            $soma_subcategorias = FluxoCaixa::select('categoria_id', 'subcategoria_id', DB::raw('SUM(valor_origem) as total_saida'))
                            ->where('tipo', 'saida')
                            ->where('fechamento_caixa_id', $id)
                            ->groupBy('categoria_id', 'subcategoria_id')
                            ->get();

            // Forma arrays para montagem do gráfico:
            // Inicializar arrays para armazenar os dados do gráfico
            $labels_sub = [];
            $data_sub = [];
            $backgroundColor_sub = [];
            $borderColor_sub = [];

            // Iterar sobre os resultados da consulta
            foreach ($soma_subcategorias as $categoria) {
                // Adicionar categoria_id como label
                $labels_sub[] = $categoria->categoria->nome . " - " . $categoria->subcategoria->nome;
                // Adicionar total_saida como dado
                $data_sub[] = $categoria->total_saida;
                // Gerar cores aleatórias para o gráfico
                $red = mt_rand(0, 255);
                $green = mt_rand(0, 255);
                $blue = mt_rand(0, 255);
                $backgroundColor_sub[] = "rgba($red, $green, $blue, 0.5)";
                $borderColor_sub[] = "rgba($red, $green, $blue, 1)";
            }

            // Criar um array associativo com todas as informações
            $data_grafico_sub = [
                'labels' => $labels_sub,
                'data' => $data_sub,
                'backgroundColor' => $backgroundColor_sub,
                'borderColor' => $borderColor_sub
            ];

            // Retornar a view com os detalhes do shipper
            return view('admin.fechamentocaixa.show', compact('fechamento', 'all_items', 'all_categorias', 'all_subcategorias', 'all_caixas', 'data_grafico', 'data_grafico_sub'));
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
                'caixa_id' => 'required|exists:caixas,id',
                'saldo_inicial' => 'required|numeric',
                // Adicione outras regras de validação conforme necessário
            ]);

            // Converter a data para um objeto Carbon
            $dataCarbon = \Carbon\Carbon::parse($request->input('data'));

            // Extrair o mês e o ano da data
            // $mes = $dataCarbon->format('m'); // Obtém o número do mês (01 para janeiro, 02 para fevereiro, etc.)
            // $ano = $dataCarbon->format('Y'); // Obtém o ano (ex: 2024)

            // Calcular o domingo (início da semana) e o sábado (final da semana)
            $start_date = $dataCarbon->startOfWeek(\Carbon\Carbon::SUNDAY)->format('Y-m-d');
            $end_date = $dataCarbon->endOfWeek(\Carbon\Carbon::SATURDAY)->format('Y-m-d');

            // Criação de um novo Freteiro no banco de dados
            FechamentoCaixa::create([
                // 'ano' => $ano,
                // 'mes' => $mes,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'caixa_id' => $request->input('caixa_id'),
                'saldo_inicial' => $request->input('saldo_inicial'),
                'saldo_final' => $request->input('saldo_inicial'),
                // Adicione outros campos conforme necessário
            ]);

            // Exibir toastr de sucesso
            return redirect()->route('registro_caixa.index')->with('toastr', [
                'type'    => 'success',
                'message' => 'Registro de Caixa criado com sucesso!',
                'title'   => 'Sucesso',
            ]);
        } catch (\Exception $e) {
            // Exibir toastr de Erro
            return redirect()->route('registro_caixa.index')->with('toastr', [
                'type'    => 'error',
                'message' => 'Ocorreu um erro ao criar o Registro de Caixa: <br>'. $e->getMessage(),
                'title'   => 'Erro',
            ]);
        }
    }
}

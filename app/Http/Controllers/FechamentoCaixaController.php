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
    public function index($tipo = null)
    {
        $all_caixas = Caixa::all();

        $totalSaldoUS = null;
        $totalSaldoRS = null;
        $totalSaldoGS = null;

        if ($tipo == 'all') {
            $all_items = FechamentoCaixa::all();
        } else {
            $totalSaldoUS = 0;
            $totalSaldoRS = 0;
            $totalSaldoGS = 0;
            // Para cada caixa, buscar a última entrada de FechamentoCaixa
            foreach ($all_caixas as $caixa) {
                $fechamento = FechamentoCaixa::where('caixa_id', $caixa->id)
                    ->orderBy('start_date', 'desc')->with('caixa')
                    ->first();
                if ($fechamento) {
                    $all_items[] = $fechamento;
                }

                if ($fechamento->caixa->moeda === 'U$') {
                    $totalSaldoUS += $fechamento->calculaSaldo();
                } else if ($fechamento->caixa->moeda === 'G$') {
                    $totalSaldoGS += $fechamento->calculaSaldo();
                } else {
                    $totalSaldoRS += $fechamento->calculaSaldo();
                }
            }
        }
        // $all_items = FechamentoCaixa::all();
        return view('admin.fechamentocaixa.index', compact('all_items', 'all_caixas', 'totalSaldoUS', 'totalSaldoGS', 'totalSaldoRS'));
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            // Buscar o item pelo ID
            $fechamento = FechamentoCaixa::findOrFail($id);
            $all_items = FluxoCaixa::where('fechamento_origem_id', $id)->orWhere('fechamento_destino_id', $id)->get();
            $all_categorias = Categoria::where('tipo', 'categoria')
                            ->get();
            $all_subcategorias = Categoria::where('tipo', 'subcategoria')
                            ->get();
            //caixas para transação
            $all_caixas_t = Caixa::where('id', '!=', $fechamento->caixa->id)->where('moeda', '=', $fechamento->caixa->moeda)->get();
            //caixas para cambio
            $all_caixas_c = Caixa::where('id', '!=', $fechamento->caixa->id)->where('moeda', '!=', $fechamento->caixa->moeda)->get();

            // $soma_categorias = FluxoCaixa::select('categoria_id', DB::raw('SUM(valor_origem) as total_saida'))
            //                 ->where('tipo', 'saida')
            //                 ->where('fechamento_origem_id', $id)
            //                 ->groupBy('categoria_id')
            //                 ->get();

            // // Forma arrays para montagem do gráfico:
            // // Inicializar arrays para armazenar os dados do gráfico
            // $labels = [];
            // $data = [];
            // $backgroundColor = [];
            // $borderColor = [];

            // // Iterar sobre os resultados da consulta
            // foreach ($soma_categorias as $categoria) {
            //     // Adicionar categoria_id como label
            //     $labels[] = $categoria->categoria->nome;
            //     // Adicionar total_saida como dado
            //     $data[] = $categoria->total_saida;
            //     // Gerar cores aleatórias para o gráfico
            //     $red = mt_rand(0, 255);
            //     $green = mt_rand(0, 255);
            //     $blue = mt_rand(0, 255);
            //     $backgroundColor[] = "rgba($red, $green, $blue, 0.5)";
            //     $borderColor[] = "rgba($red, $green, $blue, 1)";
            // }

            // // Criar um array associativo com todas as informações
            // $data_grafico = [
            //     'labels' => $labels,
            //     'data' => $data,
            //     'backgroundColor' => $backgroundColor,
            //     'borderColor' => $borderColor
            // ];

            // $soma_subcategorias = FluxoCaixa::select('categoria_id', 'subcategoria_id', DB::raw('SUM(valor_origem) as total_saida'))
            //                 ->where('tipo', 'saida')
            //                 ->where('fechamento_origem_id', $id)
            //                 ->groupBy('categoria_id', 'subcategoria_id')
            //                 ->get();

            // // Forma arrays para montagem do gráfico:
            // // Inicializar arrays para armazenar os dados do gráfico
            // $labels_sub = [];
            // $data_sub = [];
            // $backgroundColor_sub = [];
            // $borderColor_sub = [];

            // // Iterar sobre os resultados da consulta
            // foreach ($soma_subcategorias as $categoria) {
            //     // Adicionar categoria_id como label
            //     $labels_sub[] = $categoria->categoria->nome . " - " . $categoria->subcategoria->nome;
            //     // Adicionar total_saida como dado
            //     $data_sub[] = $categoria->total_saida;
            //     // Gerar cores aleatórias para o gráfico
            //     $red = mt_rand(0, 255);
            //     $green = mt_rand(0, 255);
            //     $blue = mt_rand(0, 255);
            //     $backgroundColor_sub[] = "rgba($red, $green, $blue, 0.5)";
            //     $borderColor_sub[] = "rgba($red, $green, $blue, 1)";
            // }

            // // Criar um array associativo com todas as informações
            // $data_grafico_sub = [
            //     'labels' => $labels_sub,
            //     'data' => $data_sub,
            //     'backgroundColor' => $backgroundColor_sub,
            //     'borderColor' => $borderColor_sub
            // ];

            $data_grafico = [];
            $data_grafico_sub = [];
            // Retornar a view com os detalhes do shipper
            return view('admin.fechamentocaixa.show', compact('fechamento', 'all_items', 'all_categorias', 'all_subcategorias', 'all_caixas_t', 'all_caixas_c', 'data_grafico', 'data_grafico_sub'));
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
            // Calcular o domingo (início da semana) e o sábado (final da semana)
            $start_date = $dataCarbon->startOfWeek(\Carbon\Carbon::SUNDAY)->format('Y-m-d');
            $end_date = $dataCarbon->endOfWeek(\Carbon\Carbon::SATURDAY)->format('Y-m-d');

            $fechamentoExiste = FechamentoCaixa::where('start_date', $start_date)->where('end_date', $end_date)->where('caixa_id', $request->input('caixa_id'))->first();

            if ($fechamentoExiste) {
                return redirect()->route('registro_caixa.show', ['fechamento' => $fechamentoExiste->id])->with('toastr', [
                    'type'    => 'warning',
                    'message' => 'O Registro de Caixa já existe <br>',
                    'title'   => 'Erro',
                ]);
            }

            // Criação de um novo Freteiro no banco de dados
            $fechamento = FechamentoCaixa::create([
                'start_date' => $start_date,
                'end_date' => $end_date,
                'caixa_id' => $request->input('caixa_id'),
                'saldo_inicial' => $request->input('saldo_inicial'),
                'saldo_final' => $request->input('saldo_inicial'),
                // Adicione outros campos conforme necessário
            ]);

            // Exibir toastr de sucesso
            return redirect()->route('registro_caixa.show', ['fechamento' => $fechamento->id])->with('toastr', [
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

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\FaturaCarga;
use App\Models\Carga;
use App\Models\FechamentoCaixa;
use App\Models\Caixa;
use App\Models\FluxoCaixa;
use App\Models\Categoria;

class RelatorioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function indexCarga()
    {
        $all_items = FaturaCarga::all();

        $ultimasCinco = FaturaCarga::latest()->take(5)->get()->sortBy('data');

        // Forma arrays para montagem do gráfico:
        // Inicializar arrays para armazenar os dados do gráfico        
        $labels_carga = [];
        $data_carga = [];
        $backgroundColor_valores = [
            'rgba(246, 71, 71, 0.4)', 'rgba(230, 126, 34, 0.4)', 'rgba(104, 195, 163, 0.4)', 'rgba(44, 130, 201, 0.4)', 'rgba(153, 102, 255, 0.4)',
        ];
        $borderColor_valores = [
            'rgba(246, 71, 71, 1)', 'rgba(230, 126, 34, 1)', 'rgba(104, 195, 163, 1)', 'rgba(44, 130, 201, 1)', 'rgba(153, 102, 255, 1)', 
        ];
        foreach ($ultimasCinco as $fatura) {
            // Adicionar categoria_id como label
            $labels_carga[] = \Carbon\Carbon::parse($fatura->carga->data_recebida)->format('d/m/Y');
            // Adicionar total_saida como dado
            $data_carga[] = $fatura->invoices_pesos_orig();
        }   

        // Criar um array associativo com todas as informações
        $data_grafico_mes = [
            'labels' => $labels_carga,
            'data' => $data_carga,
            'backgroundColor' => $backgroundColor_valores,
            'borderColor' => $borderColor_valores
        ];


        // Obter a data de 12 meses atrás
        $startDate = Carbon::now()->subMonths(12)->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();

        // Filtrar FaturaCarga pelos últimos 12 meses
        $faturas = FaturaCarga::whereHas('carga', function ($query) use ($startDate, $endDate) {
            // Filtrar FaturaCarga baseada na data da relação Carga
            $query->whereBetween('data_recebida', [$startDate, $endDate]);
            })
            ->get()
            ->groupBy(function ($fatura) {
                // Agrupar por mês e ano no formato "Janeiro/2025" usando a data da Carga
                return Carbon::parse($fatura->carga->data_recebida)->format('F/Y');
        });

        // Inicializar o array para armazenar os totais
        $labels_ano = [];
        $data_ano = [];
        $backgroundColor_ano = [];
        $borderColor_ano = [];

        // Iterar sobre os grupos para calcular o total de pesos por mês
        foreach ($faturas as $mesAno => $faturasDoMes) {

            $totalPeso = $faturasDoMes->sum(function ($fatura) {
                // Somar o valor de invoices_pesos_orig() para cada FaturaCarga
                return $fatura->invoices_pesos_orig();
            });

            // Armazenar os resultados
            $labels_ano[] = $mesAno;
            $data_ano[] = $totalPeso;
            // Gerar cores aleatórias para o gráfico
            $red = mt_rand(0, 255);
            $green = mt_rand(0, 255);
            $blue = mt_rand(0, 255);
            $backgroundColor_ano[] = "rgba($red, $green, $blue, 0.3)";
            $borderColor_ano[] = "rgba($red, $green, $blue, 1)";
        }

        $data_grafico_ano = [
            'labels' => $labels_ano,
            'data' => $data_ano,
            'backgroundColor' => $backgroundColor_ano,
            'borderColor' => $borderColor_ano
        ];

        return view('admin.relatoriocarga.index', compact('all_items', 'data_grafico_mes', 'data_grafico_ano'));
    }

    public function showCargas($id)
    {
        $faturacarga = FaturaCarga::findOrFail($id);
        //Montagem do gráfico tipo Barra com os clientes e pesos
        // Inicializar arrays para armazenar os dados do gráfico
        $labels_cliente = [];
        $data_cliente = [];
        $backgroundColor_cliente = [];
        $borderColor_cliente = [];

        $invoicesPorPeso = $faturacarga->invoices->sortByDesc(function ($invoice) {
            return $invoice->peso_pacote();
        });        

        // Iterar sobre os resultados da consulta
        foreach ($invoicesPorPeso as $invoice) {
            $label = '('.$invoice->cliente->caixa_postal.') '.$invoice->cliente->apelido;
        
            // Adicionar categoria_id como label
            $labels_cliente[] = $label;//$categoria->categoria->nome . " - " . $categoria->subcategoria->nome;
            // Adicionar total_saida como dado
            $data_cliente[] = $invoice->peso_pacote();
            // Gerar cores aleatórias para o gráfico
            $red = mt_rand(0, 255);
            $green = mt_rand(0, 255);
            $blue = mt_rand(0, 255);
            $backgroundColor_cliente[] = "rgba($red, $green, $blue, 0.5)";
            $borderColor_cliente[] = "rgba($red, $green, $blue, 1)";
        }

        // Criar um array associativo com todas as informações
        $data_grafico_clientes = [
            'labels' => $labels_cliente,
            'data' => $data_cliente,
            'backgroundColor' => $backgroundColor_cliente,
            'borderColor' => $borderColor_cliente
        ];

        // Montagem do gráfico tipo pizza dos valores da carga
        $faltacobrar = $faturacarga->valor_total() - $faturacarga->invoices_pagas();
        $faltapagar = $faturacarga->despesas_total() - $faturacarga->despesas_pagas();
        $cobrado = $faturacarga->invoices_pagas() - $faturacarga->despesas_pagas();
        $despesapaga = $faturacarga->despesas_pagas();

        // Forma arrays para montagem do gráfico:
        // Inicializar arrays para armazenar os dados do gráfico
        $labels_valores = [
            'Falta Cobrar', 'Falta Pagar', 'Cobrado/Lucro', 'Despesa Paga',
        ];
        $data_valores = [
            $faltacobrar, $faltapagar, $cobrado, $despesapaga,
        ];
        $backgroundColor_valores = [
            'rgba(246, 71, 71, 0.5)', 'rgba(230, 126, 34, 0.5)', 'rgba(104, 195, 163, 0.5)', 'rgba(44, 130, 201, 0.5)', 
        ];
        $borderColor_valores = [
            'rgba(246, 71, 71, 1)', 'rgba(230, 126, 34, 1)', 'rgba(104, 195, 163, 1)', 'rgba(44, 130, 201, 1)', 
        ];

        // Criar um array associativo com todas as informações
        $data_grafico_valores = [
            'labels' => $labels_valores,
            'data' => $data_valores,
            'backgroundColor' => $backgroundColor_valores,
            'borderColor' => $borderColor_valores
        ];

        return view('admin.relatoriocarga.show', compact('faturacarga', 'data_grafico_valores', 'data_grafico_clientes'));
    }

    /**
     * Display a listing of the resource.
     */
    public function indexGastos(){
        $conversoes = [
            'U$' => 1,
            'R$' => 1 / 5.85,
            'G$' => 1 / 7950,
        ];

        $baseQuery = FechamentoCaixa::query()
            ->join('fluxo_caixas as transacoes', 'fechamento_caixas.id', '=', 'transacoes.fechamento_origem_id')
            ->join('caixas', 'fechamento_caixas.caixa_id', '=', 'caixas.id');

        // Retorna cada linha com moeda
        $dados = $baseQuery
            ->selectRaw("
                DATE_FORMAT(fechamento_caixas.start_date, '%Y-%m') as mes,
                caixas.moeda as moeda,
                SUM(CASE WHEN transacoes.tipo = 'despesa' THEN transacoes.valor_origem ELSE 0 END) as total_despesas,
                SUM(CASE WHEN transacoes.tipo = 'entrada' THEN transacoes.valor_origem ELSE 0 END) as total_entradas,
                SUM(CASE WHEN transacoes.tipo IN ('saida') THEN transacoes.valor_origem ELSE 0 END) as total_gastos,
                SUM(CASE WHEN transacoes.tipo IN ('salario') THEN transacoes.valor_origem ELSE 0 END) as total_salarios
            ")
            ->groupBy(DB::raw("DATE_FORMAT(fechamento_caixas.start_date, '%Y-%m')"), 'moeda')
            ->get();

        // Consolidar todas moedas por mês (já convertidas para U$)
        $resultado = [];

        foreach ($dados as $linha) {
            $mes = $linha->mes;
            $fator = $conversoes[$linha->moeda] ?? 1;

            $entradas = ($linha->total_entradas ?? 0) * $fator;
            $despesas = ($linha->total_despesas ?? 0) * $fator;
            $gastos = ($linha->total_gastos ?? 0) * $fator;
            $salarios = ($linha->total_salarios ?? 0) * $fator;

            if (!isset($resultado[$mes])) {
                $resultado[$mes] = [
                    'mes' => $mes,
                    'entradas' => 0,
                    'despesas' => 0,
                    'gastos' => 0,
                    'salarios' => 0,
                ];                
            }

            $resultado[$mes]['entradas'] += $entradas;
            $resultado[$mes]['despesas'] += $despesas;
            $resultado[$mes]['gastos'] += $gastos;
            $resultado[$mes]['salarios'] += $salarios;
        }

        // Calcular lucros e saldo final
        foreach ($resultado as &$valores) {
            $valores['lucros'] = $valores['entradas'] + $valores['despesas'];
            $valores['saldo'] = $valores['entradas'] + ($valores['despesas'] + $valores['gastos'] + $valores['salarios'] );
        }

        // Ordenar por mês
        ksort($resultado);

        return view('admin.relatoriogastos.index', ['resultado' => $resultado]);
    }

    /**
     * Display a listing of the resource.
     */
    public function indexGastosMensais(Request $request){
        $ano = $request->input('ano', date('Y'));
        $mes = $request->input('mes', date('n'));

        $dados_us = $this->getRelatorioPorMoeda('U$', $ano, $mes);
        $dados_gs = $this->getRelatorioPorMoeda('G$', $ano, $mes);
        $dados_rs = $this->getRelatorioPorMoeda('R$', $ano, $mes);

        
        $cotacoes = [
            'G$' => 7850.0,
            'R$' => 5.80,
            'U$' => 1.0,
        ];

        $gastos_combinados = collect($dados_us['gastos'])
                            ->merge($dados_gs['gastos'])
                            ->merge($dados_rs['gastos']);
        
        $total_gastos_convertido = $gastos_combinados->reduce(function ($carry, $fluxo) use ($cotacoes) {
            $moeda = $fluxo->fechamentoOrigem->caixa->moeda ?? 'U$';
            $valor = floatval($fluxo->valor_origem ?? 0);
            $cotacao = $cotacoes[$moeda] ?? 1;
            return $carry + ($valor / $cotacao);
        }, 0);

        $entradas_combinados = collect($dados_us['entradas'])
                            ->merge($dados_gs['entradas'])
                            ->merge($dados_rs['entradas']);

        $total_entradas_convertido = $entradas_combinados->reduce(function ($carry, $fluxo) use ($cotacoes) {
            $moeda = $fluxo->fechamentoOrigem->caixa->moeda ?? 'U$';
            $valor = floatval($fluxo->valor_origem ?? 0);
            $cotacao = $cotacoes[$moeda] ?? 1;
            return $carry + ($valor / $cotacao);
        }, 0);

        $despesas_combinados = collect($dados_us['despesas'])
                            ->merge($dados_gs['despesas'])
                            ->merge($dados_rs['despesas']);

        $total_despesas_convertido = $despesas_combinados->reduce(function ($carry, $fluxo) use ($cotacoes) {
            $moeda = $fluxo->fechamentoOrigem->caixa->moeda ?? 'U$';
            $valor = floatval($fluxo->valor_origem ?? 0);
            $cotacao = $cotacoes[$moeda] ?? 1;
            return $carry + ($valor / $cotacao);
        }, 0);

        $dados_combinados = [
            "gastos"    => $gastos_combinados,
            "entradas" => $entradas_combinados,
            "despesas" => $despesas_combinados,
            "grafico" => $this->getGraficoCombinados($ano, $mes),
        ];

        $totais_combinados = [
            "gastos"    => $total_gastos_convertido,
            "entradas" => $total_entradas_convertido,
            "despesas" => $total_despesas_convertido,
        ];
    
        return view('admin.relatoriogastos.index', compact('dados_us', 'dados_gs', 'dados_rs', 'dados_combinados', 'totais_combinados'));
    } 

    private function getGraficoCombinados(int $ano, int $mes): array {
        // 1. Filtrar os caixas que utilizam a moeda enviada
        $caixas = Caixa::all()->pluck('id');

        // 2. Filtrar os FechamentoCaixa
        $fechamentos = FechamentoCaixa::whereIn('caixa_id', $caixas)
            ->whereMonth('start_date', $mes)
            ->whereYear('start_date', $ano)
            ->pluck('id');
        
        // 6. Montar o array para exibir o gráfico
        $grafico = $this->montarGrafico($fechamentos);

        return $grafico;
    }

    private function getRelatorioPorMoeda(string $moeda, int $ano, int $mes): array {
        // 1. Filtrar os caixas que utilizam a moeda enviada
        $caixas = Caixa::where('moeda', '=', $moeda)->pluck('id');

        // 2. Filtrar os FechamentoCaixa
        $fechamentos = FechamentoCaixa::whereIn('caixa_id', $caixas)
            ->whereMonth('start_date', $mes)
            ->whereYear('start_date', $ano)
            ->pluck('id');

        // 3. Filtrar os fluxos de caixa com a Moeda + Ano e Mês escolhidos + Filtro de GASTOS
        $gastos = FluxoCaixa::whereIn('fechamento_origem_id', $fechamentos)
            ->where(function ($query) {
                $query->where('tipo', 'saida')
                      ->orWhere('tipo', 'salario');
            })
            ->get();        

        // 4. Filtrar os fluxos de caixa com a Moeda + Ano e Mês escolhidos + Filtro de ENTRADAS (pagamentos)
        $entradas = FluxoCaixa::whereIn('fechamento_origem_id', $fechamentos)
            ->where('tipo', 'entrada')
            ->get();

        // 5. Filtrar os fluxos de caixa com a Moeda + Ano e Mês escolhidos + Filtro de DESPESAS (pagamentos a fornecedores) 
        $despesas = FluxoCaixa::whereIn('fechamento_origem_id', $fechamentos)
            ->where('tipo', 'despesa')
            ->get();

        // 6. Montar o array para exibir o gráfico
        $grafico = $this->montarGrafico($fechamentos);

        return [
            "gastos"    => $gastos,
            "entradas" => $entradas,
            "despesas" => $despesas,
            "grafico" => $grafico,
        ];
    }

    private function montarGrafico($fechamentos) {
        $labels = [];
        $data = [];
        $backgroundColor = [];
        $borderColor = [];

        // 6. Somar os gastos (totais) por categora e subcategoria 
        $subCategorias = FluxoCaixa::select('categoria_id', 'subcategoria_id', DB::raw('SUM(valor_origem) as total_saida'), 'tipo')
                            ->whereIn('fechamento_origem_id', $fechamentos)
                            ->where(function ($query) {
                                $query->where('tipo', 'saida')
                                    ->orWhere('tipo', 'salario');
                            })
                            ->groupBy('categoria_id', 'subcategoria_id', 'tipo')
                            ->get();

        foreach ($subCategorias as $categoria) {
            $label = $categoria->tipo == "salario" ? "Empresa - Salários" :
                ($categoria->categoria->nome ?? '') . " - " . ($categoria->subcategoria->nome ?? '');

            $labels[] = $label;
            $data[] = $categoria->total_saida;

            $red = mt_rand(0, 255);
            $green = mt_rand(0, 255);
            $blue = mt_rand(0, 255);

            $backgroundColor[] = "rgba($red, $green, $blue, 0.5)";
            $borderColor[] = "rgba($red, $green, $blue, 1)";
        }

        return [
            "labels"    => $labels,
            "data" => $data,
            "backgroundColor" => $backgroundColor,
            "borderColor" => $borderColor,
        ];
    }
}

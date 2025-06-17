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

    public function showGastos($periodo) {
        $conversoes = [
            'U$' => 1,
            'R$' => 1 / 5.85,
            'G$' => 1 / 7950,
        ];
    
        $data = Carbon::createFromFormat('Y-m', $periodo);
        $ano = $data->year;
        $mes = $data->month;    
        
        // 1. Calcular o início e o fim da semana dessa data
        $startDate = Carbon::create($ano, $mes, 1)->startOfMonth()->toDateString();
        $endDate = Carbon::create($ano, $mes, 1)->endOfMonth()->toDateString();

        // 2. Filtrar os caixas que utilizam a mesma moeda
        $caixasComMoeda = Caixa::where('moeda', '=', 'U$')->pluck('id');

        // 3. Filtrar os FechamentoCaixa que estão dentro do mês
        $fechamentosNaSemana = FechamentoCaixa::whereIn('caixa_id', $caixasComMoeda)
            ->whereMonth('start_date', $mes)
            ->whereYear('start_date', $ano)
            ->pluck('id');

        // 4. Filtrar os FluxoCaixa do tipo 'saida' para esses fechamentos
        $gastosUs = FluxoCaixa::whereIn('fechamento_origem_id', $fechamentosNaSemana)
            // ->where('tipo', '=', 'saida')
            ->where(function ($query) {
                $query->where('tipo', 'saida')
                      ->orWhere('tipo', 'salario');
            })
            ->whereBetween('data', [$startDate, $endDate])
            ->get();

        $totalGastosUs = FluxoCaixa::whereIn('fechamento_origem_id', $fechamentosNaSemana)
            // ->where('tipo', '=', 'saida')
            ->where(function ($query) {
                $query->where('tipo', 'saida')
                      ->orWhere('tipo', 'salario');
            })
            ->whereBetween('data', [$startDate, $endDate])
            ->sum('valor_origem');

        //GASTOS EM GUARANIS
        // 2. Filtrar os caixas que utilizam a mesma moeda
        $caixasComMoeda = Caixa::where('moeda', '=', 'G$')->pluck('id');

        // 3. Filtrar os FechamentoCaixa que estão dentro da semana
        $fechamentosNaSemana = FechamentoCaixa::whereIn('caixa_id', $caixasComMoeda)
            // ->whereBetween('start_date', [$startOfWeek, $endOfWeek])
            ->whereMonth('start_date', $mes)
            ->whereYear('start_date', $ano)
            ->pluck('id');

        // 4. Filtrar os FluxoCaixa do tipo 'saida' para esses fechamentos
        $gastosGs = FluxoCaixa::whereIn('fechamento_origem_id', $fechamentosNaSemana)
            // ->where('tipo', '=', 'saida')
            ->where(function ($query) {
                $query->where('tipo', 'saida')
                      ->orWhere('tipo', 'salario');
            })
            ->whereBetween('data', [$startDate, $endDate])
            ->get();

        $totalGastosGs = FluxoCaixa::whereIn('fechamento_origem_id', $fechamentosNaSemana)
            // ->where('tipo', '=', 'saida')
            ->where(function ($query) {
                $query->where('tipo', 'saida')
                      ->orWhere('tipo', 'salario');
            })
            ->whereBetween('data', [$startDate, $endDate])
            ->sum('valor_origem');

        //GASTOS EM REAIS
        // 2. Filtrar os caixas que utilizam a mesma moeda
        $caixasComMoeda = Caixa::where('moeda', '=', 'R$')->pluck('id');

        // 3. Filtrar os FechamentoCaixa que estão dentro da semana
        $fechamentosNaSemana = FechamentoCaixa::whereIn('caixa_id', $caixasComMoeda)
            // ->whereBetween('start_date', [$startOfWeek, $endOfWeek])
            ->whereMonth('start_date', $mes)
            ->whereYear('start_date', $ano)
            ->pluck('id');

        // 4. Filtrar os FluxoCaixa do tipo 'saida' para esses fechamentos
        $gastosRs = FluxoCaixa::whereIn('fechamento_origem_id', $fechamentosNaSemana)
            // ->where('tipo', '=', 'saida')
            ->where(function ($query) {
                $query->where('tipo', 'saida')
                      ->orWhere('tipo', 'salario');
            })
            ->whereBetween('data', [$startDate, $endDate])
            ->get();
        
        $totalGastosRs = FluxoCaixa::whereIn('fechamento_origem_id', $fechamentosNaSemana)
            // ->where('tipo', '=', 'saida')
            ->where(function ($query) {
                $query->where('tipo', 'saida')
                      ->orWhere('tipo', 'salario');
            })
            ->whereBetween('data', [$startDate, $endDate])
            ->sum('valor_origem');


    
        return view('admin.relatoriogastos.show', compact('ano', 'mes', 'gastosUs', 'gastosGs', 'gastosRs', 'totalGastosUs', 'totalGastosGs', 'totalGastosRs'));
    }

    /**
     * Display a listing of the resource.
     */
    public function indexGastosMensais(Request $request){
        $ano = $request->input('ano', date('Y'));
        $mes = $request->input('mes', date('n'));

        $all_categorias = Categoria::where('tipo', 'categoria')
                            ->get();
        $all_subcategorias = Categoria::where('tipo', 'subcategoria')
                            ->get();


        // 1. Filtrar os caixas que utilizam a moeda U$
        $caixasUS = Caixa::where('moeda', '=', 'U$')->pluck('id');

        // 2. Filtrar os FechamentoCaixa
        $fechamentosUS = FechamentoCaixa::whereIn('caixa_id', $caixasUS)
            ->whereMonth('start_date', $mes)
            ->whereYear('start_date', $ano)
            ->pluck('id');

        // 2. Filtrar os fluxos de caixa com a Moeda + Ano e Mês escolhidos + Filtro de GASTOS
        $fluxosUsGastos = FluxoCaixa::whereIn('fechamento_origem_id', $fechamentosUS)
            ->where(function ($query) {
                $query->where('tipo', 'saida')
                      ->orWhere('tipo', 'salario');
            })
            ->get();

        // 3. Filtrar os fluxos de caixa com a Moeda + Ano e Mês escolhidos + Filtro de ENTRADAS (pagamentos)
        $fluxosUsEntradas = FluxoCaixa::whereIn('fechamento_origem_id', $fechamentosUS)
            ->where('tipo', 'entrada')
            ->get();

        // 4. Filtrar os fluxos de caixa com a Moeda + Ano e Mês escolhidos + Filtro de DESPESAS (pagamentos a fornecedores) 
        $fluxosUsDespesas = FluxoCaixa::whereIn('fechamento_origem_id', $fechamentosUS)
            ->where('tipo', 'despesa')
            ->get();
        
        $subcategoriasUs = FluxoCaixa::select('categoria_id', 'subcategoria_id', DB::raw('SUM(valor_origem) as total_saida'), 'tipo')
                            ->whereIn('fechamento_origem_id', $fechamentosUS)
                            ->where(function ($query) {
                                $query->where('tipo', 'saida')
                                    ->orWhere('tipo', 'salario');
                            })
                            ->groupBy('categoria_id', 'subcategoria_id', 'tipo')
                            ->get();

        // Forma arrays para montagem do gráfico:
        // Inicializar arrays para armazenar os dados do gráfico
        $labels_sub = [];
        $data_sub = [];
        $backgroundColor_sub = [];
        $borderColor_sub = [];

        // Iterar sobre os resultados da consulta
        foreach ($subcategoriasUs as $categoria) {
            $label = "";
            if ($categoria->tipo == "saida") {
                $label = $categoria->categoria->nome . " - " . $categoria->subcategoria->nome;
            } else { //salario
                $label = "Empresa - Salarios";
            }
            // Adicionar categoria_id como label
            $labels_sub[] = $label;//$categoria->categoria->nome . " - " . $categoria->subcategoria->nome;
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
        $grafico_sub_us = [
            'labels' => $labels_sub,
            'data' => $data_sub,
            'backgroundColor' => $backgroundColor_sub,
            'borderColor' => $borderColor_sub
        ];


        // 1. Filtrar os caixas que utilizam a moeda U$
        $caixasGS = Caixa::where('moeda', '=', 'G$')->pluck('id');

        // 2. Filtrar os FechamentoCaixa
        $fechamentosGS = FechamentoCaixa::whereIn('caixa_id', $caixasGS)
            ->whereMonth('start_date', $mes)
            ->whereYear('start_date', $ano)
            ->pluck('id');

        // 2. Filtrar os fluxos de caixa com a Moeda + Ano e Mês escolhidos + Filtro de GASTOS
        $fluxosGsGastos = FluxoCaixa::whereIn('fechamento_origem_id', $fechamentosGS)
            ->where(function ($query) {
                $query->where('tipo', 'saida')
                      ->orWhere('tipo', 'salario');
            })
            ->get();

        // 3. Filtrar os fluxos de caixa com a Moeda + Ano e Mês escolhidos + Filtro de ENTRADAS (pagamentos)
        $fluxosGsEntradas = FluxoCaixa::whereIn('fechamento_origem_id', $fechamentosGS)
            ->where('tipo', 'entrada')
            ->get();

        // 4. Filtrar os fluxos de caixa com a Moeda + Ano e Mês escolhidos + Filtro de DESPESAS (pagamentos a fornecedores) 
        $fluxosGsDespesas = FluxoCaixa::whereIn('fechamento_origem_id', $fechamentosGS)
            ->where('tipo', 'despesa')
            ->get();
        
        $subcategoriasGs = FluxoCaixa::select('categoria_id', 'subcategoria_id', DB::raw('SUM(valor_origem) as total_saida'), 'tipo')
                            ->whereIn('fechamento_origem_id', $fechamentosGS)
                            ->where(function ($query) {
                                $query->where('tipo', 'saida')
                                    ->orWhere('tipo', 'salario');
                            })
                            ->groupBy('categoria_id', 'subcategoria_id', 'tipo')
                            ->get();

        // Forma arrays para montagem do gráfico:
        // Inicializar arrays para armazenar os dados do gráfico
        $labels_sub = [];
        $data_sub = [];
        $backgroundColor_sub = [];
        $borderColor_sub = [];

        // Iterar sobre os resultados da consulta
        foreach ($subcategoriasGs as $categoria) {
            $label = "";
            if ($categoria->tipo == "saida") {
                $label = $categoria->categoria->nome . " - " . $categoria->subcategoria->nome;
            } else { //salario
                $label = "Empresa - Salarios";
            }
            // Adicionar categoria_id como label
            $labels_sub[] = $label;//$categoria->categoria->nome . " - " . $categoria->subcategoria->nome;
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
        $grafico_sub_gs = [
            'labels' => $labels_sub,
            'data' => $data_sub,
            'backgroundColor' => $backgroundColor_sub,
            'borderColor' => $borderColor_sub
        ];

        // 1. Filtrar os caixas que utilizam a moeda U$
        $caixasRS = Caixa::where('moeda', '=', 'R$')->pluck('id');

        // 2. Filtrar os FechamentoCaixa
        $fechamentosRS = FechamentoCaixa::whereIn('caixa_id', $caixasRS)
            ->whereMonth('start_date', $mes)
            ->whereYear('start_date', $ano)
            ->pluck('id');

        // 2. Filtrar os fluxos de caixa com a Moeda + Ano e Mês escolhidos + Filtro de GASTOS
        $fluxosRsGastos = FluxoCaixa::whereIn('fechamento_origem_id', $fechamentosRS)
            ->where(function ($query) {
                $query->where('tipo', 'saida')
                      ->orWhere('tipo', 'salario');
            })
            ->get();

        // 3. Filtrar os fluxos de caixa com a Moeda + Ano e Mês escolhidos + Filtro de ENTRADAS (pagamentos)
        $fluxosRsEntradas = FluxoCaixa::whereIn('fechamento_origem_id', $fechamentosRS)
            ->where('tipo', 'entrada')
            ->get();

        // 4. Filtrar os fluxos de caixa com a Moeda + Ano e Mês escolhidos + Filtro de DESPESAS (pagamentos a fornecedores) 
        $fluxosRsDespesas = FluxoCaixa::whereIn('fechamento_origem_id', $fechamentosRS)
            ->where('tipo', 'despesa')
            ->get();
        
        $subcategoriasRs = FluxoCaixa::select('categoria_id', 'subcategoria_id', DB::raw('SUM(valor_origem) as total_saida'), 'tipo')
                            ->whereIn('fechamento_origem_id', $fechamentosRS)
                            ->where(function ($query) {
                                $query->where('tipo', 'saida')
                                    ->orWhere('tipo', 'salario');
                            })
                            ->groupBy('categoria_id', 'subcategoria_id', 'tipo')
                            ->get();

        // Forma arrays para montagem do gráfico:
        // Inicializar arrays para armazenar os dados do gráfico
        $labels_sub = [];
        $data_sub = [];
        $backgroundColor_sub = [];
        $borderColor_sub = [];

        // Iterar sobre os resultados da consulta
        foreach ($subcategoriasRs as $categoria) {
            $label = "";
            if ($categoria->tipo == "saida") {
                $label = $categoria->categoria->nome . " - " . $categoria->subcategoria->nome;
            } else { //salario
                $label = "Empresa - Salarios";
            }
            // Adicionar categoria_id como label
            $labels_sub[] = $label;//$categoria->categoria->nome . " - " . $categoria->subcategoria->nome;
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
        $grafico_sub_rs = [
            'labels' => $labels_sub,
            'data' => $data_sub,
            'backgroundColor' => $backgroundColor_sub,
            'borderColor' => $borderColor_sub
        ];
    
        return view('admin.relatoriogastos.index', compact('fluxosUsGastos', 'fluxosUsEntradas', 'fluxosUsDespesas', 'grafico_sub_us', 'fluxosGsGastos', 'fluxosGsEntradas', 'fluxosGsDespesas', 'grafico_sub_gs', 'fluxosRsGastos', 'fluxosRsEntradas', 'fluxosRsDespesas', 'grafico_sub_rs'));
    }
}

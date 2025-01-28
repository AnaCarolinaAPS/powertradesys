<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\FaturaCarga;
use App\Models\FechamentoCaixa;

class RelatorioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function indexCarga()
    {
        $all_items = FaturaCarga::all();
        return view('admin.relatoriocarga.index', compact('all_items'));
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

        $i = 0;

        // Iterar sobre os resultados da consulta
        foreach ($faturacarga->invoices as $invoice) {
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

            ++$i;
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
    public function indexGastos()
    {
        // Definir a query base com os joins necessários
        $baseQuery = FechamentoCaixa::query()
            ->join('fluxo_caixas as transacoes', 'fechamento_caixas.id', '=', 'transacoes.fechamento_origem_id')
            ->join('caixas', 'fechamento_caixas.caixa_id', '=', 'caixas.id')
            ->where('caixas.moeda', 'U$'); // Filtra apenas caixas com moeda 'U$'

        // Calcular os valores agrupados por mês
        $dadosPorMes = $baseQuery
            ->selectRaw("
                DATE_FORMAT(fechamento_caixas.start_date, '%Y-%m') as mes,
                SUM(CASE WHEN transacoes.tipo = 'despesa' THEN transacoes.valor_origem ELSE 0 END) as total_despesas,
                SUM(CASE WHEN transacoes.tipo = 'entrada' THEN transacoes.valor_origem ELSE 0 END) as total_entradas,
                SUM(CASE WHEN transacoes.tipo IN ('saida', 'salario', 'cambio') THEN transacoes.valor_origem ELSE 0 END) as total_gastos
            ")
            ->groupBy('mes')
            ->get()
            ->keyBy('mes'); // Agrupa os resultados pelo mês

        // Calcular o saldo e consolidar o resultado final
        $resultado = $dadosPorMes->map(function ($dados, $mes) {
            $saldo = ($dados->total_entradas + $dados->total_despesas ?? 0) + ($dados->total_gastos ?? 0);

            return [
                'mes' => $mes,
                'despesas' => $dados->total_despesas ?? 0,
                'entradas' => $dados->total_entradas ?? 0,
                'lucros' => $dados->total_entradas + $dados->total_despesas ?? 0,
                'gastos' => $dados->total_gastos ?? 0,
                'saldo' => $saldo,
            ];
        });

        return view('admin.relatoriogastos.index', ['resultado' => $resultado->sortKeys()]);
    }

    public function showGastos($periodo)
    {
        $data = Carbon::createFromFormat('Y-m', $periodo);
        $ano = $data->year;
        $mes = $data->month;
        // Converter o mês e ano para um intervalo de datas
        $startDate = Carbon::create($ano, $mes, 1)->startOfMonth()->toDateString();
        $endDate = Carbon::create($ano, $mes, 1)->endOfMonth()->toDateString();

        // Filtrar os fechamentos de caixa no intervalo e agrupar por caixa_id
        $fechamentos = FechamentoCaixa::whereBetween('start_date', [$startDate, $endDate])
            ->with(['transacoesOrigem', 'caixa'])
            ->get()
            ->groupBy('caixa_id');

        $lucroReal = 0;
        // Consolidar os totais por caixa
        $detalhes = $fechamentos->map(function ($fechamentosPorCaixa, $caixaId) {
            $totais = [
                'gastos' => 0,
                'salarios' => 0,
                'despesas' => 0,
                'entradas' => 0,
                'lucroreal' => 0,
                'total' => 0,
            ];

            foreach ($fechamentosPorCaixa as $fechamento) {
                $totais['gastos'] += $fechamento->transacoesOrigem->where('tipo', 'saida')->sum('valor_origem');
                $totais['salarios'] += $fechamento->transacoesOrigem->where('tipo', 'salario')->sum('valor_origem');
                $totais['despesas'] += $fechamento->transacoesOrigem->where('tipo', 'despesa')->sum('valor_origem');
                $totais['entradas'] += $fechamento->transacoesOrigem->where('tipo', 'entrada')->sum('valor_origem');
            }

            // $totais['total'] = $totais['gastos'] + $totais['salarios'] + $totais['despesas'];
            $totais['total'] = $totais['gastos'] + $totais['salarios'] + $totais['entradas'] + $totais['despesas'];
            $totais['lucroreal'] = $totais['entradas'] + $totais['despesas'];

            return [
                'caixa' => $fechamentosPorCaixa->first()->caixa,
                'totais' => $totais,
            ];
        });

        $lucroTotal = 0;

        // Filtrar os fechamentos de caixa no intervalo e agrupar por caixa_id
        $faturas = FaturaCarga::whereHas('carga', function ($query) use ($startDate, $endDate) {
            $query->whereBetween('data_recebida', [$startDate, $endDate]);
        })->get();        

        return view('admin.relatoriogastos.show', compact('ano', 'mes', 'detalhes', 'lucroTotal', 'lucroReal'));
    }

}

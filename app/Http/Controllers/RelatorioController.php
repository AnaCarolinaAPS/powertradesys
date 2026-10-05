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
use App\Models\ContasPagar;
use App\Models\ContasFixas;
use App\Models\Invoice;
use App\Models\Cliente;


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

        // dd($dados_us['grafico']);

        $totais_combinados = [
            "gastos"    => $total_gastos_convertido,
            "entradas" => $total_entradas_convertido,
            "despesas" => $total_despesas_convertido,
        ];

        $totalGastosbyCategoria = $this->getTotaisByCategoria($ano, $mes);
    
        return view('admin.relatoriogastos.index', compact('dados_us', 'dados_gs', 'dados_rs', 'dados_combinados', 'totais_combinados', 'totalGastosbyCategoria'));
    } 

    private function getTotaisByCategoria(int $ano, int $mes) {    
        
        // Cotações de outras moedas para Dólar
        $cotacaoRS = 5.50;
        $cotacaoGS = 7500;

        // 1. Filtrar os caixas que utilizam dólar
        $caixas = Caixa::where('moeda', '=', 'U$')->pluck('id');

        // 2. Filtrar os FechamentoCaixa
        $fechamentos = FechamentoCaixa::whereIn('caixa_id', $caixas)
            ->whereMonth('start_date', $mes)
            ->whereYear('start_date', $ano)
            ->pluck('id');

        // 3. Filtrar os fluxos de caixa com a Moeda + Ano e Mês escolhidos + Filtro de GASTOS
        $gastosUS = FluxoCaixa::selectRaw('categoria_id, subcategoria_id, SUM(valor_origem) as total')
            ->whereIn('fechamento_origem_id', $fechamentos)
            ->with(['categoria', 'subcategoria'])
            ->where(function ($query) {
                $query->where('tipo', 'saida')
                      ->orWhere('tipo', 'salario');
            })
            ->groupBy('categoria_id', 'subcategoria_id')
            ->get(); 
        
        // 4. Transformar em array indexado por categoria-subcategoria para facilitar merge
        $gastosCombinados = [];

        foreach ($gastosUS as $item) {
            $key = $item->categoria_id . '-' . ($item->subcategoria_id ?? 'null');
            $gastosCombinados[$key] = [
                'categoria_id' => $item->categoria_id,
                'subcategoria_id' => $item->subcategoria_id,
                'total' => $item->total,
                'categoria' => $item->categoria,
                'subcategoria' => $item->subcategoria,
            ];
        }

        // 1. Filtrar os caixas que utilizam Reais
        $caixas = Caixa::where('moeda', '=', 'R$')->pluck('id');

        // 2. Filtrar os FechamentoCaixa
        $fechamentos = FechamentoCaixa::whereIn('caixa_id', $caixas)
            ->whereMonth('start_date', $mes)
            ->whereYear('start_date', $ano)
            ->pluck('id');

        // 3. Filtrar os fluxos de caixa com a Moeda + Ano e Mês escolhidos + Filtro de GASTOS
        $gastosRS = FluxoCaixa::selectRaw('categoria_id, subcategoria_id, SUM(valor_origem) as total')
            ->whereIn('fechamento_origem_id', $fechamentos)
            ->with(['categoria', 'subcategoria'])
            ->where(function ($query) {
                $query->where('tipo', 'saida')
                      ->orWhere('tipo', 'salario');
            })
            ->groupBy('categoria_id', 'subcategoria_id')
            ->get();


        foreach ($gastosRS as $item) {
            $key = $item->categoria_id . '-' . ($item->subcategoria_id ?? 'null');
            $valorConvertido = $item->total / $cotacaoRS;

            if (isset($gastosCombinados[$key])) {
                // Soma ao valor existente
                $gastosCombinados[$key]['total'] += $valorConvertido;
            } else {
                // Novo item
                $gastosCombinados[$key] = [
                    'categoria_id' => $item->categoria_id,
                    'subcategoria_id' => $item->subcategoria_id,
                    'total' => $valorConvertido,
                    'categoria' => $item->categoria,
                    'subcategoria' => $item->subcategoria,
                ];
            }
        }

        // 1. Filtrar os caixas que utilizam Guaranies
        $caixas = Caixa::where('moeda', '=', 'G$')->pluck('id');

        // 2. Filtrar os FechamentoCaixa
        $fechamentos = FechamentoCaixa::whereIn('caixa_id', $caixas)
            ->whereMonth('start_date', $mes)
            ->whereYear('start_date', $ano)
            ->pluck('id');

        // 3. Filtrar os fluxos de caixa com a Moeda + Ano e Mês escolhidos + Filtro de GASTOS
        $gastosGS = FluxoCaixa::selectRaw('categoria_id, subcategoria_id, SUM(valor_origem) as total')
            ->whereIn('fechamento_origem_id', $fechamentos)
            ->with(['categoria', 'subcategoria'])
            ->where(function ($query) {
                $query->where('tipo', 'saida')
                      ->orWhere('tipo', 'salario');
            })
            ->groupBy('categoria_id', 'subcategoria_id')
            ->get(); 

        foreach ($gastosGS as $item) {
            $key = $item->categoria_id . '-' . ($item->subcategoria_id ?? 'null');
            $valorConvertido = $item->total / $cotacaoGS;

            if (isset($gastosCombinados[$key])) {
                // Soma ao valor existente
                $gastosCombinados[$key]['total'] += $valorConvertido;
            } else {
                // Novo item
                $gastosCombinados[$key] = [
                    'categoria_id' => $item->categoria_id,
                    'subcategoria_id' => $item->subcategoria_id,
                    'total' => $valorConvertido,
                    'categoria' => $item->categoria,
                    'subcategoria' => $item->subcategoria,
                ];
            }
        }
        
        return collect($gastosCombinados)->values();
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
        $grafico = $this->montarGrafico2($fechamentos, 'U$');

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
        $grafico = $this->montarGrafico2($fechamentos, $moeda);

        return [
            "gastos"    => $gastos,
            "entradas" => $entradas,
            "despesas" => $despesas,
            "grafico" => $grafico,
        ];
    }

    private function montarGrafico2($fechamentos, $moedaDestino) {
        $labels = [];
        $data = [];
        $backgroundColor = [];
        $borderColor = [];

        // Cotações fixas (podem vir do banco, se preferir)
        $cotacoes = [
            'G$' => 7850.0,
            'R$' => 5.80,
            'U$' => 1.0,
        ];

        $fluxos = FluxoCaixa::with(['categoria', 'subcategoria', 'fechamentoOrigem.caixa'])
                    ->whereIn('fechamento_origem_id', $fechamentos)
                    ->whereIn('tipo', ['saida', 'salario'])
                    ->get();

        $agrupados = [];

        foreach ($fluxos as $fluxo) {
            $moedaOrigem = $fluxo->fechamentoOrigem->caixa->moeda ?? 'U$';
            $cotacaoOrigem = $cotacoes[$moedaOrigem] ?? 1;
            $cotacaoDestino = $cotacoes[$moedaDestino] ?? 1;

            // Converte da moeda original para a moeda destino
            $valor_convertido = $fluxo->valor_origem * ($cotacaoDestino / $cotacaoOrigem);

            $key = $fluxo->tipo === 'salario'
                ? 'Empresa - Salários'
                : ($fluxo->categoria->nome ?? 'Sem Categoria') . ' - ' . ($fluxo->subcategoria->nome ?? 'Sem Subcategoria');

            $agrupados[$key] = ($agrupados[$key] ?? 0) + $valor_convertido;
        }

        foreach ($agrupados as $label => $total_convertido) {
            $labels[] = $label;
            $data[] = $total_convertido;

            $red = mt_rand(0, 255);
            $green = mt_rand(0, 255);
            $blue = mt_rand(0, 255);

            $backgroundColor[] = "rgba($red, $green, $blue, 0.5)";
            $borderColor[] = "rgba($red, $green, $blue, 1)";
        }

        // // 6. Somar os gastos (totais) por categora e subcategoria 
        // $subCategorias = FluxoCaixa::select('categoria_id', 'subcategoria_id', DB::raw('SUM(valor_origem) as total_saida'), 'tipo')
        //                     ->whereIn('fechamento_origem_id', $fechamentos)
        //                     ->where(function ($query) {
        //                         $query->where('tipo', 'saida')
        //                             ->orWhere('tipo', 'salario');
        //                     })
        //                     ->groupBy('categoria_id', 'subcategoria_id', 'tipo')
        //                     ->get();

        // foreach ($subCategorias as $categoria) {
        //     $label = $categoria->tipo == "salario" ? "Empresa - Salários" :
        //         ($categoria->categoria->nome ?? '') . " - " . ($categoria->subcategoria->nome ?? '');

        //     $labels[] = $label;
        //     $data[] = $categoria->total_saida;

        //     $red = mt_rand(0, 255);
        //     $green = mt_rand(0, 255);
        //     $blue = mt_rand(0, 255);

        //     $backgroundColor[] = "rgba($red, $green, $blue, 0.5)";
        //     $borderColor[] = "rgba($red, $green, $blue, 1)";
        // }

        return [
            "labels"    => $labels,
            "data" => $data,
            "backgroundColor" => $backgroundColor,
            "borderColor" => $borderColor,
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function indexCategorias(Request $request){
        $all_categorias = Categoria::where('tipo', 'categoria')
                            ->get();
        $all_subcategorias = Categoria::where('tipo', 'subcategoria')
                        ->get();

        // IDs das categorias selecionadas
        $categorias = $request->input('categoria_id');
        // IDs das subcategorias selecionadas
        $subcategorias = $request->input('subcategoria_id');
        // Data de Inicio do Relatório
        $data_inicio = $request->input('data_inicio');
        // Data de FIM do Relatório
        $data_fim = $request->input('data_fim');

        // Caso o usuário venha diretamente do menu
        if (empty($categorias)) {
            $categorias = [];
        }
        if (empty($subcategorias)) {
            $subcategorias = [];
        }

        if (empty($data_inicio)) {
            $data_inicio = Carbon::today()->subDays(30)->format('Y-m-d');
        }
        if (empty($data_fim)) {
            $data_fim = Carbon::today()->format('Y-m-d');
        }

        // Verifica se "Salários" foi selecionado
        $incluirSalario = in_array('', $subcategorias);

        // Remove a string vazia do array de subcategorias (para evitar erro no whereIn)
        $subcategoriasFiltradas = array_filter($subcategorias, function ($id) {
            return $id !== '';
        });
        
        //Incluir ou não Salários na busca
        $tipos = ['saida'];
        if ($incluirSalario) {
            $tipos[] = 'salario';
        }

        // Filtrar os Fluxos 
        $fluxos = FluxoCaixa::with(['categoria', 'subcategoria', 'fechamentoOrigem'])
                    ->whereIn('tipo', $tipos)
                    ->whereBetween('data', [$data_inicio, $data_fim]);

        if ($incluirSalario) {
            $fluxos->where(function ($query) use ($subcategoriasFiltradas, $incluirSalario) {
                // Caso tenha subcategorias selecionadas, filtra
                if (!empty($subcategoriasFiltradas)) {
                    $query->whereIn('subcategoria_id', $subcategoriasFiltradas);
                }

                // Caso "salário" tenha sido marcado, inclui registros SEM subcategoria (null)
                if ($incluirSalario) {
                    $query->orWhere(function ($q) {
                        $q->whereNull('subcategoria_id')
                        ->where('tipo', 'salario');
                    });
                }
            });
        } else {
            $fluxos->whereIn('categoria_id', (array) $categorias);
            $fluxos->whereIn('subcategoria_id', (array) $subcategorias);
        }        
        $fluxos = $fluxos->orderBy('data', 'desc')->get();

        $cotacoes = [
            'G$' => 7850.0,
            'R$' => 5.80,
            'U$' => 1.0,
        ];

        $moedaDestino = 'U$';

        $grafico = $this->montarGrafico($fluxos, $cotacoes, $moedaDestino);
        $gastosAgrupados = $this->getTotaisByCategoria2($fluxos, $cotacoes, $moedaDestino);

        return view('admin.relatoriocategorias.index', compact('all_categorias', 'all_subcategorias', 'fluxos', 'grafico', 'gastosAgrupados'));
    } 

    private function montarGrafico($fluxos, $cotacoes, $moedaDestino) {
        $labels = [];
        $data = [];
        $backgroundColor = [];
        $borderColor = [];

        if (empty($cotacoes))  {
            // Cotações fixas (podem vir do banco, se preferir)
            $cotacoes = [
                'G$' => 7850.0,
                'R$' => 5.80,
                'U$' => 1.0,
            ];
        }

        $agrupados = [];

        foreach ($fluxos as $fluxo) {
            $moedaOrigem = $fluxo->fechamentoOrigem->caixa->moeda ?? 'U$';
            $cotacaoOrigem = $cotacoes[$moedaOrigem] ?? 1;
            $cotacaoDestino = $cotacoes[$moedaDestino] ?? 1;

            // Converte da moeda original para a moeda destino
            $valor_convertido = $fluxo->valor_origem * ($cotacaoDestino / $cotacaoOrigem);

            $key = $fluxo->tipo === 'salario'
                ? 'Empresa - Salários'
                : ($fluxo->categoria->nome ?? 'Sem Categoria') . ' - ' . ($fluxo->subcategoria->nome ?? 'Sem Subcategoria');

            $agrupados[$key] = ($agrupados[$key] ?? 0) + $valor_convertido;
        }

        foreach ($agrupados as $label => $total_convertido) {
            $labels[] = $label;
            $data[] = $total_convertido;

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

    private function getTotaisByCategoria2($fluxos, $cotacoes, $moedaDestino) {    
        
        if (empty($cotacoes))  {
            // Cotações fixas (podem vir do banco, se preferir)
            $cotacoes = [
                'G$' => 7850.0,
                'R$' => 5.80,
                'U$' => 1.0,
            ];
        }

        $agrupados = [];

        foreach ($fluxos as $fluxo) {
            $moedaOrigem = $fluxo->fechamentoOrigem->caixa->moeda ?? 'U$';
            $cotacaoOrigem = $cotacoes[$moedaOrigem] ?? 1;
            $cotacaoDestino = $cotacoes[$moedaDestino] ?? 1;

            // Converte da moeda original para a moeda destino
            $valor_convertido = $fluxo->valor_origem * ($cotacaoDestino / $cotacaoOrigem);

            $key = $fluxo->tipo === 'salario'
                ? 'Empresa - Salários'
                : ($fluxo->categoria->nome ?? 'Sem Categoria') . ' - ' . ($fluxo->subcategoria->nome ?? 'Sem Subcategoria');

            $agrupados[$key] = ($agrupados[$key] ?? 0) + $valor_convertido;
        }        
        
        // dd($agrupados);
        return $agrupados;
    }

    /**
     * Display a listing of the resource.
     */
    public function indexContasPagar(Request $request)
    {
        if (!$request->has('ano')) {
            $ano = date('Y');
        } else {
            $ano = $request->input('ano');
        }

        if (!$request->has('mes')) {
            $mes = date('n');
        } else {
            $mes = $request->input('mes');
        }
        
        $dataCorte = \Carbon\Carbon::create($ano, $mes, 1)->endOfMonth();

        $contas_atrasadas = ContasPagar::whereDate('data_vencimento', '<', $dataCorte)
                    ->get()
                    ->filter(function ($conta) {
                        return $conta->valor_pendente() > 0;
                    });
                    // ->pluck('id');

        $all_items = ContasPagar::whereMonth('data_vencimento', $mes)
                                ->whereYear('data_vencimento', $ano)
                                // ->orWhereIn('id', $contas_atrasadas)
                                ->get();

        $all_categorias = Categoria::where('tipo', 'categoria')
                            ->get();
        $all_subcategorias = Categoria::where('tipo', 'subcategoria')
                        ->get();

        //Array com os ID's de todas as contas fixas que estão ativas!
        $contas_fixas_ativas = ContasFixas::where('ativa', true)
                                ->pluck('id');
        
        //Array de ID's de contas a pagar criadas a partir de contas fixas;
        $fixas_criadas = ContasPagar::whereMonth('data_vencimento', $mes)
                                ->whereYear('data_vencimento', $ano)
                                ->whereNotNull('contas_fixa_id')
                                ->pluck('contas_fixa_id');
        
        // IDs de contas fixas que ainda não têm contas_pagar criadas
        $nao_criadas = $contas_fixas_ativas->diff($fixas_criadas);

        $contasFixasNaoCriadas = ContasFixas::whereIn('id', $nao_criadas)->get();

        $totalUS = ContasPagar::whereMonth('data_vencimento', $mes)
                                ->whereYear('data_vencimento', $ano)
                                ->where('moeda', 'U$')
                                ->sum('valor');

        $totalRS = ContasPagar::whereMonth('data_vencimento', $mes)
                                ->whereYear('data_vencimento', $ano)
                                ->where('moeda', 'R$')
                                ->sum('valor');
        
        $totalGS = ContasPagar::whereMonth('data_vencimento', $mes)
                                ->whereYear('data_vencimento', $ano)
                                ->where('moeda', 'G$')
                                ->sum('valor');
        
        $hoje = \Carbon\Carbon::create($ano, $mes, 1); // data de hoje, sem hora

        $atrasadosUS = ContasPagar::whereDate('data_vencimento', '<=', $hoje)
                                ->where('moeda', 'U$')
                                ->get()
                                ->sum(function ($conta) {
                                    return $conta->valor_pendente();
                                });

        $atrasadosRS = ContasPagar::whereDate('data_vencimento', '<=', $hoje)
                                ->where('moeda', 'R$')
                                ->get()
                                ->sum(function ($conta) {
                                    return $conta->valor_pendente();
                                });
        
        $atrasadosGS = ContasPagar::whereDate('data_vencimento', '<=', $hoje)
                                ->where('moeda', 'G$')
                                ->get()
                                ->sum(function ($conta) {
                                    return $conta->valor_pendente();
                                });

        $totais = ['totalUs' => $totalUS,
                    'totalRs' => $totalRS,
                    'totalGs' => $totalGS,
                    'atrasadosUS' => $atrasadosUS,
                    'atrasadosRS' => $atrasadosRS,
                    'atrasadosGS' => $atrasadosGS,
                ];

        $all_caixas = Caixa::all();
        
        return view('admin.relatoriocontaspagar.index', compact('all_items', 'all_categorias', 'all_subcategorias', 'contasFixasNaoCriadas', 'all_caixas', 'totais'));
    }

    /**
     * Display a listing of the resource.
     */
    // public function indexClientes(Request $request)
    // {
    //     $inicio = Carbon::now()->subMonth(12)->startOfMonth();
    //     $fim = Carbon::now()->endOfMonth();


    //     $cargasFiltradas = Carga::whereBetween('data_recebida', [$inicio, $fim])->pluck('id');

    //     $faturaFiltrada = FaturaCarga::whereIn('carga_id', $cargasFiltradas)->pluck('id');

    //     $invoicesFiltradas = Invoice::whereIn('fatura_carga_id', $faturaFiltrada)->get();

    //     $agrupados = [];

    //     foreach ($invoicesFiltradas as $invoice) {
    //         $key = $invoice->cliente_id;

    //         $agrupados[$key] = ($agrupados[$key] ?? 0) + $invoice->peso_pacote_orig();
    //     }    

    //     $all_items = $agrupados; 

    //     $clientes = Cliente::whereIn('id', array_keys($agrupados))
    //                         ->pluck('apelido', 'id');
    //     // var_dump($agrupados);        

    //     return view('admin.relatorioclientes.index', compact('all_items', 'clientes'));
    // }

}

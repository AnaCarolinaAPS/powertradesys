<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Carga;
use App\Models\FaturaCarga;
use App\Models\Warehouse;
use App\Models\Cliente;
use App\Models\Fornecedor;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Começar pela semana atual
        $currentDate = Carbon::now();
        // Obter o início e o fim da semana atual
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();        

        // Buscar as cargas da semana
        $cargasMiami = Warehouse::whereBetween('data', [$startOfWeek, $endOfWeek])->get();

        // Buscar as FaturaCarga cujas cargas têm a data_recebida dentro da semana
        $cargasDaSemana = FaturaCarga::whereHas('carga', function($query) use ($startOfWeek, $endOfWeek) {
            $query->whereBetween('data_recebida', [$startOfWeek, $endOfWeek]);
        })->get();

        $peso = 0;
        $totalClientes = 0;
        $totalPacotes = 0;
        $tipoCarga = "faturacarga";

        //Caso na semana atual não tenha uma Fatura ainda
        if ($cargasDaSemana) {
            $semanaPassada = $currentDate->subWeek();
            $startOfWeekP = (clone $semanaPassada)->startOfWeek();
            $endOfWeekP = (clone $semanaPassada)->endOfWeek();

            $cargasDaSemana = Carga::whereNull('data_recebida')
                            ->whereBetween('data_enviada', [$startOfWeekP, $endOfWeekP])
                            ->first();

            if ($cargasDaSemana && !$cargasDaSemana->isEmpty()) {   
                $peso += $cargasDaSemana->pacotes->sum('peso_aprox');
                $totalClientes += $cargasDaSemana->clientes->count();
                $totalPacotes += $cargasDaSemana->pacotes->count();    

                $tipoCarga = "carga";
                $cargasDaSemana = DB::table('pacotes')
                            ->select('clientes.id', 'clientes.caixa_postal', 'clientes.apelido',
                                    DB::raw('COALESCE(SUM(pacotes.qtd), 0) as total_pacotes'),
                                    DB::raw('COALESCE(SUM(pacotes.peso_aprox), 0) as total_aproximado'),
                                    DB::raw('COALESCE(SUM(pacotes.peso), 0) as total_real'))
                                    ->leftJoin('clientes', 'clientes.id', '=', 'pacotes.cliente_id')
                                    // ->leftJoin('users', 'users.id', '=', 'clientes.user_id') // Junção com a tabela de usuários
                                    ->where('pacotes.carga_id', $cargasDaSemana->id)
                                    ->groupBy('clientes.id', 'clientes.caixa_postal', 'clientes.apelido')
                                    ->orderBy('total_aproximado', 'DESC')
                                    ->get();
            } else {
                $peso = 0;
                $totalClientes = 0;
                $totalPacotes = 0;
            }

        } else {
            foreach ($cargasDaSemana as $fatura_carga) {
                $peso += $fatura_carga->invoices_pesos_total();
                $totalClientes += $fatura_carga->carga->clientes->count();
                $totalPacotes += $fatura_carga->carga->pacotes->count();
            }
        }

        $semanaPassada = $currentDate->subWeek();
        // Obter o início e o fim da semana atual
        $startOfWeekPass = Carbon::now()->subWeek()->startOfWeek();
        $endOfWeekPass = Carbon::now()->subWeek()->endOfWeek();

        // $cargasPassada = Carga::whereBetween('data_recebida', [$startOfWeekPass, $endOfWeekPass])->get();
        $cargasPassada = FaturaCarga::whereHas('carga', function($query) use ($startOfWeekPass, $endOfWeekPass) {
            $query->whereBetween('data_recebida', [$startOfWeekPass, $endOfWeekPass]);
        })->get();

        $pesoAnterior = 0;
        $totalCobradoAnterior = 0;
        $totalClientesAnterior= 0;
        $totalPacotesAnterior = 0;

        foreach ($cargasPassada as $fatura_carga) {
            $pesoAnterior += $fatura_carga->invoices_pesos_total();
            $totalClientesAnterior += $fatura_carga->carga->clientes->count();
            $totalPacotesAnterior += $fatura_carga->carga->pacotes->count();
        }

        if ($pesoAnterior > 0) {
            $variacaoPercentualPeso = (($peso - $pesoAnterior) / $pesoAnterior) * 100;
        } else {
            $variacaoPercentualPeso = 0; // Caso a semana anterior seja 0, não há como calcular variação percentual
        }

        $cargaCard = [
            'valor' => $peso,
            'porcentagem' => $variacaoPercentualPeso,
        ];        

        $pesoMiami = 0;
        foreach ($cargasMiami as $warehouse) {
            $pesoMiami += $warehouse->pacotes->sum('peso_aprox');
        }

        if ($peso > 0) {
            $variacaoPercentualMiami = (($pesoMiami - $peso) / $peso) * 100;
        } else {
            $variacaoPercentualMiami = 0; // Caso a semana anterior seja 0, não há como calcular variação percentual
        }

        $miamiCard = [
            'valor' => $pesoMiami,
            'porcentagem' => $variacaoPercentualMiami,
        ];

        if ($totalClientesAnterior > 0) {
            $variacaoPercentualClientes = (($totalClientes - $totalClientesAnterior) / $totalClientesAnterior) * 100;
        } else {
            $variacaoPercentualClientes = 0; // Caso a semana anterior seja 0, não há como calcular variação percentual
        }

        $clientesCard = [
            'valor' => $totalClientes,
            'porcentagem' => $variacaoPercentualClientes,
        ];

        if ($totalPacotesAnterior > 0) {
            $variacaoPercentualPacotes = (($totalPacotes - $totalPacotesAnterior) / $totalPacotesAnterior) * 100;
        } else {
            $variacaoPercentualPacotes = 0; // Caso a semana anterior seja 0, não há como calcular variação percentual
        }

        $pacotesCard = [
            'valor' => $totalPacotes,
            'porcentagem' => $variacaoPercentualPacotes,
        ];


        $all_clis = Cliente::all();
        $totalPendente = $all_clis->sum(function($cliente) {
            return $cliente->invoices->sum(function($invoice) {
                return $invoice->valor_pendente();
            });
        });

        $all_despacho = Fornecedor::where('tipo', 'despachante')->get();
        $totalDespacho = $all_despacho->sum(function($despachante) {
            return $despachante->despesas->sum(function($despesa) {
                return $despesa->valor_pendente();
            });
        });

        $all_embarcador = Fornecedor::where('tipo', 'embarcador')->get();
        $totalEmbarque = $all_embarcador->sum(function($embarcador) {
            return $embarcador->despesas->sum(function($despesa) {
                return $despesa->valor_pendente();
            });
        });

        // $cargasDaSemana = Carga::whereBetween('data_recebida', [$startOfWeek, $endOfWeek])->get();
        return view('admin.index', compact('cargasDaSemana', 'cargaCard', 'miamiCard', 'clientesCard', 'pacotesCard', 'cargasDaSemana', 'tipoCarga', 'totalPendente', 'totalDespacho', 'totalEmbarque')); 
    }
}
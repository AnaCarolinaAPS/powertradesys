<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Carga;
use App\Models\FaturaCarga;


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
        // $cargasDaSemana = Carga::whereBetween('data_recebida', [$startOfWeek, $endOfWeek])->get();
        // Buscar as FaturaCarga cujas cargas têm a data_recebida dentro da semana
        $cargasDaSemana = FaturaCarga::whereHas('carga', function($query) use ($startOfWeek, $endOfWeek) {
            $query->whereBetween('data_recebida', [$startOfWeek, $endOfWeek]);
        })->get();

        //Caso na semana atual não tenha uma carga ainda
        if ($cargasDaSemana->isEmpty()) {
            $currentDate = $currentDate->subWeek();
            $startOfWeek = Carbon::now()->startOfWeek();
            $endOfWeek = Carbon::now()->endOfWeek();
            $cargasDaSemana = FaturaCarga::whereHas('carga', function($query) use ($startOfWeek, $endOfWeek) {
                $query->whereBetween('data_recebida', [$startOfWeek, $endOfWeek]);
            })->get();
        }

        $peso = 0;
        $cobrado = 0;
        $totalInvoices = 0;
        $totalClientes = 0;
        $totalPacotes = 0;

        foreach ($cargasDaSemana as $fatura_carga) {
            $peso += $fatura_carga->invoices_pesos_total();
            $cobrado += $fatura_carga->invoices_pagas();
            $totalInvoices += $fatura_carga->valor_total();
            $totalClientes += $fatura_carga->carga->clientes->count();
            $totalPacotes += $fatura_carga->carga->pacotes->count();
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

        if ($totalInvoices > 0) {
            $variacaoPercentualCobrada = (($cobrado * 100) / $totalInvoices) ;
        } else {
            $variacaoPercentualCobrada = 0; // Caso a semana anterior seja 0, não há como calcular variação percentual
        }

        $cobradoCard = [
            'valor' => $cobrado,
            'porcentagem' => $variacaoPercentualCobrada,
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

        // $cargasDaSemana = Carga::whereBetween('data_recebida', [$startOfWeek, $endOfWeek])->get();
        return view('admin.index', compact('cargasDaSemana', 'cargaCard', 'cobradoCard', 'clientesCard', 'pacotesCard')); // Isso assume que você possui uma vista chamada 'admin.dashboard.index'
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Models\Caixa;

class FaturaCarga extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero',
        'carga_id',
        'servico_id',
    ];

    public function carga()
    {
        return $this->belongsTo(Carga::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'fatura_carga_id', 'id');
    }

    public function servico()
    {
        return $this->belongsTo(Servico::class, 'servico_id', 'id');
    }

    public function despesas()
    {
        return $this->hasMany(Despesa::class);
    }

    public function valor_total(){
        return $this->invoices->sum(function ($invoice) {
            return $invoice->valor_total();
        });
    }

    public function invoices_pagas()
    {
        return $this->invoices->sum(function ($invoice) {
            return $invoice->valor_pago();
        });
    }

    public function despesas_total(){
        return $this->despesas->sum(function ($despesa) {
            return $despesa->valor_total();
        });
    }

    public function despesas_pagas()
    {
        return $this->despesas->sum(function ($despesa) {
            return $despesa->valor_pago();
        });
    }

    public function invoices_pesos_total()
    {
        return $this->invoices->sum(function ($invoice) {
            return $invoice->peso_pacote();
        });
    }

    public function invoices_pesos_orig()
    {
        return $this->invoices->sum(function ($invoice) {
            return $invoice->peso_pacote_orig();
        });
    }

    public function invoices_qtd_orig()
    {
        return $this->invoices->sum(function ($invoice) {
            return $invoice->qtd_pacote_orig();
        });
    }

    public function lucro () {
        return $this->valor_total() - $this->despesas_total();
    }

    //Função para Calcular os Registros de Gastos da Semana Referente a essa Carga
    public function calcularGastosSemanaUs() {
        $dataRecebida = $this->carga->data_recebida;

        $startOfWeek = Carbon::parse($dataRecebida)->startOfWeek(\Carbon\Carbon::SUNDAY);
        $endOfWeek = Carbon::parse($dataRecebida)->endOfWeek(\Carbon\Carbon::SUNDAY);

        $caixasComMoeda = Caixa::where('moeda', '=', 'U$')->pluck('id');

        $mes = Carbon::parse($dataRecebida)->month;
        $ano = Carbon::parse($dataRecebida)->year;

        $fechamentosNaSemana = FechamentoCaixa::whereIn('caixa_id', $caixasComMoeda)
                ->whereMonth('start_date', $mes)
                ->whereYear('start_date', $ano)
                ->pluck('id');

        $totalGastosUs = FluxoCaixa::whereIn('fechamento_origem_id', $fechamentosNaSemana)
            ->where(function ($query) {
                $query->where('tipo', 'saida')
                    ->orWhere('tipo', 'salario');
            })
            ->whereBetween('data', [$startOfWeek, $endOfWeek])
            ->sum('valor_origem');

        return $totalGastosUs;
    }
}

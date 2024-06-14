<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FechamentoCaixa extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'start_date',
        'end_date',
        'caixa_id',
        'saldo_inicial',
        'saldo_final',
    ];

    public function caixa()
    {
        return $this->belongsTo(Caixa::class, 'caixa_id');
    }

    public function transacoesOrigem()
    {
        return $this->hasMany(FluxoCaixa::class, 'fechamento_origem_id', 'id');
    }

    public function transacoesDestino()
    {
        return $this->hasMany(FluxoCaixa::class, 'fechamento_destino_id', 'id');
    }

    // Calcula o Saldo do Caixa sem precisar fazer (atualizaSaldo)
    public function calculaSaldo(){
        $entradas = $this->transacoesOrigem()->where('tipo', 'entrada')->sum('valor_origem');
        $saidas = $this->transacoesOrigem()->where('tipo', 'saida')->sum('valor_origem');
        $despesas = $this->transacoesOrigem()->where('tipo', 'despesa')->sum('valor_origem');
        $salarios = $this->transacoesOrigem()->where('tipo', 'salario')->sum('valor_origem');

        // // Processando transferências
        $transferenciasSaida = $this->transacoesOrigem()->where('tipo', 'transferencia')->sum('valor_origem');
        $transferenciasEntrada = $this->transacoesDestino()->where('tipo', 'transferencia')->sum('valor_destino');

        // // Processando câmbios
        $cambiosSaida = $this->transacoesOrigem()->where('tipo', 'cambio')->sum('valor_origem');
        $cambiosEntrada = $this->transacoesDestino()->where('tipo', 'cambio')->sum('valor_destino');

        $saldo = $this->saldo_inicial + $entradas + $transferenciasEntrada + $cambiosEntrada + ($saidas + $transferenciasSaida + $cambiosSaida + $despesas + $salarios);

        return $saldo;
    }
}

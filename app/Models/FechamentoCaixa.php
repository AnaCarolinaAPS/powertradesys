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
        // 'ano',
        // 'mes',
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

    public function fluxos()
    {
        return $this->hasMany(FluxoCaixa::class, 'fechamento_caixa_id', 'id');
    }

    // // Dentro do modelo FechamentoCaixa
    // public function atualizaSaldo($valor){
    //     try {
    //         $saldo_atualizado = $this->saldo_final + $valor;

    //         $this->update([
    //             'saldo_final' => $saldo_atualizado,
    //             // Adicione outros campos conforme necessário
    //         ]);

    //         return true;
    //     } catch (\Exception $e) {
    //         return false;
    //     }
    // }

    // Calcula o Saldo do Caixa sem precisar fazer (atualizaSaldo)
    public function calculaSaldo(){
        $entradas = $this->fluxos()->where('tipo', 'entrada')->sum('valor_origem');
        $saidas = $this->fluxos()->where('tipo', 'saida')->sum('valor_origem');
        $despesas = $this->fluxos()->where('tipo', 'despesa')->sum('valor_origem');

        // Processando transferências
        $transferenciasSaida = $this->fluxos()->where('tipo', 'transferencia')->where('caixa_origem_id', $this->caixa_id)->sum('valor_origem');
        $transferenciasEntrada = $this->fluxos()->where('tipo', 'transferencia')->where('caixa_destino_id', $this->caixa_id)->sum('valor_destino');

        // Processando câmbios
        $cambiosSaida = $this->fluxos()->where('tipo', 'cambio')->where('caixa_origem_id', $this->caixa_id)->sum('valor_origem');
        $cambiosEntrada = $this->fluxos()->where('tipo', 'cambio')->where('caixa_destino_id', $this->caixa_id)->sum('valor_destino');

        $saldo = $this->saldo_inicial + $entradas + $transferenciasEntrada + $cambiosEntrada + ($saidas + $transferenciasSaida + $cambiosSaida + $despesas);

        return $saldo;
    }
}

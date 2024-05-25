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

    public function invoices()
    {
        return $this->hasMany(FluxoCaixa::class, 'fechamento_caixa_id', 'id');
    }

    // Dentro do modelo FechamentoCaixa
    public function atualizaSaldo($valor){
        try {
            $saldo_atualizado = $this->saldo_final + $valor;

            $this->update([
                'saldo_final' => $saldo_atualizado,
                // Adicione outros campos conforme necessário
            ]);

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}

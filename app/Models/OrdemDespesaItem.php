<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdemDespesaItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'ordem_despesa_id',
        'motor_proovedor_servico_id',
        'referencia',
        'valor',
        'tipo_moeda',
    ];

    public function despesa()
    {
        return $this->belongsTo(OrdemDespesa::class, 'ordem_despesa_id');
    }

    public function servico()
    {
        return $this->belongsTo(MotorProovedorServico::class, 'motor_proovedor_servico_id');
    }
}

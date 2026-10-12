<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MotorProovedorServico extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tipo_servico',
        'descricao',
        'preco',
        'tipo_moeda',
        'data_inicio',
        'data_fim',
        'motor_proovedor_id',
        'motor_id',
    ];

    public function proovedor()
    {
        return $this->belongsTo(MotorProovedor::class);
    }

    public function motor()
    {
        return $this->belongsTo(Motor::class);
    }

    public function despesa_items()
    {
        return $this->hasMany(OrdemDespesaItem::class);
    }
}

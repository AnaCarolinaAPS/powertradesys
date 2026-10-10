<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdemDespesa extends Model
{
    use HasFactory;

    protected $fillable = [
        'data',
        'ordem_id',
        'motor_proovedors_id',
    ];

    public function ordem()
    {
        return $this->belongsTo(Ordem::class);
    }

    public function proovedor()
    {
        return $this->belongsTo(MotorProovedor::class, 'motor_proovedors_id');
    }

    public function despesa_items()
    {
        return $this->hasMany(OrdemDespesaItem::class);
    }

    public function pagamentos()
    {
        return $this->belongsToMany(Pagamento::class, 'ordem_despesas_pagamentos')->withPivot('valor_recebido');;
    } 

    public function valor_total()
    {
        return $this->despesa_items->sum('valor');
    }

    //Para resgatar todos os pagamentos associados a despesa da ordem
    public function valor_pago()
    {
        return $this->pagamentos->sum('pivot.valor_recebido');
    }

    //Para retirar o valor pendente
    public function valor_pendente()
    {
        return $this->valor_total() - $this->valor_pago();
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ordem extends Model
{
    use HasFactory;

    protected $fillable = [
        'data_compra',
        'referencia',
        'cliente',
        'data_recebido',  
        'data_entrega',
    ];

    public function itens() {
        return $this->hasMany(OrdemItem::class);
    }

    public function valor_total()
    {
        return $this->itens->sum(function ($item) {
            return $item->quantidade*$item->valor;
        });
    }

    public function pagamentos()
    {
        return $this->belongsToMany(Pagamento::class, 'ordem_pagamentos')->withPivot('valor_recebido');;
    }   

    //Para resgatar todos os pagamentos associados a invoice
    public function valor_pago()
    {
        return $this->pagamentos->sum('pivot.valor_recebido');
    }

    //Para retirar o valor pendente
    public function valor_pendente()
    {
        return $this->valor_total() - $this->valor_pago();
    }

    public function despesas()
    {
        return $this->hasMany(OrdemDespesa::class);
    }
}

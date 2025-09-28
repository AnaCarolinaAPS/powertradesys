<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Despesa extends Model
{
    use HasFactory;

    protected $fillable = [
        'data',
        'fatura_carga_id',
        'fornecedor_id',
    ];

    public function fatura_carga()
    {
        return $this->belongsTo(FaturaCarga::class);
    }

    public function fornecedor()
    {
        return $this->belongsTo(Fornecedor::class);
    }

    public function despesa_items()
    {
        return $this->hasMany(DespesaItem::class);
    }

    public function pagamentos()
    {
        return $this->belongsToMany(Pagamento::class, 'despesa_pagamentos')->withPivot('valor_recebido');;
    }

    //Para resgatar os valores dos itens (Total do Valor da Despesa)
    public function valor_total()
    {
        return $this->despesa_items->sum('valor');
    }

    //Para resgatar todos os pagamentos associados a despesa
    public function valor_pago()
    {
        return $this->pagamentos->sum('pivot.valor_recebido');
        // $valor_pago = 0;
        // foreach ($this->pagamentos as $pgto) {
        //     $valor_pago += $pgto->despesas->find($this->id)->pivot->valor_recebido;
        // }
        // return $valor_pago;
    }

    //Para retirar o valor pendente
    public function valor_pendente()
    {
        return $this->valor_total() - $this->valor_pago();
    }

}

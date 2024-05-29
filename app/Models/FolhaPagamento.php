<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FolhaPagamento extends Model
{
    use HasFactory;

    protected $fillable = [
        'funcionario_id',
        'periodo',
    ];

    public function funcionario()
    {
        return $this->belongsTo(Funcionario::class);
    }

    public function items()
    {
        return $this->hasMany(FolhaPagamentoItem::class, 'folha_pagamento_id');
    }

    public function pagamentos()
    {
        return $this->belongsToMany(Pagamento::class, 'folha_pagamento_pagamentos')->withPivot('valor_recebido');;
    }

    //Para resgatar os valores dos itens (Total do Valor da Despesa)
    public function valor_total()
    {
        return $this->items->sum('valor');
    }

    //Para resgatar todos os pagamentos associados a despesa
    public function valor_pago()
    {
        return $this->pagamentos->sum('pivot.valor_recebido');
    }
}

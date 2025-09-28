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

    //Para resgatar os valor da folha de pagamento
    public function valor_total()
    {
        return $this->items->sum('valor');
        // return $this->items
        // ->groupBy(function ($item) {
        //     return $item->servicosF->moeda;
        // })
        // ->map(function ($group) {
        //     return $group->sum('valor');
        // });
    }

    //Para resgatar todos os pagamentos associados a essa folha de pagamento
    public function valor_pago()
    {
        return $this->pagamentos->sum('pivot.valor_recebido');
    }

    //Para o total pendente da folha de pagamento
    public function valor_pendente()
    {
        return $this->items->sum('valor')-$this->pagamentos->sum('pivot.valor_recebido');
    }
}

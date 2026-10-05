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
}

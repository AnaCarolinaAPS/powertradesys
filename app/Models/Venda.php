<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venda extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'data',
        'numero_factura',
        'cliente_id',
        'condicao_venda',
        'impresso',
        'cancelado',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function itens() {
        return $this->hasMany(ItemVenda::class);
    }

    public function quantidade_total()
    {
        return $this->itens->sum(function ($itens) {
            return $itens->quantidade;
        });
    }

    public function valor_total()
    {
        return $this->itens->sum(function ($item) {
            return $item->quantidade*$item->valor_unitario;
        });
    }

}

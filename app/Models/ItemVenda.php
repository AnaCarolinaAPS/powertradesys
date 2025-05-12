<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemVenda extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'venda_id',
        'produto_id',
        'quantidade',
        'valor_unitario',
        'valor_de_venta',
    ];

    public function venda() {
        return $this->belongsTo(Venda::class, 'venda_id');
    }

    public function produto() {
        return $this->belongsTo(Produto::class, 'produto_id');
    }

}

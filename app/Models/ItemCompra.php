<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemCompra extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'compra_id',
        'produto_id',
        'quantidade',
        'valor_unitario',
    ];

    public function compra() {
        return $this->belongsTo(Compra::class, 'compra_id');
    }

    public function produto() {
        return $this->belongsTo(Produto::class, 'produto_id');
    }
}

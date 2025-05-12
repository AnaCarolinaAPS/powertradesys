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

}

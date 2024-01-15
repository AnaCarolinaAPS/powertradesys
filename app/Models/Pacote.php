<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pacote extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'rastreio',
        'qtd',
        'peso_aprox',
        'peso',
        'warehouse_id',
        'cliente_id',
        'carga_id',
        'observacoes',
        'altura',
        'largura',
        'profundidade',
        'volume',
    ];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function carga()
    {
        return $this->belongsTo(Carga::class);
    }

    public function entrega_pacote()
    {
        return $this->belongsTo(EntregaPacote::class);
    }
}

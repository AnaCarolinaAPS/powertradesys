<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FluxoCaixa extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'data',
        'descricao',
        'tipo',
        'caixa_origem_id',
        'valor_origem',
        'caixa_destino_id',
        'valor_destino',
        'categoria_id',
        'subcategoria_id',
        'fechamento_caixa_id',
    ];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function subcategoria()
    {
        return $this->belongsTo(Categoria::class, 'subcategoria_id');
    }

    public function caixaOrigem()
    {
        return $this->belongsTo(Caixa::class, 'caixa_origem_id');
    }

    public function caixaDestino()
    {
        return $this->belongsTo(Caixa::class, 'caixa_destino_id');
    }

    public function fechamento()
    {
        return $this->belongsTo(FechamentoCaixa::class, 'fechamento_caixa_id');
    }

    public function pagamento()
    {
        return $this->hasOne(Pagamento::class);
    }
}

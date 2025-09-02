<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContasFixas extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'descricao',
        'moeda',
        'valor',
        'data_vencimento',
        'categoria_id',
        'subcategoria_id',
        'ativa',
    ];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function subcategoria()
    {
        return $this->belongsTo(Categoria::class, 'subcategoria_id');
    }

    public function contaspagars()
    {
        return $this->hasMany(ContasPagar::class, 'contas_fixa_id');
    }

}

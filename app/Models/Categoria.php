<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nome',
        'observacoes',
        'tipo',
    ];

    public function transacoes()
    {
        return $this->hasMany(FluxoCaixa::class, 'categoria_id');
    }

    public function transacoesSubcategoria()
    {
        return $this->hasMany(FluxoCaixa::class, 'subcategoria_id');
    }
}

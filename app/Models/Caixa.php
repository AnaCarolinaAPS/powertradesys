<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Caixa extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nome',
        'moeda',
        'observacoes',
        'aberto',
    ];

    // public function transacoesOrigem()
    // {
    //     return $this->hasMany(FluxoCaixa::class, 'caixa_origem_id');
    // }

    // public function transacoesDestino()
    // {
    //     return $this->hasMany(FluxoCaixa::class, 'caixa_destino_id');
    // }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fornecedor extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nome',
        'contato',
        'tipo',
    ];

    public function servicos()
    {
        return $this->hasMany(ServicosFornecedor::class);
    }

    public function cargas()
    {
        return $this->hasMany(Carga::class);
    }

    public function despesas()
    {
        return $this->hasMany(Despesa::class);
    }

    public function totalPendente()
    {
        // Pegar todas as despesas do fornecedor e somar os valores pendentes
    return $this->despesas->sum(function ($despesa) {
        return $despesa->valor_pendente();
    });
    }
}

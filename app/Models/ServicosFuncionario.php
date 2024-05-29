<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServicosFuncionario extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'descricao',
        'valor',
        'tipo',
        'moeda',
        'frequencia',
        'data_inicio',
        'data_fim',
        'funcionario_id',
    ];

    public function funcionario()
    {
        return $this->belongsTo(Funcionario::class);
    }

    public function folha_pagamento_items()
    {
        return $this->hasMany(FolhaPagamentoItem::class, 'servicos_funcionario_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FolhaPagamentoItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'folha_pagamento_id',
        'servicos_funcionario_id',
        'data',
        'referencia',
        'valor',
    ];

    public function folha_pagamento()
    {
        return $this->belongsTo(FolhaPagamento::class, 'folha_pagamento_id');
    }

    public function servicosF()
    {
        return $this->belongsTo(ServicosFuncionario::class, 'servicos_funcionario_id');
    }
}

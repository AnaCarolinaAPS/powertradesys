<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContasPagar extends Model
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
        'contas_fixa_id',
    ];

    protected $appends = ['valor_pendente'];

    public function getValorPendenteAttribute()
    {
        return $this->valor_pendente(); // aproveita seu método
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function subcategoria()
    {
        return $this->belongsTo(Categoria::class, 'subcategoria_id');
    }

    public function contafixa()
    {
        return $this->belongsTo(ContasFixas::class, 'contas_fixa_id');
    }

    public function pagamentos()
    {
        return $this->belongsToMany(Pagamento::class, 'conta_pagar_pagamentos')->withPivot('valor_recebido');;
    }

    //Para resgatar todos os pagamentos associados a conta
    public function valor_pago()
    {
        return $this->pagamentos->sum('pivot.valor_recebido');
    }

    //Para retirar o valor pendente
    public function valor_pendente()
    {
        return $this->valor - $this->valor_pago();
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pagamento extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'invoice_id',
        'valor',
        'data_pagamento',
        'observacoes',
        'fluxo_caixa_id',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function fluxo_caixa()
    {
        return $this->belongsTo(FluxoCaixa::class);
    }
}

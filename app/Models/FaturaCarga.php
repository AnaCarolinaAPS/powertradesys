<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FaturaCarga extends Model
{
    use HasFactory;

    protected $fillable = [
        'data',
        'numero',
        'carga_id',
        'servico_id',
    ];

    public function carga()
    {
        return $this->belongsTo(Carga::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'fatura_carga_id', 'id');
    }

    public function servico()
    {
        return $this->belongsTo(Servico::class, 'servico_id', 'id');
    }

    public function valor_total(){
        return $this->invoices->sum(function ($invoice) {
            return $invoice->valor_total();
        });
    }

    public function valor_pagado(){
        $total = 0;

        foreach ($this->invoices as $invoice) {
            $total += $invoice->valor_total();
        }
    }
}

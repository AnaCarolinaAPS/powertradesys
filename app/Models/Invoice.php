<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'data',
        // 'valor_pago',
        'cliente_id',
        'fatura_carga_id',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function fatura_carga()
    {
        return $this->belongsTo(FaturaCarga::class, 'fatura_carga_id');
    }

    public function invoice_pacotes()
    {
        return $this->hasMany(InvoicePacote::class);
    }

    public function pagamentos()
    {
        return $this->belongsToMany(Pagamento::class, 'invoice_pagamentos')->withPivot('valor_recebido');;
    }

    //Para resgatar os valores dos pacotes (Total do Valor da Invoice)
    public function valor_total()
    {
        return $this->invoice_pacotes->sum('valor');
    }

    //Para resgatar todos os pagamentos associados a invoice
    public function valor_pago()
    {
        return $this->pagamentos->sum('pivot.valor_recebido');
        // $valor_pago = 0;
        // foreach ($this->pagamentos as $pgto) {
        //     $valor_pago += $pgto->invoices->find($this->id)->pivot->valor_recebido;
        // }
        // return $valor_pago;
    }
}

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
        'valor',
        'data_pagamento',
        'observacoes',
        'fluxo_caixa_id',
    ];

    public function invoices()
    {
        return $this->belongsToMany(Invoice::class, 'invoice_pagamentos');
    }

    public function fluxo_caixa()
    {
        return $this->belongsTo(FluxoCaixa::class);
    }

    //Para resgatar os valores dos pagamentos de DETERMINADA INVOICE
    public function getValorPagoForInvoice($invoiceId)
    {
        // Procurar a invoice pelo ID e retornar o valor pago associado a ela
        $invoice = $this->invoices()->find($invoiceId);
        if ($invoice) {
            return $invoice->pivot->valor_recebido;
        } else {
            return null; // Ou algum outro valor padrão, se preferir
        }
    }
}

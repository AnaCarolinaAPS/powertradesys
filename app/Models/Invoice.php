<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'data',
        'valor_pago',
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
        return $this->hasMany(Pagamento::class, 'invoice_pagamento');
    }

    // Dentro do modelo Invoice
    public function atualizaPago($valor){
        try {
            $saldo_atualizado = $this->valor_pago + $valor;

            $this->update([
                'valor_pago' => $saldo_atualizado,
                // Adicione outros campos conforme necessário
            ]);

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    //Para resgatar os valores dos pacotes (Total do Valor da Invoice)
    public function valor_total()
    {
        return $this->invoice_pacotes->sum('valor');
    }
}

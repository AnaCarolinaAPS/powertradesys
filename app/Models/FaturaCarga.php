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

    public function despesas()
    {
        return $this->hasMany(Despesa::class);
    }

    public function valor_total(){
        return $this->invoices->sum(function ($invoice) {
            return $invoice->valor_total();
        });
    }

    public function invoices_pagas()
    {
        return $this->invoices->sum(function ($invoice) {
            return $invoice->valor_pago();
        });
    }

    public function despesas_total(){
        return $this->despesas->sum(function ($despesa) {
            return $despesa->valor_total();
        });
    }

    public function despesas_pagas()
    {
        return $this->despesas->sum(function ($despesa) {
            return $despesa->valor_pago();
        });
    }

    public function invoices_pesos_total()
    {
        return $this->invoices->sum(function ($invoice) {
            return $invoice->peso_pacote();
        });
    }

    public function invoices_pesos_orig()
    {
        return $this->invoices->sum(function ($invoice) {
            return $invoice->peso_pacote_orig();
        });
    }
}

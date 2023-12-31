<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'data',
        'cliente_id',
        'fatura_cargas_id',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function fatura_carga()
    {
        return $this->belongsTo(FaturaCarga::class);
    }

    public function invoicePacotes()
    {
        return $this->hasMany(InvoicePacote::class);
    }

}

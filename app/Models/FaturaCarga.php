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
}

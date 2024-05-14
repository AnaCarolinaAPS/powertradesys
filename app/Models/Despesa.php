<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Despesa extends Model
{
    use HasFactory;

    protected $fillable = [
        'data',
        'fatura_carga_id',
        'fornecedor_id',
    ];

    public function fatura_carga()
    {
        return $this->belongsTo(FaturaCarga::class);
    }

    public function fornecedor()
    {
        return $this->belongsTo(Fornecedor::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'data',
        'numero',
        'status',
        'cliente_id',
        'carga_id',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function carga()
    {
        return $this->belongsTo(Carga::class);
    }
}

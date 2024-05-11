<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntregaPacote extends Model
{
    use HasFactory;

    protected $fillable = [
        'peso',
        'qtd',
        'pacote_id',
        'entrega_id',
        'referencia',
    ];

    public function entrega()
    {
        return $this->belongsTo(Entrega::class);
    }

    public function pacote()
    {
        return $this->belongsTo(Pacote::class);
    }
}

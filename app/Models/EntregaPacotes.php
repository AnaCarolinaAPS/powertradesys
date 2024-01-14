<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntregaPacotes extends Model
{
    use HasFactory;

    protected $fillable = [
        'data',
        'hora',
        'responsavel',
        'freteiro_id',
        'cliente_id',
    ];

    public function entrega()
    {
        return $this->belongsTo(Entrega::class);
    }
}

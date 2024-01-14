<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entrega extends Model
{
    use HasFactory;

    protected $fillable = [
        'peso',
        'qtd',
        'pacote_id',
        'entrega_id',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function freteiro()
    {
        return $this->belongsTo(Freteiro::class);
    }

    public function entrega_pacotes()
    {
        return $this->hasMany(EntregaPacotes::class);
    }
}

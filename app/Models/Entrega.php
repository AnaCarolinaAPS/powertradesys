<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entrega extends Model
{
    use HasFactory;

    protected $fillable = [
        'data',
        'hora',
        'responsavel',
        'freteiro_id',
        'cliente_id',
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
        return $this->hasMany(EntregaPacote::class);
    }
}

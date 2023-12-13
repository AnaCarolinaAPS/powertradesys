<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carga extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'data_enviada',
        'data_recebida',
        'despachante_id',
        'embarcador_id',
    ];

    public function pacotes()
    {
        return $this->hasMany(Pacote::class);
    }

    public function despachante()
    {
        return $this->belongsTo(Despachante::class);
    }

    public function embarcador()
    {
        return $this->belongsTo(Embarcador::class);
    }
}

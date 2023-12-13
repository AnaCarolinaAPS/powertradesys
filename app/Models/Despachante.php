<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Despachante extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nome',
        'contato',
    ];

    public function servicos()
    {
        return $this->hasMany(ServicosDespachante::class);
    }

    public function cargas()
    {
        return $this->hasMany(Carga::class);
    }
}

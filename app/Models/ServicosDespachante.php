<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServicosDespachante extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'descricao',
        'data_inicio',
        'data_fim',
        'despachante_id',
    ];

    public function despachante()
    {
        return $this->belongsTo(Despachante::class);
    }
}

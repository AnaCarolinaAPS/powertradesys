<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servico extends Model
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
        'preco',
        'tipo',
    ];

    public function faturaCargas()
    {
        return $this->hasMany(FaturaCarga::class);
    }
}

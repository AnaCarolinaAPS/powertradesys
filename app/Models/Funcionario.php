<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Funcionario extends Model
{
    use HasFactory;

    protected $fillable = [
        'ci',
        'nome',
        'email',
        'contato',
        'cargo',
        'data_contratacao'
    ];

    public function servicos()
    {
        return $this->hasMany(ServicosFuncionario::class);
    }
}

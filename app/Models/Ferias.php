<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ferias extends Model
{
    use HasFactory;

    protected $table = 'ferias';

    protected $fillable = [
        'funcionario_id',
        'data_inicio',
        'data_fim',
        'observacao',
    ];

    // Definindo o relacionamento com a entidade Funcionário
    public function funcionario()
    {
        return $this->belongsTo(Funcionario::class);
    }
    
}

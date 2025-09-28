<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'wr',
        'data',
        'observacoes',
        'shipper_id',
        'embarcador_id',
    ];

    public function shipper()
    {
        return $this->belongsTo(Shipper::class);
    }

    public function pacotes()
    {
        return $this->hasMany(Pacote::class);
    }

    public function embarcador()
    {
        return $this->belongsTo(Fornecedor::class, 'embarcador_id');
    }

    //Para resgatar a quantidade dos pacotes (Total de Pacotes da Warehouse)
    public function total_pacotes()
    {
        return $this->pacotes->sum(function($pacote) {
            return $pacote->qtd ?? 0;
        });
    }

    //Para resgatar os valores dos pacotes (Total do Peso da Warehouse)
    public function total_peso()
    {
        return $this->pacotes->sum(function($pacote) {
            return $pacote->peso ?? 0;
        });
    }
}

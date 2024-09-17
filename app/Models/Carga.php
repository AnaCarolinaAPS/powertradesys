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
        'observacoes',
        'despachante_id',
        'embarcador_id',
        'transportadora_id',
        'fatura_carga_id',
        'peso_guia',
        'guia_aerea',
    ];

    public function pacotes()
    {
        return $this->hasMany(Pacote::class);
    }

    public function despachante()
    {
        return $this->belongsTo(Fornecedor::class, 'despachante_id');
    }

    public function embarcador()
    {
        return $this->belongsTo(Fornecedor::class, 'embarcador_id');
    }

    public function transportadora()
    {
        return $this->belongsTo(Fornecedor::class, 'transportadora_id');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function clientes()
    {
        return $this->hasManyThrough(Cliente::class, Pacote::class, 'carga_id', 'id', 'id', 'cliente_id');
    }

    public function fatura_carga()
    {
        return $this->belongsTo(FaturaCarga::class);
    }   
}

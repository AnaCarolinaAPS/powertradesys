<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PacotesPendentes extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'rastreio',
        'cliente_id',
        'data_pedido',
        'data_miami',
        'status',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
}

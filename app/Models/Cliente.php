<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $table = 'clientes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'caixa_postal',
        'apelido',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pacotes()
    {
        return $this->hasMany(Pacote::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function entregas()
    {
        return $this->hasMany(Entrega::class);
    }
}

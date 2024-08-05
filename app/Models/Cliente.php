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

    public function invoicesPendentes()
    {
        return $this->invoices->filter(function ($invoice) {
            return $invoice->valor_pendente() > 0;
        });
    }

    public function creditos()
    {
        return $this->hasMany(Credito::class);
    }

    //Para resgatar os valores dos pacotes (Total do Valor da Invoice)
    public function total_creditos()
    {
        return $this->creditos->sum('valor_credito');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nome',
        'descricao',
    ];

    public function itensDeCompra() {
        return $this->hasMany(ItemCompra::class);
    }

    public function quantidade_comprada()
    {
        return $this->itensDeCompra->sum(function ($itens) {
            return $itens->quantidade;
        });
    }

}

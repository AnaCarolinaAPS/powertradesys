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

    public function itensDeVenda() {
        return $this->hasMany(ItemVenda::class);
    }

    public function quantidade_comprada()
    {
        return $this->itensDeCompra->sum(function ($itens) {
            return $itens->quantidade;
        });
    }

    public function quantidade_vendida()
    {
        return $this->itensDeVenda->sum(function ($itens) {
            return $itens->quantidade;
        });
    }

    public function quantidade_estoque()
    {
        return $this->quantidade_comprada() - $this->quantidade_vendida();
    }

    public function scopeComEstoque($query){
        return $query->get()->filter(function ($produto) {
            return $produto->quantidade_estoque() > 0;
        });
    }

}

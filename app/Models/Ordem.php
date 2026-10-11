<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ordem extends Model
{
    use HasFactory;

    protected $fillable = [
        'data_compra',
        'referencia',
        'cliente',
        'data_recebido',  
        'data_entrega',
    ];

    public function itens() {
        return $this->hasMany(OrdemItem::class);
    }

    public function valor_total()
    {
        return $this->itens->sum(function ($item) {
            return $item->quantidade*$item->valor;
        });
    }

    public function pagamentos()
    {
        return $this->belongsToMany(Pagamento::class, 'ordem_pagamentos')->withPivot('valor_recebido');;
    }   

    //Para resgatar todos os pagamentos associados a ordem
    public function valor_pago()
    {
        return $this->pagamentos->sum('pivot.valor_recebido');
    }

    //Para retirar o valor pendente
    public function valor_pendente()
    {
        return $this->valor_total() - $this->valor_pago();
    }

    public function despesas()
    {
        return $this->hasMany(OrdemDespesa::class);
    }

    public function despesas_total(){
        $totais = [
            'dolar' => 0,
            'reais' => 0,
            'guaranies' => 0,
        ];

        foreach ($this->despesas as $despesa) {
            foreach ($despesa->despesa_items as $despesa_item) {
                $moeda = $despesa_item->tipo_moeda;

                if (array_key_exists($moeda, $totais)) {
                    $totais[$moeda] += $despesa_item->valor;
                }
            }
        }

        return $totais;
    }

    public function despesas_pendentes(){
        $totais = [
            'dolar' => 0,
            'reais' => 0,
            'guaranies' => 0,
        ];

        $pagos = [
            'dolar' => 0,
            'reais' => 0,
            'guaranies' => 0,
        ];

        foreach ($this->despesas as $despesa) {
            foreach ($despesa->despesa_items as $despesa_item) {
                $moeda = $despesa_item->tipo_moeda;

                if (array_key_exists($moeda, $totais)) {
                    $totais[$moeda] += $despesa_item->valor;
                }
            }

            foreach ($despesa->pagamentos as $despesa_pgto) {
                if (array_key_exists($moeda, $pagos)) {
                    $pagos[$moeda] += $despesa_pgto->valor;
                }
            }
        }

        $pendencias = [
            'dolar' => $totais['dolar'] - $pagos['dolar'],
            'reais' => $totais['reais'] - $pagos['reais'],
            'guaranies' => $totais['guaranies'] - $pagos['guaranies'],
        ];

        return $pendencias;
    }

    public function despesas_pagas()
    {
        return $this->despesas->sum(function ($despesa) {
            return $despesa->valor_pago();
        });
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venda extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'data',
        'numero_factura',
        'cliente_id',
        'condicao_venda',
        'impresso',
        'cancelado',
    ];

    function numeroPorExtensoEspanhol($numero) {
        $unidades = [
            '', 'uno', 'dos', 'tres', 'cuatro', 'cinco', 'seis', 'siete', 'ocho', 'nueve',
            'diez', 'once', 'doce', 'trece', 'catorce', 'quince',
            'dieciséis', 'diecisiete', 'dieciocho', 'diecinueve'
        ];

        $dezenas = [
            '', '', 'veinte', 'treinta', 'cuarenta', 'cincuenta',
            'sesenta', 'setenta', 'ochenta', 'noventa'
        ];

        $centenas = [
            '', 'ciento', 'doscientos', 'trescientos', 'cuatrocientos',
            'quinientos', 'seiscientos', 'setecientos', 'ochocientos', 'novecientos'
        ];

        if ($numero == 0) return 'cero';
        if ($numero == 100) return 'cien';

        $texto = '';

        // Milhares
        if ($numero >= 1000) {
            $milhar = intval($numero / 1000);
            $resto = $numero % 1000;

            if ($milhar == 1) {
                $texto .= 'mil';
            } else {
                $texto .= $this->numeroPorExtensoEspanhol($milhar) . ' mil';
            }

            if ($resto > 0) {
                $texto .= ' ' . $this->numeroPorExtensoEspanhol($resto);
            }

            return trim($texto);
        }

        // Centenas
        if ($numero >= 100) {
            $centena = intval($numero / 100);
            $resto = $numero % 100;

            $texto .= $centenas[$centena];

            if ($resto > 0) {
                $texto .= ' ' . $this->numeroPorExtensoEspanhol($resto);
            }

            return trim($texto);
        }

        // Dezenas
        if ($numero >= 20) {
            $dezena = intval($numero / 10);
            $unidade = $numero % 10;

            if ($numero < 30) {
                return 'veinti' . $unidades[$unidade];
            }

            $texto .= $dezenas[$dezena];

            if ($unidade > 0) {
                $texto .= ' y ' . $unidades[$unidade];
            }

            return trim($texto);
        }

        // Menor que 20
        return $unidades[$numero];
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function itens() {
        return $this->hasMany(ItemVenda::class);
    }

    public function quantidade_total()
    {
        return $this->itens->sum(function ($itens) {
            return $itens->quantidade;
        });
    }

    public function valor_total()
    {
        return $this->itens->sum(function ($item) {
            return $item->quantidade*$item->valor_unitario;
        });
    }

    public function total_iva5()
    {
        return $this->itens->sum(function ($item) {
            return $item->valor_de_venta === 'IVA5' ? $item->quantidade * $item->valor_unitario : 0;
        });
    }

    public function total_iva10()
    {
        return $this->itens->sum(function ($item) {
            return $item->valor_de_venta === 'IVA10' ? $item->quantidade * $item->valor_unitario : 0;
        });
    }

    public function total_extentas()
    {
        return $this->itens->sum(function ($item) {
            return $item->valor_de_venta === 'extentas' ? $item->quantidade * $item->valor_unitario : 0;
        });
    }

    public function valor_total_extenso() {

        $porExtenso = $this->numeroPorExtensoEspanhol($this->valor_total());
        
        return $porExtenso;
    }

}

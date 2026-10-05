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
}

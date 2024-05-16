<?php

namespace App\Models;

use App\Http\Controllers\ServicosFornecedorController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DespesaItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'despesa_id',
        'servico_fornecedor_id',
        'referencia',
        'valor',
    ];

    public function despesa()
    {
        return $this->belongsTo(Despesa::class);
    }

    public function servico_fornecedor()
    {
        return $this->belongsTo(ServicosFornecedor::class);
    }
}

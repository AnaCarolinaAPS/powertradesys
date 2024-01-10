<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoicePacote extends Model
{
    use HasFactory;

    protected $fillable = [
        'peso',
        'valor',
        'invoice_id',
        'pacote_id',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function pacote()
    {
        return $this->belongsTo(Pacote::class);
    }
}

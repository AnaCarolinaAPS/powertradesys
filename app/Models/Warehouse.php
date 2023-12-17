<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'wr',
        'data',
        'observacoes',
        'shipper_id',
        'embarcador_id',
    ];

    public function shipper()
    {
        return $this->belongsTo(Shipper::class);
    }

    public function pacotes()
    {
        return $this->hasMany(Pacote::class);
    }

    public function embarcador()
    {
        return $this->belongsTo(Embarcador::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Embarcador extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nome',
        'contato',
    ];

    public function cargas()
    {
        return $this->hasMany(Carga::class);
    }

    public function warehouses()
    {
        return $this->hasMany(Warehouse::class);
    }
}

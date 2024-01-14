<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Freteiro extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'contato',
    ];

    public function entregas()
    {
        return $this->hasMany(Entrega::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdemItem extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'ordem_id',
        'motor_id',
        'quantidade',
        'valor',
        'tipo_moeda',
    ];

    public function ordem() {
        return $this->belongsTo(Ordem::class, 'ordem_id');
    }

    public function motor() {
        return $this->belongsTo(Motor::class, 'motor_id');
    }
}

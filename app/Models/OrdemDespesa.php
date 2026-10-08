<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdemDespesa extends Model
{
    use HasFactory;

    protected $fillable = [
        'data',
        'ordem_id',
        'motor_proovedors_id',
    ];

    public function ordem()
    {
        return $this->belongsTo(Ordem::class);
    }

    public function proovedor()
    {
        return $this->belongsTo(MotorProovedor::class, 'motor_proovedors_id');
    }
}

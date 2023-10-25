<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isClient()
    {
        // return $this->role === 'client';
        // Verifica se o usuário tem a role de cliente
        if ($this->role === 'cliente') {
            // Verifica se o usuário tem um cliente associado
            return $this->client !== null;
        }
        return false;
    }

    public function isLogistics()
    {
        return $this->role === 'logistics';
    }

    public function isFinancial()
    {
        return $this->role === 'financial';
    }

    public function cliente()
    {
        return $this->hasOne(Cliente::class);
    }
}

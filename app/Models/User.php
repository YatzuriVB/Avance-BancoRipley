<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'usuarios_homebanking';
    protected $primaryKey = 'pkusuario';

    protected $fillable = [
        'username',
        'password_hash',
        'pkcliente',
        'activo',
    ];

    protected $hidden = [
        'password_hash',
        'token_refresh',
    ];

    protected function casts(): array
    {
        return [
            'ultimo_acceso' => 'datetime',
            'password_hash' => 'hashed',
        ];
    }

    // Laravel espera 'password' pero la columna se llama 'password_hash'
    public function getAuthPassword()
    {
        return $this->password_hash;
    }
}
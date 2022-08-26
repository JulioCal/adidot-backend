<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class trabajador extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $primaryKey = 'cedula';

    public $incrementing = false;

    protected $fillable = ['nombre', 'cedula', 'email', 'password', 'role', 'sexo', 'direccion', 'estatus', 'gerencia'];

    protected $hidden = [
        'password', 'remember_token'
    ];
}

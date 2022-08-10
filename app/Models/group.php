<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class group extends Model
{
    use HasFactory;
    protected $fillable = [
        'nombre',
        'owner_grupo',
        'integrantes',
    ];
    protected $casts = [
        'integrantes' => 'array',
    ];
}

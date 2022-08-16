<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class document extends Model
{
    use HasFactory;

    protected $fillable = [
        'file',
        'title',
        'text',
        'trabajador_cedula',
        'permit',
        'owner',
        'comments',
        'grupos'
    ];
    protected $casts = [
        'grupos' => 'array',
        'comments' => 'array',
    ];
    protected $primaryKey = 'document_id';
}

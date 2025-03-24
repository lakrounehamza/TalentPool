<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Annonce extends Model
{
    protected  $fillable  = [
        'titre',
        'description',
        'status',
        'recruteur_id',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recruteur extends Model
{
    protected  $fillable =['id',];
    public function annonces()
    {
        return  $this->hasMany(Annonce::class);
    }
}

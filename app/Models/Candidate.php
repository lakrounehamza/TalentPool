<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    protected $fillable = [
        'id',
    ];
    public function candidatures()
    {
        return $this->hasMany(Candidature::class);
    }
}

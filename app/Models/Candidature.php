<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Candidature extends User
{
    protected  $fillable = [
        'annonce_id',
        'candidate_id',
        'status',
        'cv'
    ];
    public function annonce()
    {
        return  $this->belongsTo(Annonce::class);
    }

    public function condidate()
    {
        return  $this->belongsTo(Candidate::class);
    }
}

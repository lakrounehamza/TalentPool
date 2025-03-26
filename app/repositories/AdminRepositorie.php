<?php

namespace App\Repositories;
use  App\Models\Candidate;
use  App\Models\Recruteur;
use  App\Models\Annonce;
use  App\Models\Candidature;

class AdminRepositorie
{
    /**
     * Create a new class instance.
     */
 public function global(){
        $recruteurs = Recruteur::all();
        $candidates = Candidate::all();
        $annonces = Annonce::all();
        $candidatures = Candidature::all();
        $data = [
            'recruteurs' => $recruteurs,
            'candidates' => $candidates,
            'annonces' => $annonces,
            'candidatures' => $candidatures,
        ];
        return $data;
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Candidate;
use App\Models\Annonce;
use App\Models\Candidature;
use Illuminate\Support\Facades\Auth;

class RecruteurController extends Controller
{
    public function recruteur() 
    { 
        $id = Auth::guard('api')->user()->id;

        $annonces = Annonce::where('recruteur_id', $id)->count();

        $candidatures = Candidature::join('annonces', 'annonces.id', '=', 'candidatures.annonce_id')
            ->where('annonces.recruteur_id', $id)
            ->count();
        $candidates = Candidate::join('candidatures', 'candidates.id', '=', 'candidatures.candidate_id')
            ->join('annonces', 'annonces.id', '=', 'candidatures.annonce_id')
            ->where('annonces.recruteur_id', $id)
            ->count();

        return response()->json([
            'annonces' => $annonces,
            'candidatures' => $candidatures,
            'candidates' => $candidates
        ]);
    }
}

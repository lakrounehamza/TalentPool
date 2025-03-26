<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Recruteur;
use App\Models\Candidate;
use App\Models\Annonce;
use App\Models\Candidature;

class AdminController extends Controller
{
   public function globales(){
         $recruteurs = Recruteur::count();
         $candidates = Candidate::count();
         $annonces = Annonce::count();
         $candidatures = Candidature::count();
         return response()->json([
              'recruteurs' => $recruteurs,
              'candidates' => $candidates,
              'annonces' => $annonces,
              'candidatures' => $candidatures
         ]);
   }
}

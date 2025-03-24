<?php

namespace App\repositories; 
use App\Contract\CandidatureRepositoryInterface;
use App\Models\Candidature;
use App\Models\Candidate;

class CandidatureReposirorie implements CandidatureRepositoryInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }
    public function getAllCandidature(){
        $candidature  = Candidature::all();
        return  $candidature;
    }
    public function getCandidatureById(Candidature $candidature){
        return $candidature;
    }
    public function deleteCandidature(Candidature $candidature){
        $candidature->delete();
    }
    public function createCandidature(array $attributes){
        Candidature::create($attributes);
    }
    public function updateCandidature(Candidature  $candidature ,array $attributes){
        $candidature->update($attributes);
    }
    public function getCandidatureByCandidat(Candidate $candidate){
        $candidatures = candidature::where('candidate_id',$candidate->id);
        return $candidatures;
    }
    public function getCandidatureByStatus($status){
        return candidature::where('status',$status);
    }
    public function getCandidatureByCandidatAndStatus(Candidate $candidate, $status){
        $candidatures  =  Candidature::where('candidate_id',$candidate->id)->where('status',$status);
        return $candidatures;
    }
}

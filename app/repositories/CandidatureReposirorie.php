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
        $candidatures = candidature::where('candidate_id',$candidate->id)->get();
        return $candidatures;
    }
    public function getCandidatureByStatus($status){
        return candidature::where('status',$status)->get();
    }
    public function getCandidatureByCandidatAndStatus(Candidate $candidate, $status){
        $candidatures  =  Candidature::where('candidate_id',$candidate->id)->where('status',$status)->get();
        return $candidatures;
    }
    
    public function notificationCandidature($candidatureId){
        $candidature = Candidature::find($candidatureId);
        
        if (!$candidature) {
            return [
                'success' => false,
                'message' => 'Candidature non trouvée'
            ];
        }
        
        $candidate = Candidate::find($candidature->candidate_id);
        $annonce = $candidature->annonce;
        
        if (!$candidate || !$annonce) {
            return [
                'success' => false,
                'message' => 'Informations associées non trouvées'
            ];
        }
        
        // Create notification data based on candidature status
        $notificationData = [
            'candidature_id' => $candidature->id,
            'annonce_id' => $annonce->id,
            'annonce_title' => $annonce->title,
            'status' => $candidature->status,
            'date' => $candidature->updated_at,
            'message' => $this->getStatusMessage($candidature->status)
        ];
        
        return [
            'success' => true,
            'message' => 'Notification de candidature',
            'data' => $notificationData
        ];
    }
    
    private function getStatusMessage($status) {
        switch ($status) {
            case 'pending':
                return 'Votre candidature est en attente de traitement.';
            case 'accepted':
                return 'Félicitations ! Votre candidature a été acceptée.';
            case 'rejected':
                return 'Nous regrettons de vous informer que votre candidature n\'a pas été retenue.';
            case 'interview':
                return 'Vous êtes invité(e) à un entretien pour cette candidature.';
            default:
                return 'Le statut de votre candidature a été mis à jour.';
        }
    }
}

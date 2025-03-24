<?php

namespace  App\Contract\CandidatureRepositoryInterface;

use  App\Models\Candidature;
use App\Models\Candidate;

interface CandidatureRepositoryInterface
{
    public function getAllCandidature();
    public function getCandidatureById(Candidature $candidature);
    public function deleteCandidature(Candidature $candidature);
    public function createCandidature(array $attributes);
    public function updateCandidature(Candidature  $candidature ,array $attributes);
    public function getCandidatureByCandidat(Candidate $candidate);
    public function getCandidatureByStatus($status);
    public function getCandidatureByCandidatAndStatus(Candidate $candidate, $status);
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use  App\repositories\CandidatureReposirorie;
use App\Http\Requests\CreateCandidatureRequeste;
use App\Http\Requests\PutCandidatureRequeste;
use App\Http\Requests\UpdateCandidatureRequeste;
use  App\Models\Candidature;
use  App\Models\Candidate;

class candidatureController extends Controller
{
    private $candidatureReposirorie;
    public function __construct(CandidatureReposirorie $candidatureReposirorie)
    {
        $this->candidatureReposirorie = $candidatureReposirorie;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $candidates = $this->candidatureReposirorie->getAllCandidature();
        return response()->json($candidates);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateCandidatureRequeste $request)
    {
        $candidature = $this->candidatureReposirorie->createCandidature($request->all());
        return response()->json($candidature);
    }

    /**
     * Display the specified resource.
     */
    public function show(Candidature  $candidature)
    {
        return response()->json($candidature);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCandidatureRequeste $request , Candidature $candidature)
    {
        $candidature = $this->candidatureReposirorie->updateCandidature( $candidature , $request->all());
        return response()->json($candidature);
    }
    public function  Put(PutCandidatureRequeste $request, Candidature $candidature)
    {
        $candidature = $this->candidatureReposirorie->updateCandidature( $candidature , $request->all());
        return response()->json($candidature);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Candidature $candidature)
    {
        $candidature = $this->candidatureReposirorie->deleteCandidature($candidature);
        return response()->json($candidature);
    }
    public function  getCandidatureByCandidat(Candidate $candidate)
    {
        $candidature = $this->candidatureReposirorie->getCandidatureByCandidat($candidate);
        return response()->json($candidature);
    }
}

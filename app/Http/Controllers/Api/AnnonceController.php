<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Annonce;
use App\Repositories\AnnonceRepositorie;
use App\Http\Requests\CreateAnnonceRequest;
use App\Http\Requests\UpdateAnnonceRequest;
class AnnonceController extends Controller
{
    private AnnonceRepositorie $annonceRepository;
    public function __construct(AnnonceRepositorie $annonceRepository)
    {
        $this->annonceRepository = $annonceRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $annonces = $this->annonceRepository->getAllAnnonce();
        return response()->json($annonces);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateAnnonceRequest $request)
    {
        $this->annonceRepository->createAnnonce($request->all());
        return response()->json([
            'success' => true,
            'message' => 'Annonce created successfully'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $annonce = Annonce::find($id);
        return response()->json($annonce);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Annonce  $annonce, UpdateAnnonceRequest $request)
    {
        $this->annonceRepository->updateAnnonce($annonce, $request->all());
        return response()->json([
            'success' => true,
            'message' => 'Annonce updated successfully'
        ]);
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Annonce  $annonce)
    {
        $this->annonceRepository->deleteAnnonce($annonce);
        return response()->json([
            'success' => true,
            'message' => 'Annonce deleted successfully'
        ]);
    }
}

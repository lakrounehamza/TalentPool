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
     * @OA\Get(
     *     path="/api/annonces",
     *     summary="afichire  des annonces  ",
     *     description="Returns a list of job advertisements with their details.",
     *     @OA\Response(
     *         response=200,
     *         description="A list of job ads.",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=2),
     *                 @OA\Property(property="title", type="string", example="titre2"),
     *                 @OA\Property(property="description", type="string", example="description1"),
     *                 @OA\Property(property="status", type="string", example="status"),
     *                 @OA\Property(property="recruteur_id", type="integer", example=2),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-03-24T15:11:09.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-03-24T15:11:09.000000Z")
     *             )
     *         )
     *     )
     * )
     */


    public function index()
    {
        $annonces = $this->annonceRepository->getAllAnnonce();
        return response()->json($annonces);
    }

    /**
     * @OA\Post(
     *     path="/api/annonces",
     *     summary="Créer une annonce",
     *     description="Permet à un utilisateur de créer une nouvelle annonce.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"title", "description", "status", "recruteur_id"},
     *             @OA\Property(property="title", type="string", example="Titre de l'annonce"),
     *             @OA\Property(property="description", type="string", example="Description de l'annonce"),
     *             @OA\Property(property="status", type="string", example="active"),
     *             @OA\Property(property="recruteur_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Annonce créée avec succès.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Annonce créée avec succès")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Erreur de validation.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Validation errors"),
     *             @OA\Property(property="data", type="object", additionalProperties={
     *                 @OA\Property(property="title", type="array", items=@OA\Items(type="string"))
     *             })
     *         )
     *     )
     * )
     */


    public function store(CreateAnnonceRequest $request)
    {
        $this->annonceRepository->createAnnonce($request->all());
        return response()->json([
            'success' => true,
            'message' => 'Annonce created successfully'
        ]);
    }

    

    
    public function show(string $id)
    {
        $annonce = Annonce::find($id);
        return response()->json($annonce);
    }



    public function update(Annonce  $annonce, UpdateAnnonceRequest $request)
    {
        $this->annonceRepository->updateAnnonce($annonce, $request->all());
        return response()->json([
            'success' => true,
            'message' => 'Annonce updated successfully'
        ]);
    }

    

    public function destroy(Annonce  $annonce)
    {
        $this->annonceRepository->deleteAnnonce($annonce);
        return response()->json([
            'success' => true,
            'message' => 'Annonce deleted successfully'
        ]);
    }
}

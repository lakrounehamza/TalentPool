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
     *         @OA\JsonContent(ref="#/components/schemas/CreateAnnonceRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Annonce créée avec succès.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Annonce créée avec succès")
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

    /**
     * @OA\Get(
     *     path="/api/annonces/{id}",
     *     summary="Afficher une annonce spécifique",
     *     description="Retourne une annonce en fonction de son identifiant.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Annonce trouvée.",
     *         @OA\JsonContent(ref="#/components/schemas/Annonce")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Annonce non trouvée.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Annonce non trouvée")
     *         )
     *     )
     * )
     */

    public function show(string $id)
    {
        $annonce = Annonce::find($id);
        return response()->json($annonce);
    }

    /**
     * @OA\Put(
     *     path="/api/annonces/{id}",
     *     summary="Mettre à jour une annonce",
     *     description="Permet de mettre à jour une annonce existante.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdateAnnonceRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Annonce mise à jour avec succès.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Annonce mise à jour avec succès")
     *         )
     *     )
     * )
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
     * @OA\Delete(
     *     path="/api/annonces/{id}",
     *     summary="Supprimer une annonce",
     *     description="Permet de supprimer une annonce existante.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Annonce supprimée avec succès.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Annonce supprimée avec succès")
     *         )
     *     )
     * )
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

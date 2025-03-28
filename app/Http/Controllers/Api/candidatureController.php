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
     * @OA\Get(
     *     path="/api/candidatures",
     *     summary="Liste des candidatures",
     *     description="Récupère la liste de toutes les candidatures.",
     *     @OA\Response(
     *         response=200,
     *         description="Liste des candidatures récupérée avec succès.",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="candidate_id", type="integer", example=2),
     *                 @OA\Property(property="annonce_id", type="integer", example=3),
     *                 @OA\Property(property="status", type="string", example="pending"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-03-24T15:11:09.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-03-24T15:11:09.000000Z")
     *             )
     *         )
     *     )
     * )
     * Display a listing of the resource.
     */
    public function index()
    {
        $candidates = $this->candidatureReposirorie->getAllCandidature();
        return response()->json($candidates);
    }

    /**
     * @OA\Post(
     *     path="/api/candidatures",
     *     summary="Créer une candidature",
     *     description="Permet à un candidat de postuler à une annonce.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"candidate_id", "annonce_id", "status"},
     *             @OA\Property(property="candidate_id", type="integer", example=2),
     *             @OA\Property(property="annonce_id", type="integer", example=3),
     *             @OA\Property(property="status", type="string", example="pending")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Candidature créée avec succès.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="candidate_id", type="integer", example=2),
     *             @OA\Property(property="annonce_id", type="integer", example=3),
     *             @OA\Property(property="status", type="string", example="pending"),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2025-03-24T15:11:09.000000Z"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", example="2025-03-24T15:11:09.000000Z")
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
     *                 @OA\Property(property="candidate_id", type="array", items=@OA\Items(type="string"))
     *             })
     *         )
     *     )
     * )
     * Store a newly created resource in storage.
     */
    public function store(CreateCandidatureRequeste $request)
    {
        $candidature = $this->candidatureReposirorie->createCandidature($request->all());
        return response()->json($candidature);
    }

    /**
     * @OA\Get(
     *     path="/api/candidatures/{candidature}",
     *     summary="Afficher une candidature spécifique",
     *     description="Récupère les détails d'une candidature par son identifiant.",
     *     @OA\Parameter(
     *         name="candidature",
     *         in="path",
     *         required=true,
     *         description="Identifiant de la candidature",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Détails de la candidature.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="candidate_id", type="integer", example=2),
     *             @OA\Property(property="annonce_id", type="integer", example=3),
     *             @OA\Property(property="status", type="string", example="pending"),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2025-03-24T15:11:09.000000Z"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", example="2025-03-24T15:11:09.000000Z")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Candidature non trouvée.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Candidature non trouvée")
     *         )
     *     )
     * )
     * Display the specified resource.
     */
    public function show(Candidature  $candidature)
    {
        return response()->json($candidature);
    }

    /**
     * @OA\Put(
     *     path="/api/candidatures/{candidature}",
     *     summary="Mettre à jour une candidature",
     *     description="Permet de modifier les informations d'une candidature existante.",
     *     @OA\Parameter(
     *         name="candidature",
     *         in="path",
     *         required=true,
     *         description="Identifiant de la candidature à modifier",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"status"},
     *             @OA\Property(property="status", type="string", example="accepted")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Candidature mise à jour avec succès.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="candidate_id", type="integer", example=2),
     *             @OA\Property(property="annonce_id", type="integer", example=3),
     *             @OA\Property(property="status", type="string", example="accepted"),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2025-03-24T15:11:09.000000Z"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", example="2025-03-24T15:11:09.000000Z")
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
     *                 @OA\Property(property="status", type="array", items=@OA\Items(type="string"))
     *             })
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Candidature non trouvée.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Candidature non trouvée")
     *         )
     *     )
     * )
     * Update the specified resource in storage.
     */
    public function update(UpdateCandidatureRequeste $request , Candidature $candidature)
    {
        $candidature = $this->candidatureReposirorie->updateCandidature( $candidature , $request->all());
        return response()->json($candidature);
    }
    
    /**
     * @OA\Patch(
     *     path="/api/candidatures/{candidature}/put",
     *     summary="Mettre à jour partiellement une candidature",
     *     description="Permet de modifier partiellement les informations d'une candidature existante.",
     *     @OA\Parameter(
     *         name="candidature",
     *         in="path",
     *         required=true,
     *         description="Identifiant de la candidature à modifier",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="rejected")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Candidature mise à jour avec succès.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="candidate_id", type="integer", example=2),
     *             @OA\Property(property="annonce_id", type="integer", example=3),
     *             @OA\Property(property="status", type="string", example="rejected"),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2025-03-24T15:11:09.000000Z"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", example="2025-03-24T15:11:09.000000Z")
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
     *                 @OA\Property(property="status", type="array", items=@OA\Items(type="string"))
     *             })
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Candidature non trouvée.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Candidature non trouvée")
     *         )
     *     )
     * )
     */
    public function Put(PutCandidatureRequeste $request, Candidature $candidature)
    {
        $candidature = $this->candidatureReposirorie->updateCandidature( $candidature , $request->all());
        return response()->json($candidature);
    }

    /**
     * @OA\Delete(
     *     path="/api/candidatures/{candidature}",
     *     summary="Supprimer une candidature",
     *     description="Permet de supprimer définitivement une candidature.",
     *     @OA\Parameter(
     *         name="candidature",
     *         in="path",
     *         required=true,
     *         description="Identifiant de la candidature à supprimer",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Candidature supprimée avec succès.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Candidature supprimée avec succès")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Candidature non trouvée.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Candidature non trouvée")
     *         )
     *     )
     * )
     * Remove the specified resource from storage.
     */
    public function destroy(Candidature $candidature)
    {
        $candidature = $this->candidatureReposirorie->deleteCandidature($candidature);
        return response()->json($candidature);
    }
    
    /**
     * @OA\Get(
     *     path="/api/candidatures/candidate/{id}",
     *     summary="Récupérer les candidatures d'un candidat",
     *     description="Récupère toutes les candidatures associées à un candidat spécifique.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Identifiant du candidat",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Candidatures récupérées avec succès.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Candidature by candidat"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="candidate_id", type="integer", example=2),
     *                     @OA\Property(property="annonce_id", type="integer", example=3),
     *                     @OA\Property(property="status", type="string", example="pending"),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-03-24T15:11:09.000000Z"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-03-24T15:11:09.000000Z")
     *                 )
     *             ),
     *             @OA\Property(
     *                 property="candidate",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=2),
     *                 @OA\Property(property="user_id", type="integer", example=5),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-03-24T15:11:09.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-03-24T15:11:09.000000Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Candidat non trouvé.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Candidat non trouvé")
     *         )
     *     )
     * )
     */
    public function getCandidatureByCandidat(string $id)
    {
        $candidate = Candidate::find($id);
        $candidature = $this->candidatureReposirorie->getCandidatureByCandidat($candidate);
        return response()->json(["message" => "Candidature by candidat", "data" => $candidature, "candidate" => $candidate]);
    }
    
    /**
     * @OA\Get(
     *     path="/api/candidatures/status/{status}",
     *     summary="Récupérer les candidatures par statut",
     *     description="Récupère toutes les candidatures ayant un statut spécifique.",
     *     @OA\Parameter(
     *         name="status",
     *         in="path",
     *         required=true,
     *         description="Statut des candidatures à récupérer (ex: pending, accepted, rejected)",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Candidatures récupérées avec succès.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Candidature by status"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="candidate_id", type="integer", example=2),
     *                     @OA\Property(property="annonce_id", type="integer", example=3),
     *                     @OA\Property(property="status", type="string", example="pending"),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-03-24T15:11:09.000000Z"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-03-24T15:11:09.000000Z")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function getCandidatureByStatus(string $status)
    {
        $candidature = $this->candidatureReposirorie->getCandidatureByStatus($status);
        return response()->json(["message" => "Candidature by status", "data" => $candidature]);
    }
    
    /**
     * @OA\Get(
     *     path="/api/candidatures/candidate/{id}/status/{status}",
     *     summary="Récupérer les candidatures d'un candidat par statut",
     *     description="Récupère toutes les candidatures d'un candidat spécifique ayant un statut spécifique.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Identifiant du candidat",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="status",
     *         in="path",
     *         required=true,
     *         description="Statut des candidatures à récupérer (ex: pending, accepted, rejected)",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Candidatures récupérées avec succès.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Candidature by candidat and status"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="candidate_id", type="integer", example=2),
     *                     @OA\Property(property="annonce_id", type="integer", example=3),
     *                     @OA\Property(property="status", type="string", example="pending"),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-03-24T15:11:09.000000Z"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-03-24T15:11:09.000000Z")
     *                 )
     *             ),
     *             @OA\Property(
     *                 property="candidate",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=2),
     *                 @OA\Property(property="user_id", type="integer", example=5),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-03-24T15:11:09.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-03-24T15:11:09.000000Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Candidat non trouvé.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Candidat non trouvé")
     *         )
     *     )
     * )
     */
    public function getCandidatureByCandidatAndStatus(string $id, string $status)
    {
        $candidate = Candidate::find($id);
        $candidature = $this->candidatureReposirorie->getCandidatureByCandidatAndStatus($candidate, $status);
        return response()->json(["message" => "Candidature by candidat and status", "data" => $candidature, "candidate" => $candidate]);
    }
    
    /**
     * @OA\Get(
     *     path="/api/notification/candidatures/{id}",
     *     summary="Récupérer les notifications d'une candidature",
     *     description="Récupère les informations de notification pour une candidature spécifique.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Identifiant de la candidature",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Notification récupérée avec succès.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Notification de candidature"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="candidature_id", type="integer", example=1),
     *                 @OA\Property(property="annonce_id", type="integer", example=3),
     *                 @OA\Property(property="annonce_title", type="string", example="Développeur Full Stack"),
     *                 @OA\Property(property="status", type="string", example="accepted"),
     *                 @OA\Property(property="date", type="string", format="date-time", example="2025-03-24T15:11:09.000000Z"),
     *                 @OA\Property(property="message", type="string", example="Félicitations ! Votre candidature a été acceptée.")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Candidature non trouvée.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Candidature non trouvée")
     *         )
     *     )
     * )
     */
    public function notificationCandidature(string $id)
    {
        $result = $this->candidatureReposirorie->notificationCandidature($id);
        return response()->json($result);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\RecruteurRepositorie;

class RecruteurController extends Controller
{
    private $recruteurRepository;
    public function __construct(RecruteurRepositorie $recruteurRepository)
    {
        $this->recruteurRepository = $recruteurRepository;
    }
    /**
     * @OA\Get(
     *     path="/api/recruteurs",
     *     summary="Récupérer la liste des recruteurs",
     *     description="Récupère les informations de tous les recruteurs.",
     *     @OA\Response(
     *         response=200,
     *         description="Liste des recruteurs récupérée avec succès.",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="user_id", type="integer", example=5),
     *                 @OA\Property(property="company", type="string", example="Entreprise XYZ"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-03-24T15:11:09.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-03-24T15:11:09.000000Z"),
     *                 @OA\Property(
     *                     property="user",
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=5),
     *                     @OA\Property(property="name", type="string", example="John Doe"),
     *                     @OA\Property(property="email", type="string", example="john@example.com"),
     *                     @OA\Property(property="role", type="string", example="recruteur")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function recruteur() 
    {
        $data = $this->recruteurRepository->recruteur();
        return response()->json($data);
    }
}

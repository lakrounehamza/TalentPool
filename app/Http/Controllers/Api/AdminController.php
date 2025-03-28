<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Recruteur;
use App\Models\Candidate;
use App\Models\Annonce;
use App\Models\Candidature;
use App\Repositories\AdminRepositorie;
class AdminController extends Controller
{
    private $adminRepositorie;
    public function __construct(AdminRepositorie $adminRepositorie)
    {
        $this->adminRepositorie = $adminRepositorie;
    }
   /**
    * @OA\Get(
    *     path="/api/admin/globales",
    *     summary="Récupérer les données globales du système",
    *     description="Récupère les statistiques et données globales pour l'administration.",
    *     @OA\Response(
    *         response=200,
    *         description="Données globales récupérées avec succès.",
    *         @OA\JsonContent(
    *             type="object",
    *             @OA\Property(property="total_users", type="integer", example=150),
    *             @OA\Property(property="total_candidates", type="integer", example=100),
    *             @OA\Property(property="total_recruiters", type="integer", example=45),
    *             @OA\Property(property="total_annonces", type="integer", example=75),
    *             @OA\Property(property="total_candidatures", type="integer", example=250),
    *             @OA\Property(property="recent_activities", type="array", @OA\Items(type="object"))
    *         )
    *     )
    * )
    */
   public function globales(){
        $data = $this->adminRepositorie->global();
        return response()->json($data);
       
   }
}

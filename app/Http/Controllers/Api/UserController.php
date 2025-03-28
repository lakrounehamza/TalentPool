<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/users",
     *     summary="Récupérer la liste des utilisateurs",
     *     description="Récupère les informations de tous les utilisateurs.",
     *     @OA\Response(
     *         response=200,
     *         description="Liste des utilisateurs récupérée avec succès.",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="John Doe"),
     *                 @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *                 @OA\Property(property="phone", type="string", example="0612345678"),
     *                 @OA\Property(property="role", type="string", example="candidat"),
     *                 @OA\Property(property="photo", type="string", nullable=true, example="photo.jpg"),
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
        $users = User::all();
        return response()->json($users);
    }

    /**
     * Store a newly created resource in storage.
     */


    /**
     * @OA\Get(
     *     path="/api/users/{user}",
     *     summary="Récupérer un utilisateur spécifique",
     *     description="Récupère les détails d'un utilisateur par son identifiant.",
     *     @OA\Parameter(
     *         name="user",
     *         in="path",
     *         required=true,
     *         description="Identifiant de l'utilisateur",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Détails de l'utilisateur.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *             @OA\Property(property="phone", type="string", example="0612345678"),
     *             @OA\Property(property="role", type="string", example="candidat"),
     *             @OA\Property(property="photo", type="string", nullable=true, example="photo.jpg"),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2025-03-24T15:11:09.000000Z"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", example="2025-03-24T15:11:09.000000Z")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Utilisateur non trouvé.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="No query results for model [App\\Models\\User] 1")
     *         )
     *     )
     * )
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     */


    /**
     * @OA\Delete(
     *     path="/api/users/{user}",
     *     summary="Supprimer un utilisateur",
     *     description="Permet de supprimer définitivement un utilisateur.",
     *     @OA\Parameter(
     *         name="user",
     *         in="path",
     *         required=true,
     *         description="Identifiant de l'utilisateur à supprimer",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Utilisateur supprimé avec succès.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="User deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Utilisateur non trouvé.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="No query results for model [App\\Models\\User] 1")
     *         )
     *     )
     * )
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully'
        ]);
    }
}

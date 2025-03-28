<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Password as PasswordFacade;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;

class UserAuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/auth/register",
     *     summary="Inscription d'un utilisateur",
     *     description="Permet à un utilisateur de s'inscrire sur la plateforme",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"name", "email", "password", "phone", "role"},
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123"),
     *             @OA\Property(property="phone", type="string", example="0612345678"),
     *             @OA\Property(property="role", type="string", example="candidat"),
     *             @OA\Property(property="photo", type="string", nullable=true, example="photo.jpg")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Utilisateur enregistré avec succès",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Utilisateur enregistré avec succès")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erreur de validation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Erreur de validation"),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=409,
     *         description="Email déjà utilisé",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Cet email existe déjà")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erreur serveur",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Une erreur s'est produite"),
     *             @OA\Property(property="error", type="string", example="Une erreur inattendue est survenue")
     *         )
     *     )
     * )
     */
    public function register(Request $request)
    {
        try {
            $validatedData = $request->validate([
                "name" => "required",
                "email" => "required",
                "password" => ["required"],
                "phone" => "required",
                "role" => "required",
                "photo" => "nullable",
            ]);
            
            $user = User::create([
                "name" => $validatedData["name"],
                "email" => $validatedData["email"],
                "password" => Hash::make($validatedData["password"]),
                "phone" => $validatedData["phone"],
                "role" => $validatedData["role"],
                "photo" => $validatedData["photo"] ?? null,
            ]);
            
            return response()->json([
                "success" => true,
                "message" => "Utilisateur enregistré avec succès",
            ], 201);
            
        } catch (ValidationException $e) {
            return response()->json([
                "success" => false,
                "message" => "Erreur de validation",
                "errors" => $e->errors()
            ], 422);
            
        } catch (QueryException $e) {
            $errorCode = $e->errorInfo[1] ?? null;
            
            if ($errorCode == 1062) { 
                return response()->json([
                    "success" => false,
                    "message" => "Cet email existe déjà"
                ], 409);
            }
            
            return response()->json([
                "success" => false,
                "message" => "Erreur de base de données",
                "error" => config('app.debug') ? $e->getMessage() : "Une erreur est survenue lors de l'opération"
            ], 500);
            
        } catch (Exception $e) {
            return response()->json([
                "success" => false,
                "message" => "Une erreur s'est produite",
                "error" => config('app.debug') ? $e->getMessage() : "Une erreur inattendue est survenue"
            ], 500);
        }
    }
    
    /**
     * @OA\Post(
     *     path="/api/auth/login",
     *     summary="Connexion d'un utilisateur",
     *     description="Permet à un utilisateur de se connecter et d'obtenir un token JWT",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Connexion réussie",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Connexion réussie."),
     *             @OA\Property(property="token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..."),
     *             @OA\Property(property="user", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=202,
     *         description="Identifiants invalides",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Aucun utilisateur connecté n'est associé à cet email")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erreur de validation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Erreur de validation"),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erreur serveur",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Impossible de générer le token")
     *         )
     *     )
     * )
     */
    public function login(Request $request)
    { 
        try {
            $validateData = $request->validate([
                "email" => "required",
                "password" => "required"
            ]);
            
            $user = User::where('email', $validateData['email'])->first();
            
            if (!$user) {
                return response()->json([
                    "success" => false,
                    "message" => "Aucun utilisateur connecté n'est associé à cet email"
                ], 202);
            }
            
            if (!Hash::check($validateData['password'], $user->password)) {
                return response()->json([
                    "success" => false,
                    "message" => "le mode passé n'est pas valide",
                ], 202);
            }
            
            try {
                $token = JWTAuth::fromUser($user);
            } catch (JWTException $e) {
                return response()->json([
                    "success" => false,
                    "message" => "Impossible de générer le token : " . $e->getMessage()
                ], 500);
            }
    
            // Retourner le token
            return response()->json([
                "success" => true,
                "message" => "Connexion réussie.",
                "token" => $token,
                "user" => $user
            ], 200);
            
        } catch (ValidationException $e) {
            return response()->json([
                "success" => false,
                "message" => "Erreur de validation",
                "errors" => $e->errors()
            ], 422);
        }
    }
    
    /**
     * @OA\Post(
     *     path="/api/auth/logout",
     *     summary="Déconnexion d'un utilisateur",
     *     description="Permet à un utilisateur de se déconnecter en invalidant son token JWT",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Déconnexion réussie",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Déconnexion réussie")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Non authentifié",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Token non fourni ou invalide")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erreur serveur",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Erreur lors de la déconnexion")
     *         )
     *     )
     * )
     */
    public function logout()
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
            
            return response()->json([
                'success' => true,
                'message' => 'Déconnexion réussie'
            ]);
        } catch (JWTException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la déconnexion: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * @OA\Post(
     *     path="/api/auth/refresh",
     *     summary="Rafraîchir le token JWT",
     *     description="Permet à un utilisateur de rafraîchir son token JWT",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Token rafraîchi avec succès",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Token rafraîchi avec succès"),
     *             @OA\Property(property="token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Non authentifié",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Token non fourni ou invalide")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erreur serveur",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Erreur lors du rafraîchissement du token")
     *         )
     *     )
     * )
     */
    public function refresh()
    {
        try {
            $token = JWTAuth::getToken();
            $newToken = JWTAuth::refresh($token);
            
            return response()->json([
                'success' => true,
                'message' => 'Token rafraîchi avec succès',
                'token' => $newToken
            ]);
        } catch (JWTException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du rafraîchissement du token: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * @OA\Post(
     *     path="/api/auth/password/forgot",
     *     summary="Demande de réinitialisation de mot de passe",
     *     description="Permet à un utilisateur de demander un lien de réinitialisation de mot de passe",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"email"},
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Email de réinitialisation envoyé",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Lien de réinitialisation envoyé par email")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erreur de validation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Erreur de validation"),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erreur serveur",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Erreur lors de l'envoi du lien de réinitialisation")
     *         )
     *     )
     * )
     */
    public function forgot(Request $request)
    {
        try {
            $request->validate(['email' => 'required|email']);
            
            $status = PasswordFacade::sendResetLink(
                $request->only('email')
            );
            
            if ($status === PasswordFacade::RESET_LINK_SENT) {
                return response()->json([
                    'success' => true,
                    'message' => 'Lien de réinitialisation envoyé par email'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de l\'envoi du lien de réinitialisation'
                ], 500);
            }
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $e->errors()
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'envoi du lien de réinitialisation',
                'error' => config('app.debug') ? $e->getMessage() : 'Une erreur inattendue est survenue'
            ], 500);
        }
    }
    
    /**
     * @OA\Post(
     *     path="/api/auth/password/reset",
     *     summary="Réinitialisation de mot de passe",
     *     description="Permet à un utilisateur de réinitialiser son mot de passe avec un token valide",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"token", "email", "password", "password_confirmation"},
     *             @OA\Property(property="token", type="string", example="abcdef1234567890"),
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="newpassword123"),
     *             @OA\Property(property="password_confirmation", type="string", format="password", example="newpassword123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Mot de passe réinitialisé avec succès",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Mot de passe réinitialisé avec succès")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erreur de validation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Erreur de validation"),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erreur serveur",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Erreur lors de la réinitialisation du mot de passe")
     *         )
     *     )
     * )
     */
    public function reset(Request $request)
    {
        try {
            $request->validate([
                'token' => 'required',
                'email' => 'required|email',
                'password' => 'required|confirmed|min:8',
            ]);
            
            $status = PasswordFacade::reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function ($user, $password) {
                    $user->password = Hash::make($password);
                    $user->setRememberToken(Str::random(60));
                    $user->save();
                    
                    event(new PasswordReset($user));
                }
            );
            
            if ($status === PasswordFacade::PASSWORD_RESET) {
                return response()->json([
                    'success' => true,
                    'message' => 'Mot de passe réinitialisé avec succès'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la réinitialisation du mot de passe'
                ], 500);
            }
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $e->errors()
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la réinitialisation du mot de passe',
                'error' => config('app.debug') ? $e->getMessage() : 'Une erreur inattendue est survenue'
            ], 500);
        }
    }
}
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


class UserAuthController extends Controller
{
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
    public  function login(Request $request){ 
        try{
            $validateData = $request->validate([
                "email" => "required",
                "password" => "required"
            ]);
            $user =User::where('email',$validateData['email'])->first();
            if(!$user)
                return  response()->json(["success" => false ,
                "message" => "Aucun utilisateur connecté n'est associé à cet email"
        ],202);
        if(!Hash::check($validateData['password'],$user->password))
            return response()->json([
                "seccess" => false,
                "message" => "le mode passé n'est pas valide",
            ],202);
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
        return  response()->json(["message" => "login"]);

        }catch(ValidationException  $e){
            return  response()->json(["success" => false,"message" => "Erreur de validation",
                "errors" => $e->errors()], 422);
        }
    }
}
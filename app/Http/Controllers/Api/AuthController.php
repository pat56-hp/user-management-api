<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use App\Http\Resources\UserResource;
use App\Services\ActivityService;
use App\Services\AuthService;
use Symfony\Component\HttpFoundation\JsonResponse;

class AuthController extends Controller
{
    public function __construct(private AuthService $authService, private ActivityService $activityService)
    {
        // Middleware pour protéger la route de déconnexion
        $this->middleware('auth:api')->except('login');
    }

    /**
     * Authentification d'un utilisateur.
     *
     * @param AuthRequest $request
     * @return JsonResponse
     */
    public function login(AuthRequest $request) :JsonResponse{
        //Récupération des credentials de l'utilisateur
        $credentials = $request->only('email', 'password');

        //Vérification des identifiants
        if (!$token = $this->authService->authenticate($credentials)) {
            return response()->json(['message' => 'Email ou mot de passe incorrect'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        //Log d'activité
        $this->activityService->logActivity('Connexion à l\'interface');

        //Authentification réussie, renvoi du token JWT et des informations de l'utilisateur
        return response()->json([
            'access_token' => $token,
            'access_type' => 'Bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
        ], JsonResponse::HTTP_OK);
    }

    /**
     * Récupération des informations de l'utilisateur connecté
     *
     * @return void
     */
    public function me(){
        return new UserResource(auth('api')->user());
    }

    /**
     * Déconnexion de l'utilisateur.
     *
     * @return JsonResponse
     */
    public function logout() :JsonResponse
    {
        $this->authService->logout();
        //Log d'activité
        $this->activityService->logActivity('Déconnexion');
        return response()->json(['message' => 'Déconnexion réussie'], JsonResponse::HTTP_OK);
    }
}

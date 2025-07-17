<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use App\Services\AuthService;
use Symfony\Component\HttpFoundation\JsonResponse;

class AuthController extends Controller
{
    public function __construct(private AuthService $authService)
    {
        // Middleware pour protéger la route de déconnexion
        $this->middleware('auth:api')->only('logout');
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
            return response()->json(['error' => 'Email ou mot de passe incorrect'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        //Authentification réussie, renvoi du token JWT et des informations de l'utilisateur
        return response()->json([
            'access_token' => $token,
            'access_type' => 'Bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
        ], JsonResponse::HTTP_OK);
    }

    /**
     * Déconnexion de l'utilisateur.
     *
     * @return JsonResponse
     */
    public function logout() :JsonResponse
    {
        $this->authService->logout();
        return response()->json(['message' => 'Déconnexion réussie'], JsonResponse::HTTP_OK);
    }
}

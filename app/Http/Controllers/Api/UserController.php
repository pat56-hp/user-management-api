<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Services\UserService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserController extends Controller
{
    public function __construct(private UserService $userService)
    {
        // Middleware pour protéger les routes de l'utilisateur
        $this->middleware('auth:api');
    }

    /**
     * Recuperation des utilisateurs.
     *
     * @param Request $request
     * @return void
     */
    public function index(Request $request) :JsonResponse
    {
        return response()->json([
            'data' => $this->userService->getAllUsers($request->query('search')),
            'message' => 'Liste des utilisateurs récupérée avec succès'
        ], JsonResponse::HTTP_OK);
    }

    /**
     * Sauvegarde d'un nouvel utilisateur.
     *
     * @param UserRequest $request
     * @return JsonResponse
     */
    public function store(UserRequest $request) :JsonResponse{
        $data = $request->validated();
        try {
            // Création de l'utilisateur
            $user = $this->userService->createUser($data);
            return response()->json([
                'data' => $user,
                'message' => 'Utilisateur créé avec succès'
            ], JsonResponse::HTTP_CREATED);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Erreur lors de la création de l\'utilisateur',
                'error' => $th->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update d'un utilisateur.
     *
     * @param UserRequest $request
     * @param integer $id
     * @return JsonResponse
     */
    public function update(UserRequest $request, int $id) :JsonResponse
    {
        $data = $request->validated();
        try {
            // Mise à jour de l'utilisateur
            $user = $this->userService->updateUser($id, $data);
            return response()->json([
                'data' => $user,
                'message' => 'Utilisateur mis à jour avec succès'
            ], JsonResponse::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Erreur lors de la mise à jour de l\'utilisateur',
                'error' => $th->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Suppression d'un utilisateur.
     *
     * @param integer $id
     * @return JsonResponse
     */
    public function destroy(int $id) :JsonResponse
    {
        try {
            //Si la suppression est réussie
            //on retourne une réponse JSON avec un message de succès
            //sinon on lance une exception
            if ($this->userService->deleteUser($id)) {
                return response()->json([
                    'message' => 'Utilisateur supprimé avec succès'
                ], JsonResponse::HTTP_OK);
            }
            
            throw new \Exception('Impossible de supprimer l\'utilisateur');
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Erreur lors de la suppression de l\'utilisateur',
                'error' => $th->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Changer le statut d'un utilisateur.
     *
     * @param integer $id
     * @return JsonResponse
     */
    public function changeStatus(int $id) :JsonResponse
    {
        try {
            // Logique pour changer le statut de l'utilisateur
            $user = $this->userService->changeUserStatus($id);
            return response()->json([
                'data' => $user,
                'message' => 'Statut de l\'utilisateur mis à jour avec succès'
            ], JsonResponse::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Erreur lors de la mise à jour du statut de l\'utilisateur',
                'error' => $th->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

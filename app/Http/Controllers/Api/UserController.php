<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\ActivityService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserController extends Controller
{
    public function __construct(private UserService $userService, private ActivityService $activityService)
    {
        // Middleware pour protéger les routes de l'utilisateur
        $this->middleware('auth:api');
    }

    /**
     * Recuperation des utilisateurs.
     *
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function index(Request $request) :JsonResponse
    {
        $users = $this->userService->getAllUsers($request->query('search'));
        //Log d'activité
        if (!empty($request->query('search'))) {
            $this->activityService->logActivity('Filtre des utilisateurs');
        }else{
            $this->activityService->logActivity('Affichage de la liste des utilisateurs');
        }
        return response()->json([
            'data' => UserResource::collection($users),
            'meta' => [
                'current_page' => $users ? $users->currentPage() : null,
                'last_page' => $users ? $users->lastPage() : null,
                'per_page' => $users ? $users->perPage() : null,
                'total' => $users ? $users->total() : null,
                'first_page_url' => $users ? $users->url(1) : null,
                'last_page_url' => $users ? $users->url($users->lastPage()) : null,
                'next_page_url' => $users ? $users->nextPageUrl() : null,
                'prev_page_url' => $users ? $users->previousPageUrl() : null,
            ],
            'statistics' => $this->userService->getStatistics(),
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
            //Log
            $this->activityService->logActivity('Création de l\'utilisateur ' . $user->nom);
            return response()->json([
                'data' => new UserResource($user),
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
    public function update(UserRequest $request, User $user) :JsonResponse
    {
        $data = $request->validated();
        try {
            // Mise à jour de l'utilisateur
            $user = $this->userService->updateUser($user->id, $data);
            //Log
            $this->activityService->logActivity('Modification de l\'utilisateur ' . $user?->nom);
            return response()->json([
                'data' => new UserResource($user),
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
    public function destroy(User $user) :JsonResponse
    {
        try {
            //Si la suppression est réussie
            //on retourne une réponse JSON avec un message de succès
            //sinon on lance une exception
            if ($this->userService->deleteUser($user->id)) {
                //Log
                $this->activityService->logActivity('Suppression de l\'utilisateur ' . $user->nom);
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
    public function changeStatus(User $user) :JsonResponse
    {
        try {
            // Logique pour changer le statut de l'utilisateur
            $user = $this->userService->changeUserStatus($user->id);
            //Log
            $msg = $user->actif == 1 ? 'Désactivation' : 'Activation';
            $this->activityService->logActivity("{$msg} de l\'utilisateur " . $user->nom);
            return response()->json([
                'data' => new UserResource($user),
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

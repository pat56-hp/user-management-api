<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Contracts\Pagination\Paginator;

/**
 * Opérations liées aux users
 */
class UserService
{
    public function __construct(private UserRepository $userRepository, private UploadService $uploadService){}

    /**
     * Statistiques des utilisateurs.
     *
     * @return array
     */
    public function getStatistics(): array{
        return [
            'total_users' => $this->userRepository->count(),
            'active_users' => $this->userRepository->count(['actif' => true]),
            'inactive_users' => $this->userRepository->count(['actif' => false]),
        ];
    }

    /**
     * Récupération de tous les utilisateurs.
     *
     * @param string|null $search
     * @return void
     */
    public function getAllUsers(string|null $search) :Paginator
    {
        return $this->userRepository->getAll($search);
    }

    /**
     * Création d'un nouvel utilisateur.
     *
     * @param array $data
     * @return User
     */
    public function createUser(array $data)
    {
        //Si l'utilisateur a fourni un avatar, on le télécharge
        //et on ajoute le chemin dans les données de l'utilisateur
        if (isset($data['avatar'])) {
            $data['avatar'] = $this->uploadService->uploadFile($data['avatar'], 'avatars');
        }

        return $this->userRepository->createData($data);
    }

    /**
     * Mise à jour d'un utilisateur existant.
     *
     * @param integer $id
     * @param array $data
     * @return void
     */
    public function updateUser(int $id, array $data)
    {
        //Si l'utilisateur a fourni un nouvel avatar, on le télécharge
        //et on met à jour le chemin dans les données de l'utilisateur
        if (isset($data['avatar'])) {
            $data['avatar'] = $this->uploadService->uploadFile($data['avatar'], 'avatars');
        }
        
        if (isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }else {
            unset($data['password']);
        }

        return $this->userRepository->updateData($data, $id);
    }

    /**
     * Suppression d'un utilisateur.
     *
     * @param integer $id
     * @return bool
     */
    public function deleteUser(int $id) :bool
    {
        return $this->userRepository->deleteData($id);
    }

    /**
     * Modification du statut d'un utilisateur (actif/inactif).
     *
     * @param integer $id
     * @return User
     */
    public function changeUserStatus(int $id) :User
    {
        $user = $this->userRepository->findOrFail($id);
        return $this->userRepository->updateData([
            'actif' => !$user->actif
        ], $id);
    }
}
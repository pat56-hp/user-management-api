<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Contracts\Pagination\Paginator;

class UserRepository{
    public function __construct(private User $model){}

    /**
     * Récupération de tous les utilisateurs avec option de recherche.
     *
     * @param string|null $query
     * @return Paginator
     */
    public function getAll(string|null $query) :Paginator{
        return $this->model->when(!empty($query), function($q) use ($query) {
                $q->where(function ($q) use ($query) {
                    $q->where('nom', 'like', "%{$query}%")
                    ->orWhere('email', 'like', "%{$query}%");
                });
            })->latest()->paginate()->withQueryString();
    }

    /**
     * Récupération d'un utilisateur par son ID.
     *
     * @param integer $id
     * @return User|null
     */
    public function findOrFail(int $id) :?User
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Création d'un nouvel utilisateur.
     *
     * @param array $data
     * @return User
     */
    public function createData(array $data) :User{
        return $this->model->create($data);
    }

    /**
     * Modification d'un utilisateur existant.
     *
     * @param array $data
     * @param integer $id
     * @return User
     */
    public function updateData(array $data, int $id) :User
    {
        $user = $this->model->findOrFail($id);
        $user->update($data);
        return $user;
    }

    /**
     * Suppression d'un utilisateur.
     *
     * @param integer $id
     * @return boolean
     */
    public function deleteData(int $id) :bool
    {
        $user = $this->model->findOrFail($id);
        return $user->delete();
    }
}
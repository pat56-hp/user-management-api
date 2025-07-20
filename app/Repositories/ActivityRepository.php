<?php

namespace App\Repositories;

use App\Models\Activity;

class ActivityRepository{
    public function __construct(private Activity $model){}

    /**
     * Recupere le log d'activités de l'utilisateur connecté.
     *
     * @return void
     */
    public function getAllOfAuth(){
        return $this->model->where('user_id', auth('api')->id())->latest()->paginate();
    }

    /**
     * Sauvegarde de l'activité d'un utilisateur.
     *
     * @param array $data
     * @return void
     */
    public function create(array $data){
        return $this->model->create($data);
    }
}
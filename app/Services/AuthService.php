<?php

namespace App\Services;
class AuthService
{
    /**
     * Authentifier un utilisateur avec les informations d'identification fournies.
     *
     * @param array $credentials
     * @return mixed
     */
    public function authenticate(array $credentials) :mixed
    {
        $credentials['actif'] = 1;
        return auth('api')->attempt($credentials);
    }

    /**
     * Déconnexion de l'utilisateur authentifié.
     *
     * @return void
     */
    public function logout()
    {
        auth('api')->logout();
    }
}
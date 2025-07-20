<?php

namespace App\Services;

use App\Repositories\ActivityRepository;

class ActivityService
{
    public function __construct(private ActivityRepository $activityRepository){}

    /**
     * Recupere le log d'activités de l'utilisateur connecté.
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getAllOfAuth()
    {
        $this->logActivity('Affichage de l\'historique d\'activité');
        return $this->activityRepository->getAllOfAuth();
    }

    /**
     * Log d'activité d'un utilisateur.
     *
     */
    public function logActivity($action = null): void
    {
        $data['user_id'] 	= auth('api')->id();
        $data['ip']		 	= (isset($_SERVER['REMOTE_ADDR'])) ? $_SERVER['REMOTE_ADDR'] : '' ;

        $agent = (isset($_SERVER['HTTP_USER_AGENT'])) ? $_SERVER['HTTP_USER_AGENT'] : '';

        if(preg_match('/Linux/i',$agent)) $os = 'Linux';
        elseif(preg_match('/Mac/i',$agent)) $os = 'Mac';
        elseif(preg_match('/iPhone/i',$agent)) $os = 'iPhone';
        elseif(preg_match('/iPad/i',$agent)) $os = 'iPad';
        elseif(preg_match('/Droid/i',$agent)) $os = 'Droid';
        elseif(preg_match('/Unix/i',$agent)) $os = 'Unix';
        elseif(preg_match('/Windows/i',$agent)) $os = 'Windows';
        else $os = 'Unknown';

        if(preg_match('/Firefox/i',$agent)) $br = 'Firefox';
        elseif(preg_match('/Mac/i',$agent)) $br = 'Mac';
        elseif(preg_match('/Chrome/i',$agent)) $br = 'Chrome';
        elseif(preg_match('/Opera/i',$agent)) $br = 'Opera';
        elseif(preg_match('/MSIE/i',$agent)) $br = 'IE';
        else $br = 'Unknown';
        setlocale(LC_TIME, 'fr_FR.utf8','fra');
        $data['navigator']  = $br.'/'.$os;
        $data['action']		= $action;
        $data['pays']		= (isset($_SERVER['GEOIP_COUNTRY_NAME'])) ? $_SERVER['GEOIP_COUNTRY_NAME'] : '' ;
        $data['codepays']	= (isset($_SERVER['GEOIP_COUNTRY_CODE'])) ? $_SERVER['GEOIP_COUNTRY_CODE'] : '' ;
        $data['url']		= (isset($_SERVER['SCRIPT_URI'])) ? $_SERVER['SCRIPT_URI'] : '' ;

        //Sauvegarde de l'activité
        $this->activityRepository->create($data);
    }
}
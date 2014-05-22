<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Classe C_default, controleur par défaut de l'application
 * Si aucune spécification de contrôleur n'est précisée dans l'URL du navigateur
 * c'est le contrôleur par défaut qui sera invoqué.
 */
class C_default extends CI_Controller
{
    
    /**
     * Fonctionnalité par défaut du contrôleur. 
     * Vérifie l'existence d'une connexion :
     * Soit elle existe vérifie si c'est un visiteur ou un comptable, 
     * soit elle n'existe pas et on envoie la vue de connexion
     * 
     * @param $login
     * @param $mdp
     */
    public function index($login = "", $mdp = "")
    {
        $this->load->model('authentif');
        
        if (!$this->authentif->estConnecte())
        {
            $data = array();
            $this->templates->load('t_connexion', 'v_connexion', $data);
        }
        else
        {
            $this->load->helper('url');
            
            $this->load->model('dataAccess');
            $authUser = $this->dataAccess->getInfosEmployes($login, $mdp);
            
            if ($authUser['fonction'] == "visiteur")
            {
                redirect('/c_visiteur/');
            }
            
            $authUser = $this->dataAccess->getInfosEmployes($login, $mdp);
            if ($authUser['fonction'] == "comptable")
            {
                redirect('/c_comptable/');
            }
        }
    }
    
    /**
     * Traite le retour du formulaire de connexion afin de connecter l'utilisateur si il est reconnu.
     */
    public function connecter()
    {
        $this->load->model('authentif');
        
        $login = $this->input->post('login');
        $mdp   = $this->input->post('mdp');
        
        $authUser = $this->authentif->authentifier($login, $mdp);
        
        if (empty($authUser))
        {
            $data = array(
                'erreur' => 'Login ou mot de passe incorrect'
            );
            $this->templates->load('t_connexion', 'v_connexion', $data);
        }
        else
        {
            $this->authentif->connecter($authUser['id'], $authUser['nom'], $authUser['prenom']);
            $this->index($login, $mdp);
        }
    }
    
}

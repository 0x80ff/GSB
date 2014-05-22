<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Classe A_comptable, modèle du module comptable.
 */
class A_comptable extends CI_Model
{
    
    /**
     * Constructeur de classe
     */
    function __construct()
    {
        parent::__construct();
        
        // chargement du modèle d'accès aux données qui est utile à toutes les méthodes
        $this->load->model('dataAccess');
    }
    
    /**
     * Accueil du comptable
     * Renvois l'utilisateur vers l'accueil.
     */
    public function accueil()
    {
        // obtention de l'id de l'utilisateur mémorisé en session
        $idVisiteur = $this->session->userdata('idUser');
        // envoie de la vue accueil du visiteur
        $this->templates->load('t_comptable', 'v_comAccueil');
    }
    
    /**
     * Fonction de visualisation des fiches signées par les visiteurs
     */
    public function voirFiches()
    {
        $data['lesFichesSign'] = $this->dataAccess->getLignesFortaitSign();
        $this->templates->load('t_comptable', 'v_comVoirListeFichesSign', $data);
    }
    
    public function voirValides()
    {
        $data['lesFichesSign'] = $this->dataAccess->getLignesFortaitVal();
        $this->templates->load('t_comptable', 'v_comVoirListeFichesVal', $data);
    }
    
    /**
     * Fonction de visualisation des fiches signées par les visiteurs
     * @param $mois 		Contient le mois et l'année de la fiche concernée
     * @param $idVisiteur 	id du visiteur
     */
    public function valFiche($mois, $idVisiteur)
    {
        
        $data['p_mois']              = $mois;
        $data['p_idVisiteur']        = $idVisiteur;
        $data['numAnnee']            = substr($mois, 0, 4);
        $data['numMois']             = substr($mois, 4, 2);
        $data['lesFraisHorsForfait'] = $this->dataAccess->getLesLignesHorsForfait($idVisiteur, $mois);
        $data['lesFraisForfait']     = $this->dataAccess->getLesLignesForfait($idVisiteur, $mois);
        
        $this->templates->load('t_comptable', 'v_comVoirListeFrais', $data);
    }
    
    /**
     * Fonction de mise en paiement d'une fiche
     * @param $mois 		Contient le mois et l'année de la fiche concernée
     * @param $idVisiteur 	id du visiteur
     */
    public function misePFiche($mois, $idVisiteur)
    {
        $this->dataAccess->majEtatFicheFrais($idVisiteur, $mois, 'MP');
        $data['lesFichesSign'] = $this->dataAccess->getLignesFortaitVal();
        //$this->templates->load('t_comptable', 'v_comVoirListeFichesVal',$data);
    }
    
    /**
     * Fonction de mise en remboursement d'une fiche
     * @param $mois 		Contient le mois et l'année de la fiche concernée
     * @param $idVisiteur 	id du visiteur
     */
    public function miseRBFiche($mois, $idVisiteur)
    {
        $this->dataAccess->majEtatFicheFrais($idVisiteur, $mois, 'RB');
        $data['lesFichesSign'] = $this->dataAccess->getLignesFortaitVal();
    }
    
    /**
     * Fonction de mise en remboursement d'une fiche
     * @param $idVisiteur 	Id du visiteur
     * @param $mois 		Contient l'année et le mois de la fiche concernée
     * @param $action 		Statut de la fiche à modifier
     * @param $commentaire  Commentaire spécifié par le comptable
     */
    public function validationFiches($idVisiteur, $mois, $action, $commentaire)
    {
        
        $data['p_mois']              = $mois;
        $data['p_idVisiteur']        = $idVisiteur;
        $data['numAnnee']            = substr($mois, 0, 4);
        $data['numMois']             = substr($mois, 4, 2);
        $data['lesFraisHorsForfait'] = $this->dataAccess->getLesLignesHorsForfait($idVisiteur, $mois);
        $data['lesFraisForfait']     = $this->dataAccess->getLesLignesForfait($idVisiteur, $mois);
        
        $this->dataAccess->majEtatFicheFrais($idVisiteur, $mois, $action, $commentaire);
        
        $data['lesFichesSign'] = $this->dataAccess->getLignesFortaitSign();
        $this->templates->load('t_comptable', 'v_comVoirListeFichesSign', $data);
    }
    
    
}
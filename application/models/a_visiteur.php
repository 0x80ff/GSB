<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class A_visiteur extends CI_Model
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
     * Renvois le visiteur vers la page d'accueil
     */
    public function accueil()
    {
        // Obtention de la liste des 6 derniers mois (y compris celui ci)
        $lesMois    = $this->functionsLib->getSixDerniersMois();
        $idVisiteur = $this->session->userdata('idUser');
        
        // Contrôle de l'existence des 6 dernières fiches et création si nécessaire
        foreach ($lesMois as $unMois)
        {
            if (!$this->dataAccess->ExisteFiche($idVisiteur, $unMois))
                $this->dataAccess->creeFiche($idVisiteur, $unMois);
        }
        // envoie de la vue accueil du visiteur
        $this->templates->load('t_visiteur', 'v_visAccueil');
    }
    
    /**
     * Liste les fiches existantes du visiteur connecté et 
     * donne accès aux fonctionnalités associées
     *
     * @param $idVisiteur : l'id du visiteur 
     * @param $message : message facultatif destiné à notifier l'utilisateur du résultat d'une action précédemment exécutée
     */
    public function mesFiches($idVisiteur, $message = null)
    {
        $idVisiteur = $this->session->userdata('idUser');
        
        $data['notify']    = $message;
        $data['mesFiches'] = $this->dataAccess->getFiches($idVisiteur);
        
        $this->templates->load('t_visiteur', 'v_visMesFiches', $data);
    }
    
    /**
     * Présente le détail de la fiche sélectionnée 
     * 
     * @param $idVisiteur : l'id du visiteur 
     * @param $mois : le mois de la fiche à modifier 
     */
    public function voirFiche($idVisiteur, $mois)
    {
        
        $data['numAnnee']            = substr($mois, 0, 4);
        $data['numMois']             = substr($mois, 4, 2);
        $data['lesFraisHorsForfait'] = $this->dataAccess->getLesLignesHorsForfait($idVisiteur, $mois);
        $data['lesFraisForfait']     = $this->dataAccess->getLesLignesForfait($idVisiteur, $mois);
        
        $this->templates->load('t_visiteur', 'v_visVoirListeFrais', $data);
    }
    
    /**
     * Présente le détail de la fiche sélectionnée et donne 
     * accés à la modification du contenu de cette fiche.
     * 
     * @param $idVisiteur : l'id du visiteur 
     * @param $mois : le mois de la fiche à modifier 
     * @param $message : message facultatif destiné à notifier l'utilisateur du résultat d'une action précédemment exécutée
     */
    public function modFiche($idVisiteur, $mois, $message = null)
    {
        
        $data['notify']              = $message;
        $data['numAnnee']            = substr($mois, 0, 4);
        $data['numMois']             = substr($mois, 4, 2);
        $data['lesFraisHorsForfait'] = $this->dataAccess->getLesLignesHorsForfait($idVisiteur, $mois);
        $data['lesFraisForfait']     = $this->dataAccess->getLesLignesForfait($idVisiteur, $mois);
        
        $this->templates->load('t_visiteur', 'v_visModListeFrais', $data);
    }
    
    /**
     * Signe une fiche de frais en changeant son état
     * 
     * @param $idVisiteur : l'id du visiteur 
     * @param $mois : le mois de la fiche à signer
     */
    public function signeFiche($idVisiteur, $mois)
    {
        $this->dataAccess->signeFiche($idVisiteur, $mois);
    }
    
    /**
     * Modifie les quantités associées aux frais forfaitisés dans une fiche donnée
     * 
     * @param $idVisiteur : l'id du visiteur 
     * @param $mois : le mois de la fiche concernée
     * @param $lesFrais : les quantités liées à chaque type de frais, sous la forme d'un tableau
     */
    public function majForfait($idVisiteur, $mois, $lesFrais)
    {
        
        $this->dataAccess->majLignesForfait($idVisiteur, $mois, $lesFrais);
        $this->dataAccess->recalculeMontantFiche($idVisiteur, $mois);
    }
    
    /**
     * Ajoute une ligne de frais hors forfait dans une fiche donnée
     * 
     * @param $idVisiteur : l'id du visiteur 
     * @param $mois : le mois de la fiche concernée
     * @param $lesFrais : les quantités liées à chaque type de frais, sous la forme d'un tableau
     */
    public function ajouteFrais($idVisiteur, $mois, $uneLigne)
    {
        $dateFrais = $uneLigne['dateFrais'];
        $libelle   = $uneLigne['libelle'];
        $montant   = $uneLigne['montant'];
        
        $this->dataAccess->creeLigneHorsForfait($idVisiteur, $mois, $libelle, $dateFrais, $montant);
    }
    
    /**
     * Supprime une ligne de frais hors forfait dans une fiche donnée
     * 
     * @param $idVisiteur : l'id du visiteur 
     * @param $mois : le mois de la fiche concernée
     * @param $idLigneFrais : l'id de la ligne à supprimer
     */
    public function supprLigneFrais($idVisiteur, $mois, $idLigneFrais)
    {
        
        $this->dataAccess->supprimerLigneHorsForfait($idLigneFrais);
    }
}
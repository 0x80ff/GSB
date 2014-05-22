<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * \brief 	Classe C_comptable: Contrôleur du module \e Comptable de l'application
 */
class C_comptable extends CI_Controller
{
    
    /**
     * Aiguillage des demandes faites au contrôleur
     * La fonction _remap est une fonctionnalité offerte par CI destinée à remplacer 
     * le comportement habituel de la fonction index. Grâce à _remap, on dispose
     * d'une fonction unique capable d'accepter un nombre variable de paramètres.
     *
     * @param $action : l'action demandée par le visiteur
     * @param $params : les éventuels paramètres transmis pour la réalisation de cette action
     */
    public function _remap($action, $params = array())
    {
        $this->load->model('authentif');
        
        // Si l'utilisateur n'est pas connecté:
        if (!$this->authentif->estConnecte())
        {
            // On le renvois vers la vue de connexion:
            $data = array();
            $this->templates->load('t_connexion', 'v_connexion', $data);
        }
        else
        {
            // Aiguillage selon l'action demandée:
            if ($action == 'index')
            {
                $this->load->model('a_comptable');
                $this->a_comptable->accueil();
                
            }
            elseif ($action == 'gesFiches')
            {
                $this->load->model('a_comptable');
                $this->a_comptable->voirFiches();
                
            }
            elseif ($action == 'gesPaiement')
            {
                $this->load->model('a_comptable');
                $this->a_comptable->voirValides();
                
            }
            elseif ($action == 'valFiche')
            {
                $idVisiteur = $_POST['idVisiteur'];
                $mois       = $_POST['mois'];
                
                $this->load->model('a_comptable');
                
                // on mémorise le mois de la fiche en cours de modification
                $this->session->set_userdata('mois', $mois);
                
                $this->a_comptable->valFiche($mois, $idVisiteur);
            }
            elseif ($action == 'validationFiche')
            {
                $idVisiteur  = $_POST['idVisiteur'];
                $mois        = $_POST['mois'];
                $action      = $_POST['val'];
                $commentaire = $_POST['commentaire'];
                
                $this->load->model('a_comptable');
                $this->a_comptable->validationFiches($idVisiteur, $mois, $action, $commentaire);
            }
            elseif ($action == 'misePaiementFiche')
            {
                
                $slct = (isset($_POST['slct'])) ? $_POST['slct'] : 0;
                if ($slct != 0)
                {
                    $tabIdVisiteur = $_POST['idVisiteur'];
                    $tabMois       = $_POST['mois'];
                    $tabId         = $_POST['id'];
                    $long          = count($tabId);
                    $longslct      = count($slct);
                }
                if (isset($slct) && isset($tabId) && $slct != 0)
                {
                    
                    for ($i = 0; $i < $long; $i++)
                    {
                        for ($j = 0; $j < $longslct; $j++)
                        {
                            
                            if ($tabId[$i] == $slct[$j])
                            {
                                
                                $idVisiteur = $tabIdVisiteur[$i];
                                $mois       = $tabMois[$i];
                                
                                $this->load->model('a_comptable');
                                $this->a_comptable->misePFiche($mois, $idVisiteur);
                            }
                        }
                    }
                }
                $this->load->model('a_comptable');
                $this->a_comptable->voirValides();
            }
            elseif ($action == 'rembourseFiche')
            {         
                $slct = (isset($_POST['slct'])) ? $_POST['slct'] : 0;
                if ($slct != 0)
                {
                    $tabIdVisiteur = $_POST['idVisiteur'];
                    $tabMois       = $_POST['mois'];
                    $tabId         = $_POST['id'];
                    $long          = count($tabId);
                    $longslct      = count($slct);
                }
                if (isset($slct) && isset($tabId) && $slct != 0)
                {
                    
                    for ($i = 0; $i < $long; $i++)
                    {
                        for ($j = 0; $j < $longslct; $j++)
                        {
                            
                            if ($tabId[$i] == $slct[$j])
                            {
                                
                                $idVisiteur = $tabIdVisiteur[$i];
                                $mois       = $tabMois[$i];
                                
                                $this->load->model('a_comptable');
                                $this->a_comptable->miseRBFiche($mois, $idVisiteur);
                            }
                        }
                    }
                }
                $this->load->model('a_comptable');
                $this->a_comptable->voirValides();
            }
            elseif ($action == 'deconnecter')
            {
                $this->load->model('authentif');
                $this->authentif->deconnecter();
            }
            else // dans tous les autres cas, on envoie la vue par défaut pour l'erreur 404
            {
                show_404();
            }
        }
    }
}

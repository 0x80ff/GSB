<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Classe C_visiteur: Contrôleur du module Visiteur de l'application.
*/
class C_visiteur extends CI_Controller {

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
		
		// Si il n'est pas connecté:
		if (!$this->authentif->estConnecte()) 
		{
			$data = array();
			$this->templates->load('t_connexion', 'v_connexion', $data); // Renvois vers la vue de connexion.
		}
		else
		{
			// Aiguillage selon l'action demandée 
			// CI a traité l'URL au préalable de sorte à toujours renvoyer l'action "index"
			// même lorsqu'aucune action n'est exprimée
			if ($action == 'index')
			{
				$this->load->model('a_visiteur');

				// on n'est pas en mode "modification d'une fiche"
				$this->session->unset_userdata('mois');

				$this->a_visiteur->accueil();
			}
			elseif ($action == 'mesFiches')
			{
				$this->load->model('a_visiteur');

				// on n'est pas en mode "modification d'une fiche"
				$this->session->unset_userdata('mois');

				$idVisiteur = $this->session->userdata('idUser');
				$this->a_visiteur->mesFiches($idVisiteur);
			}
			elseif ($action == 'deconnecter')
			{
				$this->load->model('authentif');
				$this->authentif->deconnecter();
			}
			elseif ($action == 'voirFiche'){	
				$this->load->model('a_visiteur');

				$mois = $params[0];

				$this->session->set_userdata('mois', $mois);
				$idVisiteur = $this->session->userdata('idUser');

				$this->a_visiteur->voirFiche($idVisiteur, $mois);
			}
			elseif ($action == 'modFiche')
			{
				$this->load->model('a_visiteur');

				$mois = $params[0];

				$this->session->set_userdata('mois', $mois);

				$idVisiteur = $this->session->userdata('idUser');

				$this->a_visiteur->modFiche($idVisiteur, $mois);
			}
			elseif ($action == 'signeFiche')
			{
				$this->load->model('a_visiteur');

				$mois       = $params[0];
				$idVisiteur = $this->session->userdata('idUser');
				
				$this->a_visiteur->signeFiche($idVisiteur, $mois);

				// ... et on revient à mesFiches
				$this->a_visiteur->mesFiches($idVisiteur, "La fiche $mois a été signée. <br/>Pensez à envoyer vos justificatifs afin qu'elle soit traitée par le service comptable rapidement.");
			}
			elseif ($action == 'majForfait')
			{		
				$this->load->model('a_visiteur');

				$idVisiteur = $this->session->userdata('idUser');
				$mois 		= $this->session->userdata('mois');
				$lesFrais   = $this->input->post('lesFrais');

				$this->a_visiteur->majForfait($idVisiteur, $mois, $lesFrais);

				$this->a_visiteur->modFiche($idVisiteur, $mois, 'Modification(s) des éléments forfaitisés enregistrée(s) ...');
			}
			elseif ($action == 'ajouteFrais')
			{	
				$this->load->model('a_visiteur');

				$idVisiteur = $this->session->userdata('idUser');
				$mois 	    = $this->session->userdata('mois');

				// obtention des données postées
				$uneLigne = array( 
					'dateFrais' => $this->input->post('dateFrais'),
					'libelle'   => $this->input->post('libelle'),
					'montant'   => $this->input->post('montant')
				);

				$this->a_visiteur->ajouteFrais($idVisiteur, $mois, $uneLigne);

				$this->a_visiteur->modFiche($idVisiteur, $mois, 'Ligne "Hors forfait" ajoutée ...');				
			}
			elseif ($action == 'supprFrais') 
			{
				$this->load->model('a_visiteur');

				$idVisiteur   = $this->session->userdata('idUser');
				$mois 		  = $this->session->userdata('mois');
				$idLigneFrais = $params[0];

				$this->a_visiteur->supprLigneFrais($idVisiteur, $mois, $idLigneFrais);

				$this->a_visiteur->modFiche($idVisiteur, $mois, 'Ligne "Hors forfait" supprimée ...');				
			}
			else // Dans tous les autres cas, on envoie la vue par défaut pour l'erreur 404
			{
				show_404();
			}
		}
	}
}

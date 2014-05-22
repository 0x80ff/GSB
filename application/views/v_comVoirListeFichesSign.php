<?php
	$this->load->helper('url');
	$path = base_url();
?>
<div id="contenu">
	<h2>Liste des fiches de frais signées ou refusées</h2>
	 	
	<?php if(!empty($notify)) echo '<p id="notify" >'.$notify.'</p>';?>
	 
	<table class="listeLegere">
		<thead>
			<tr>
				<th >Nom</th>
				<th >Mois</th>
				<th >Montant</th>  
				<th >Date modif</th>  
				<th >Etat</th>  
				<th >Commentaire</th>              
			</tr>
		</thead>
		<tbody>
          
		<?php    
			foreach( $lesFichesSign as $uneFiche) 
			{
				$modLink   = '';
				$signeLink = '';
				echo
				'<tr>
					<td class="perso" align="center">'.$uneFiche['prenom'].' '.$uneFiche['nom'].'</td>
					<td class="formu" align="center"><form method="POST" action="'.$path.'c_comptable/valFiche"><input type="hidden" name="mois" value="'.$uneFiche['mois'].'"><input type="hidden" name="idVisiteur" value="'.$uneFiche['idVisiteur'].'"><input type="submit" value="'.$uneFiche['mois'].'"></form></td>

					<!-- <td class="date">'.anchor('c_comptable/valFiche/'.$uneFiche['mois'],$uneFiche['mois'],'title="Consulter la fiche"').'</td> -->
					<td class="montant" align="center">'.$uneFiche['montantValide'].'</td>
					<td class="date">'.$uneFiche['dateModif'].'</td>
					<td class="etat" align="center">'.$uneFiche['idEtat'].'</td>
					<td class="libelle">'.$uneFiche['Commentaire'].'</td>
				</tr>';
			}
		?>	  
		</tbody>
    </table>

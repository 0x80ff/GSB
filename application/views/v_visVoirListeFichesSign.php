<?php
	$this->load->helper('url');
?>
<div id="contenu">
	<h2>Liste de mes fiches de frais</h2>
	 	
	<?php if(!empty($notify)) echo '<p id="notify" >'.$notify.'</p>';?>
	 
	<table class="listeLegere">
		<thead>
			<tr>
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
					<td class="date">'.anchor('c_comptable/voirFiche/'.$uneFiche['mois'], $uneFiche['mois'],  'title="Consulter la fiche"').'</td>
					<td class="montant">'.$uneFiche['montantValide'].'</td>
					<td class="date">'.$uneFiche['dateModif'].'</td>
					<td class="libelle">'.$uneFiche['idEtat'].'</td>
					<td class="libelle">'.$uneFiche['Commentaire'].'</td>
				</tr>';
			}
		?>	  
		</tbody>
    </table>

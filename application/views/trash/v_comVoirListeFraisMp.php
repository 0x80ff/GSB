<?php
	$this->load->helper('url');
		$path = base_url();
?>

<div id="contenu">
	<h2>Renseigner ma fiche de frais du mois <?php echo $numMois."-".$numAnnee; ?></h2>
					
	<div class="corpsForm">
	  
		<fieldset>
			<legend>Eléments forfaitisés</legend>
			<table>
			<thead>
				<tr>
					<th></th>
					<th>Quantité</th>
					<th>Montant</th>
					<th>Total</th>
				</tr>
			</thead>
			<tbody>
			<?php
				$total = 0;
				foreach ($lesFraisForfait as $unFrais)
				{
					$idFrais  = $unFrais['idfrais'];
					$libelle  = $unFrais['libelle'];
					$quantite = $unFrais['quantite'];
					$montant  = $unFrais['montant'];
					
					$total +=  ( $montant * $quantite );
					
					echo 
					'
					<tr>
						<td><label for="'.$idFrais.'">'.$libelle.'</label></td>
						<td><label for="'.$idFrais.'">'.$quantite.'</td>
						<td><label for=montant">'.number_format($montant,2,'.','').'€</td>
						<td><label for=total">'.number_format($montant*$quantite,2,'.','').'€</td>
					</tr>
					';
					}
			echo '<tr><td colspan = "3"> Total frais forfaitisés</td><td> '.number_format($total,2,'.','').'€</td></tr>';
				
			?>	
			</tbody>
			</table>
		</fieldset>
		<p></p>
	</div>

	
	<table class="listeLegere">
		<caption>Descriptif des éléments hors forfait</caption>
		<tr>
			<th >Date</th>
			<th >Libellé</th>  
			<th >Montant</th>  
			<!--<th >&nbsp;</th>         -->     
		</tr>
          
		<?php    
			foreach( $lesFraisHorsForfait as $unFraisHorsForfait) 
			{
				$libelle = $unFraisHorsForfait['libelle'];
				$date 	 = $unFraisHorsForfait['date'];
				$montant = $unFraisHorsForfait['montant'];
				$id      = $unFraisHorsForfait['id'];
				echo 
				'<tr>
					<td class="date">'.$date.'</td>
					<td class="libelle">'.$libelle.'</td>
					<td class="montant">'.$montant.'</td>
					<!-- <td class="action"> </td> -->
					</tr>';
			}
		?>	  
                                          
    </table>
	
<div id="ActionsComptable">
    	<?php 
		$p_mois 	  = $_POST['mois'];
		$p_idVisiteur = $_POST['idVisiteur'];
		
    	echo
    		'<form method="POST" action="'.$path.'c_comptable/rembourseFiche">
			<input type="hidden" name="mois" value="'.$p_mois.'">
			<input type="hidden" name="idVisiteur" value="'.$p_idVisiteur.'">
			<input type="submit" value="Remboursée"></form>' ?>
</div>

    

</div>

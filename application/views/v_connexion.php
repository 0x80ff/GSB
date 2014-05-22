<?php
	$this->load->helper('url');
	$path = base_url();
?>

<div id="contenu">
	<h2>Identification utilisateur</h2>

	<?php if (isset($erreur))	echo '<div class ="erreur"><ul><li>'.$erreur.'</li></ul></div>'; ?>

	<div style="display:none" id="alert-error" class="alert">
		<strong id="error-title">Titre</strong>
		<p id="error-content">Le message d'erreur"</p>
	</div>

	<form id="frmSaisie" method="post" action="<?php echo $path.'c_default/connecter';?>">
		<p>
			<label for="login">Login*</label>
			<input id="login" type="text" name="login"   size="30" maxlength="45" onblur="verifLogin()"/>
		</p>
		<p>
			<label for="mdp">Mot de passe*</label>
			<input id="mdp"  type="password"  name="mdp" size="30" maxlength="45" onblur="verifPass()"/>
		</p>
		<p>
			<input type="submit" id="val1" value="Valider" name="valider"/>
			<input type="reset"  id="val1" value="Annuler" name="annuler"/> 
		</p>
	</form>

</div>
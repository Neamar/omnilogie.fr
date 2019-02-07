<?php
/**
* Vue : membres/Connexion
* Layout : Formulaire d'inscription
* Lien devenir membre
*
*/
?>

<h3><span>Connexion au site</span></h3>

<form method="post" action="" id="connexion">
	<p><label for="pseudo">Pseudo :</label>
	<input type="text" name="pseudo" id="pseudo" /></p>

	<p><label for="password">Mot de passe :</label>
	<input type="password" name="password" id="password" /></p>

	<p><input type="submit" value="Connexion" /></p>
</form>

<p><a href="/membres/Inscription">Pas encore inscrit ? Cliquez ici pour créer un compte et commencer à rédiger des articles !</a></p>
<p><a href="/membres/Oubli">Mot de passe oublié ?</a></p>

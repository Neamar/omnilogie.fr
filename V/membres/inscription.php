<?php
/**
* Vue : membres/Inscription
* Layout : Formulaire d'inscription
* Lien devenir membre
*
*/
?>

<h3><span>Inscription au site</span></h3>

<p><strong>Omnilogie</strong> fonctionne grâce aux articles des membres.<br />
En vous inscrivant vous avez la possibilité d'écrire des articles et de partager votre culture avec le monde &ndash; comme défini dans la <a href="/Ligne">ligne éditoriale</a> du site.</p>

<p>L'inscription n'engage à rien ; vous pouvez n'écrire qu'un article, des dizaines... nous les accueillerons avec le même plaisir !</p>

<p>L'inscription est simple et rapide, alors n'hésitez pas !</p>

<?php if(!isset($C['Message'])) { ?>

<form method="post" action="" id="inscriptionMembre">
	<p><label for="pseudo">Pseudo :</label>
	<input type="text" name="pseudo" id="pseudo" maxlength="17" /></p>

	<p><label for="password">Mot de passe :</label>
	<input type="password" name="password" id="password" /></p>

	<p><label for="password2">Mot de passe (confirmez) :</label>
	<input type="password" name="password2" id="password2" /></p>

	<p><label for="mail">Mail :</label>
	<input type="email" name="mail" id="mail" /></p>

	<p><input type="submit" value="C'est parti !" /></p>
</form>

<?php } ?>

<p><a href="/membres/Connexion">Déjà inscrit ?</a></p>

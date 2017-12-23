<?php
/**
* Vue : membres/Oubli
* Layout : Formulaire de récupération du mot de passe.
* Lien devenir membre
*
*/
?>

<h3><span>Inscription au site</span></h3>

<p>Chez <strong>Omnilogie</strong>, nous croyons que votre mot de passe est une valeur sûre que vous ne souhaitez pas divulguer.<br />
Nous savons aussi que malgré tous les conseils de prudence que nous pourrions vous prodiguer, le même mot de passe vous sert probablement à plusieurs endroits différents. Aussi, nous ne stockons jamais votre mot de passe en clair : même si notre vie en dépendait, nous ne pourrions vous voler votre identité. D'un autre côté, il nous est impossible de vous renvoyer par mail votre mot de passe que nous ne connaissons pas... le mieux que nous puissions faire, c'est vous en générer un nouveau, aléatoire, et vous laisser le changer.<br />
Pour cela, entrez le mail de contact, et nous vous enverrons vos nouveaux identifiants.</p>

<form method="post" action="">
	<p><label for="mail">Mail fourni à l'inscription :</label>
	<input type="email" name="mail" id="mail" /></p>

	<p><input type="submit" value="Changer mon mot de passe !" /></p>
</form>

<p><a href="/membres/Connexion">Retour à la connexion</a></p>
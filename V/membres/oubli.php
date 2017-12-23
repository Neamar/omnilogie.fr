<?php
/**
* Vue : membres/Oubli
* Layout : Formulaire de r�cup�ration du mot de passe.
* Lien devenir membre
*
*/
?>

<h3><span>Inscription au site</span></h3>

<p>Chez <strong>Omnilogie</strong>, nous croyons que votre mot de passe est une valeur s�re que vous ne souhaitez pas divulguer.<br />
Nous savons aussi que malgr� tous les conseils de prudence que nous pourrions vous prodiguer, le m�me mot de passe vous sert probablement � plusieurs endroits diff�rents. Aussi, nous ne stockons jamais votre mot de passe en clair : m�me si notre vie en d�pendait, nous ne pourrions vous voler votre identit�. D'un autre c�t�, il nous est impossible de vous renvoyer par mail votre mot de passe que nous ne connaissons pas... le mieux que nous puissions faire, c'est vous en g�n�rer un nouveau, al�atoire, et vous laisser le changer.<br />
Pour cela, entrez le mail de contact, et nous vous enverrons vos nouveaux identifiants.</p>

<form method="post" action="">
	<p><label for="mail">Mail fourni � l'inscription :</label>
	<input type="email" name="mail" id="mail" /></p>

	<p><input type="submit" value="Changer mon mot de passe !" /></p>
</form>

<p><a href="/membres/Connexion">Retour � la connexion</a></p>
<?php
/**
* Contr�leur : membres/apercu.php
* But : afficher un aper�u d'un article en cours de r�daction.
* On ne touche pas � l'encodage des caract�res pour laisser le navigateur faire le boulot tout seul !
*/

//////////////////////////////////////////////////////
//Fonctionnalit�s du contr�leur :

header("Content-Type:text/plain; charset=utf-8");

//Pr�paration du texte : (cas des guillemets typographiques directs)
$Texte = str_replace
(
	array('«','»'),
	array('&#171;','&#187;'),
	$_POST['Texte']
);

Typo::setTexte(stripslashes($Texte));

//Parsage. (cas des guillemets typographiques standards)
$Texte = str_replace
(
	array('û','�','�','«','»'),
	array('&ucirc;','&#171;','&#187;','&#171;','&#187;'),
	ParseMath(Typo::Parse())
);

echo '<div class="omnilogisme">' . $Texte . '</div>';

//Arr�ter le script ici.
exit();
?>
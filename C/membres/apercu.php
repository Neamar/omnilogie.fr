<?php
/**
* Contrôleur : membres/apercu.php
* But : afficher un aperçu d'un article en cours de rédaction.
* On ne touche pas à l'encodage des caractères pour laisser le navigateur faire le boulot tout seul !
*/

//////////////////////////////////////////////////////
//Fonctionnalités du contrôleur :

header("Content-Type:text/plain; charset=utf-8");

//Préparation du texte : (cas des guillemets typographiques directs)
$Texte = str_replace
(
	array('Â«','Â»'),
	array('&#171;','&#187;'),
	$_POST['Texte']
);

Typo::setTexte(stripslashes($Texte));

//Parsage. (cas des guillemets typographiques standards)
$Texte = str_replace
(
	array('Ã»','«','»','Â«','Â»'),
	array('&ucirc;','&#171;','&#187;','&#171;','&#187;'),
	ParseMath(Typo::Parse())
);

echo '<div class="omnilogisme">' . $Texte . '</div>';

//Arrêter le script ici.
exit();
?>
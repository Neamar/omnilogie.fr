<?php
/**
* Contrôleur : admin/admin.php
* But : enregistrer les modifications de statut apportées à un article.
* Charge un tableau $Articles pour le modèle.
*/

//////////////////////////////////////////////////////
//Fonctionnalités du contrôleur :

$DefaultWhere = 'Omnilogismes.Statut="INDETERMINE" OR Omnilogismes.Statut="EST_CORRIGE"';

//Récupérer le titre de la page


$Prefixe = 'statut';

if(isset($_GET['Titre']))
{
	$TitreOmni = Encoding::decodeFromGet('Titre');
	$Where = 'Omnilogismes.Titre="' . $TitreOmni . '"';
}
else
	$Where = $DefaultWhere;



$Articles = Admin::getControleur($Where,$Prefixe, array('Admin','adminControleurCallback'));
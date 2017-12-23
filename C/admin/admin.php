<?php
/**
* Contr�leur : admin/admin.php
* But : enregistrer les modifications de statut apport�es � un article.
* Charge un tableau $Articles pour le mod�le.
*/

//////////////////////////////////////////////////////
//Fonctionnalit�s du contr�leur :

$DefaultWhere = 'Omnilogismes.Statut="INDETERMINE" OR Omnilogismes.Statut="EST_CORRIGE"';

//R�cup�rer le titre de la page


$Prefixe = 'statut';

if(isset($_GET['Titre']))
{
	$TitreOmni = Encoding::decodeFromGet('Titre');
	$Where = 'Omnilogismes.Titre="' . $TitreOmni . '"';
}
else
	$Where = $DefaultWhere;



$Articles = Admin::getControleur($Where,$Prefixe, array('Admin','adminControleurCallback'));
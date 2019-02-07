<?php
/**
* Modèle : Random
* But : Afficher 20 articles séléctionnés au hasard
* Données à charger : 20 articles, sous forme de teaser.
*/
//Articles
$C['PageTitle'] = 'Articles au hasard';

$C['CanonicalURL'] = '/Random';


//Récupérer tous les articles parus.
//Utiliser le système de pagination
$Param = Omni::buildParam(OMNI_SMALL_PARAM);

$Param->Where = '
!ISNULL(Sortie)
AND Statut="ACCEPTE"';

$Param->Order = 'RAND()';
$Param->Limit = 20;

//Récupérer les articles
$Articles = Omni::get($Param);
foreach($Articles as &$Article)
{
	$C['Articles'][$Article->ID]['Teaser'] = $Article->outputTeaser();
}
unset($Articles);
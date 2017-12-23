<?php
/**
* Mod�le : Random
* But : Afficher 20 articles s�l�ctionn�s au hasard
* Donn�es � charger : 20 articles, sous forme de teaser.
*/
//Articles
$C['PageTitle'] = 'Articles au hasard';

$C['CanonicalURL'] = '/Random';


//R�cup�rer tous les articles parus.
//Utiliser le syst�me de pagination
$Param = Omni::buildParam(OMNI_SMALL_PARAM);

$Param->Where = '
!ISNULL(Sortie)
AND Statut="ACCEPTE"';

$Param->Order = 'RAND()';
$Param->Limit = 20;

//R�cup�rer les articles
$Articles = Omni::get($Param);
foreach($Articles as &$Article)
{
	$C['Articles'][$Article->ID]['Teaser'] = $Article->outputTeaser();
}
unset($Articles);
<?php
/**
* Modèle : admin/edit
* But : liens vers les articles à modifer
* Prérequis : aucun.
*/

$C['PageTitle']='Page d\'accueil des censeurs';
$C['CanonicalURL']='/admin/Edit/';

/**
* Récupère une liste de trailers correspondant à l'objet Param, puis change le lien de /O/ vers /admin/Edit, puis renvoie le tableau
* @param Param:SqlParam les paramètres de récupération.
* @return :array un tableau de trailer.
*/
function getEditTrailers(SqlParam $Param)
{
	$Trailers = Omni::getTrailers($Param);
	foreach($Trailers as &$Trailer)
		$Trailer=str_replace('/O/','/admin/Edit/',$Trailer);
	
	if(count($Trailers)==0)
		$Trailers[] = '<small>Aucun article.</small>';

	return $Trailers;
}

$Param = Omni::buildParam(OMNI_TRAILER_PARAM);


//Articles à corriger.
$Param->Where = 'Statut="A_CORRIGER"';
$C['ACorriger'] = getEditTrailers($Param);


//Articles indeterminés
$Param->Where = 'Statut="INDETERMINE"';
$C['Indetermines'] = getEditTrailers($Param);


//Articles en parution imminente
$Param->Where = 'Statut="ACCEPTE" AND ISNULL(Sortie)';
$C['Acceptes'] = getEditTrailers($Param);


//Articles en parution imminente
$Param->Where = 'Statut="EST_CORRIGE"';
$C['Corriges'] = getEditTrailers($Param);
<?php
/**
* Mod�le : admin/edit
* But : liens vers les articles � modifer
* Pr�requis : aucun.
*/

$C['PageTitle']='Page d\'accueil des censeurs';
$C['CanonicalURL']='/admin/Edit/';

/**
* R�cup�re une liste de trailers correspondant � l'objet Param, puis change le lien de /O/ vers /admin/Edit, puis renvoie le tableau
* @param Param:SqlParam les param�tres de r�cup�ration.
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


//Articles � corriger.
$Param->Where = 'Statut="A_CORRIGER"';
$C['ACorriger'] = getEditTrailers($Param);


//Articles indetermin�s
$Param->Where = 'Statut="INDETERMINE"';
$C['Indetermines'] = getEditTrailers($Param);


//Articles en parution imminente
$Param->Where = 'Statut="ACCEPTE" AND ISNULL(Sortie)';
$C['Acceptes'] = getEditTrailers($Param);


//Articles en parution imminente
$Param->Where = 'Statut="EST_CORRIGE"';
$C['Corriges'] = getEditTrailers($Param);
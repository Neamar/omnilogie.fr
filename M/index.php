<?php
/**
* Modèle : index.php
* But : Générer les données nécessaire à l'affichage de la page d'accueil
* Données à charger :
* - Article du jour complet
* - Articles précédents raccourcis.
*/



//Général
$C['PageTitle'] = 'Omnilogie.fr : la culture générale au quotidien';
$C['CanonicalURL'] = '/';

unset($C['Pods']['lastArticles']);

//Récupérer les derniers articles
$Param = Omni::buildParam(OMNI_SMALL_PARAM);
$Param->Where = 'Sortie <> "" AND Auteurs.Auteur != "Top"';
$Param->Order = 'RAND() DESC';
$Param->Limit = '4';
$Articles = Omni::get($Param);

//Article du jour
$Article=array_shift($Articles);
$C['Header'] = $Article->outputHeader();
$C['LienDuJour'] = Link::omni($Article->Titre);
$C['URLs'] = $Article->getURLs();

$C['Contenu'] = $Article->outputFull();
$C['Categories']=Category::outputTree(Category::getTree($Article->getCategories()));
$C['Similar'] = $Article->outputSimilar();

//Articles précédents
foreach($Articles as &$Article)
{
	$C['Articles'][$Article->ID]['Header'] = $Article->outputHeader(OMNI_SMALL_HEADER);
	$C['Articles'][$Article->ID]['Contenu'] = $Article->outputStart();
	$C['Articles'][$Article->ID]['ReadMore'] = Link::omni($Article->Titre);
}

//Il y a un an sur Omnilogie...
$UnanParams=Omni::buildParam(OMNI_SMALL_PARAM);
$UnanParams->Where = 'Sortie = "' . date("Y-m-d 00:00:00", time() - 60 * 60 * 24 * 365) . '"';
$UnanParams->Limit = '1';

$C['UnAn'] = array();
try
{
	$Article=Omni::getSingleOrThrow($UnanParams);

	$C['UnAn']['Header'] = $Article->outputHeader(OMNI_SMALL_HEADER);
	$C['UnAn']['Contenu'] = $Article->outputStart();
	$C['UnAn']['ReadMore'] = Link::omni($Article->Titre);
} catch (Exception $e) {}

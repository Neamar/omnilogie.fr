<?php
/**
* Modèle : admin/showArticle
*/

$C['PageTitle']='Modification : « ' . $Titre . ' »';
$C['CanonicalURL']='';
/*
* MODIFICATION DES PODS.
* Cette page ajoute plusieurs pods, et en supprime certains pour gagner de la place.
*/
//Virer certains pods pour gagner de la place :
unset($C['Pods']['author-stats'],$C['Pods']['modifiable'],$C['Pods']['publiable']);

//Affichage des dernières actions
$C['Pods']['lastactions']['Content']=Formatting::makeList(Event::getLast(15,$Where,'%DATE% %MODIF% par %AUTEUR% %DIFF%'));


//Table des matières et paramètres par défauts
$C['TOC']=array();
foreach($C['Sections'] as $ID=>&$Section)
{
	if(!isset($Section['Max']))
		$Section['Max']=10;
	if(!isset($Section['Action']))
		$Section['Action']='';
	if(!isset($Section['IsFileForm']))
		$Section['IsFileForm']=false;
	if(!isset($Section['SubmitCaption']))
		$Section['SubmitCaption']='Enregistrer les modifications';

	$C['TOC'][] = '<a href="#' . $ID . '" title="' . $Section['Description'] . '">' . $Section['Titre'] . '</a>';
}

//Bifurquer pour passer sur un autre fichier de vue :
$_GET['P'] = 'admin/index';
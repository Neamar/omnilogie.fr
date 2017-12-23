<?php
/**
* Modèle : admin/edit
* But : éditer un article
* Prérequis : $Article, chargé par le contrôleur.
*/

$C['PageTitle']='Modification de ' . $Article->Titre;
$C['CanonicalURL']=Link::omni($Article->Titre,'/admin/Edit/');

//Virer certains pods pour gagner de la place :
unset($C['Pods']['author-stats'],$C['Pods']['modifiable'],$C['Pods']['twitter'],$C['Pods']['activeAuthor'],$C['Pods']['randomArticle'],$C['Pods']['catCloud']);

//Affichage des dernières actions
$C['Pods']['lastactions']['Title'] = 'Actions sur l\'article';
$C['Pods']['lastactions']['Content']=Formatting::makeList(Event::getLast(150,'Omnilogismes.Titre="' . $Article->Titre . '"','%DATE% %MODIF% par %AUTEUR% %DIFF%'));



//Valeurs par défaut :
$C['Valeurs']=array(
	'titre'=>$Article->Titre,
	'article'=>$Article->Omnilogisme,
	'sources'=>implode("\n",array_keys($Article->getURLs())),
);

Typo::setTexte($Article->Omnilogisme);
$C['Apercu'] = str_replace('<img src="/','<img src="http://omnilogie.fr/',ParseMath(Typo::Parse()));
$C['LienDirect'] = Anchor::omni($Article);
$C['Titre'] = $Article->Titre;
<?php
/**
* Mod�le : admin/edit
* But : �diter un article
* Pr�requis : $Article, charg� par le contr�leur.
*/

$C['PageTitle']='Modification de ' . $Article->Titre;
$C['CanonicalURL']=Link::omni($Article->Titre,'/admin/Edit/');

//Virer certains pods pour gagner de la place :
unset($C['Pods']['author-stats'],$C['Pods']['modifiable'],$C['Pods']['twitter'],$C['Pods']['activeAuthor'],$C['Pods']['randomArticle'],$C['Pods']['catCloud']);

//Affichage des derni�res actions
$C['Pods']['lastactions']['Title'] = 'Actions sur l\'article';
$C['Pods']['lastactions']['Content']=Formatting::makeList(Event::getLast(150,'Omnilogismes.Titre="' . $Article->Titre . '"','%DATE% %MODIF% par %AUTEUR% %DIFF%'));



//Valeurs par d�faut :
$C['Valeurs']=array(
	'titre'=>$Article->Titre,
	'article'=>$Article->Omnilogisme,
	'sources'=>implode("\n",array_keys($Article->getURLs())),
);

Typo::setTexte($Article->Omnilogisme);
$C['Apercu'] = str_replace('<img src="/','<img src="http://omnilogie.fr/',ParseMath(Typo::Parse()));
$C['LienDirect'] = Anchor::omni($Article);
$C['Titre'] = $Article->Titre;
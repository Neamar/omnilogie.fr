<?php
/**
* Modèle : membres/correction
* But : éditer un article
* Données à charger : liste des propositions réservées
* Prérequis : $Article, chargé par le contrôleur.
*/

$C['PageTitle']='Modification de ' . $Article->Titre;
$C['CanonicalURL']=Link::omni($Article->Titre,'/membres/Edit/');

//Valeurs par défaut :
$C['Valeurs']=array(
'id'=>$Article->ID,
'titre'=>$Article->Titre,
'article'=>$Article->Omnilogisme,
'sources'=>implode("\n",array_keys($Article->getURLs())),
'brouillon'=>($Article->Statut=='BROUILLON'?'checked="checked"':''),
);

Typo::setTexte($Article->Omnilogisme);
$C['Apercu'] = ParseMath(Typo::Parse());
$C['LienDirect'] = Anchor::omni($Article);
<?php
/**
* Mod�le : membres/correction
* But : �diter un article
* Donn�es � charger : liste des propositions r�serv�es
* Pr�requis : $Article, charg� par le contr�leur.
*/

$C['PageTitle']='Modification de ' . $Article->Titre;
$C['CanonicalURL']=Link::omni($Article->Titre,'/membres/Edit/');

//Valeurs par d�faut :
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
<?php
/**
* Fichier d'évènement
* Event::NOUVELLE_CATEGORIE
*
* @standalone
* @access taggers
* Mettre à jour le pod "dernières catégories crées"
*/
//Derniers ajouts dans l'arbre
$ListeCategorieSQL=SQL::query('SELECT Categorie FROM OMNI_Categories ORDER BY ID DESC LIMIT 5');
$List=array();
while($Category = mysql_fetch_assoc($ListeCategorieSQL))
	$List[] = Anchor::category($Category['Categorie']);

Cache::set('Pods','last-added',Formatting::makeList($List));
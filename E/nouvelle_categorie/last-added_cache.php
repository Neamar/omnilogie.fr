<?php
/**
* Fichier d'�v�nement
* Event::NOUVELLE_CATEGORIE
*
* @standalone
* @access taggers
* Mettre � jour le pod "derni�res cat�gories cr�es"
*/
//Derniers ajouts dans l'arbre
$ListeCategorieSQL=SQL::query('SELECT Categorie FROM OMNI_Categories ORDER BY ID DESC LIMIT 5');
$List=array();
while($Category = mysql_fetch_assoc($ListeCategorieSQL))
	$List[] = Anchor::category($Category['Categorie']);

Cache::set('Pods','last-added',Formatting::makeList($List));
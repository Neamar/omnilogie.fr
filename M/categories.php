<?php
/**
* Mod�le : Categories
* But : Afficher la liste des cat�gories
* Donn�es � charger : L'arbre des cat�gories.
* Les derni�res cat�gories ajout�es.
*/
//Categories

$C['PageTitle'] = 'Liste des cat�gories';
$C['CanonicalURL'] = '/Liste/';

//� placer apr�s la construction des sagas car l'arbre est modifi� par outputTree.
$ListeCategorieSQL=SQL::query('SELECT DISTINCT(Categorie) FROM OMNI_Liens');
$List=array();
while($Category = mysql_fetch_assoc($ListeCategorieSQL))
	$List[] = $Category['Categorie'];

$C['Categories']=Category::outputTree(Category::getTree($List));


prependPod('last-added','Derniers ajouts sur l\'arbre',Cache::get('Pods','last-added'));
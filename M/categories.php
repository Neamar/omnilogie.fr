<?php
/**
* Modèle : Categories
* But : Afficher la liste des catégories
* Données à charger : L'arbre des catégories.
* Les dernières catégories ajoutées.
*/
//Categories

$C['PageTitle'] = 'Liste des catégories';
$C['CanonicalURL'] = '/Liste/';

//à placer après la construction des sagas car l'arbre est modifié par outputTree.
$ListeCategorieSQL=SQL::query('SELECT DISTINCT(Categorie) FROM OMNI_Liens');
$List=array();
while($Category = mysql_fetch_assoc($ListeCategorieSQL))
	$List[] = $Category['Categorie'];

$C['Categories']=Category::outputTree(Category::getTree($List));


prependPod('last-added','Derniers ajouts sur l\'arbre',Cache::get('Pods','last-added'));
<?php
/**
* Modèle : showCategory
* But : Générer les données nécessaire à l'affichage de la page d'accueil
* Données à charger : 
* - Catégories entourantes
* - Articles dans la catégorie.
* Pré requis du contrôleur associé : la variable $Category avec l'entrée propre demandée.
*/

$C['PageActuelle'] = (isset($_GET['Page'])?' : page ' . intval($_GET['Page']):'');
$C['PageTitle'] = 'Liste des articles dans la catégorie ' . $Category . $C['PageActuelle'];
$C['CanonicalURL'] = Link::category($Category) . (isset($_GET['Page'])?'Page-' . $_GET['Page']:'');
$C['Category'] = $Category;

//Catégories entourantes :
$Siblings = Category::getSiblings($Category);
$Tree = Category::getTree($Siblings);

//Si c'est une saga, afficher les derniers articles dans le menu de droite
if(isset($Tree['Sagas']) && is_array($Tree['Sagas']))
	Category::buildSaga($Tree['Sagas']);

$C['AroundTree']=Category::outputTree($Tree);

//Récupérer les articles
$Param = Omni::buildParam(OMNI_SMALL_PARAM);

$Param->Where = '
Statut="ACCEPTE"
AND !ISNULL(Sortie)
AND Liens.Categorie IN(' . implode(',',array_map('Category::escapeAndQuote',$Siblings)) . ')';

$Param->Order = 'Omnilogismes.Sortie DESC';

Formatting::makePage($Param,Link::category($Category));
<?php
/**
* Mod�le : showCategory
* But : G�n�rer les donn�es n�cessaire � l'affichage de la page d'accueil
* Donn�es � charger : 
* - Cat�gories entourantes
* - Articles dans la cat�gorie.
* Pr� requis du contr�leur associ� : la variable $Category avec l'entr�e propre demand�e.
*/

$C['PageActuelle'] = (isset($_GET['Page'])?' : page ' . intval($_GET['Page']):'');
$C['PageTitle'] = 'Liste des articles dans la cat�gorie ' . $Category . $C['PageActuelle'];
$C['CanonicalURL'] = Link::category($Category) . (isset($_GET['Page'])?'Page-' . $_GET['Page']:'');
$C['Category'] = $Category;

//Cat�gories entourantes :
$Siblings = Category::getSiblings($Category);
$Tree = Category::getTree($Siblings);

//Si c'est une saga, afficher les derniers articles dans le menu de droite
if(isset($Tree['Sagas']) && is_array($Tree['Sagas']))
	Category::buildSaga($Tree['Sagas']);

$C['AroundTree']=Category::outputTree($Tree);

//R�cup�rer les articles
$Param = Omni::buildParam(OMNI_SMALL_PARAM);

$Param->Where = '
Statut="ACCEPTE"
AND !ISNULL(Sortie)
AND Liens.Categorie IN(' . implode(',',array_map('Category::escapeAndQuote',$Siblings)) . ')';

$Param->Order = 'Omnilogismes.Sortie DESC';

Formatting::makePage($Param,Link::category($Category));
<?php
/**
* Modèle : admin/showTag
* But : permettre de tagger un article
* Prérequis : l'article
*/

$C['PageTitle']='Taggage : « ' . $Article->Titre . ' »';
$C['CanonicalURL']='';
/*
* MODIFICATION DES PODS.
* Cette page ajoute plusieurs pods, et en supprime certains pour gagner de la place.
*/
unset(
	$C['Pods']['author-stats'],
	$C['Pods']['modifiable'],
	$C['Pods']['connected'],
	$C['Pods']['publiable'],
	$C['Pods']['lastactions'],
	$C['Pods']['lastArticles'],
	$C['Pods']['catCloud'],
	$C['Pods']['activeAuthor'],
	$C['Pods']['randomArticle'],
	$C['Pods']['twitter'],
);


//Liste des mots clés de l'article
$KeywordsSQL = SQL::query('SELECT Categorie
FROM OMNI_Liens
WHERE News=' . $Article->ID);

$Keywords=array();
while($Keyword = mysql_fetch_assoc($KeywordsSQL))
	$Keywords[] = $Keyword['Categorie'];

//Liste des mots clés existants
$ListeCategorieSQL=SQL::query('SELECT DISTINCT(Categorie) FROM OMNI_Categories');
$Liste=array();
while($Category = mysql_fetch_assoc($ListeCategorieSQL))
	$Liste[] = $Category['Categorie'];

$Categories=Category::getTree($Liste);


//Charger l'article
$C['Header'] = $Article->outputHeader();
$C['Article'] = $Article->outputFull();

function getChecks($Elements)
{
	global $Keywords;
	$R = '<div>';
	foreach($Elements as $Element=>$SubCategories)
	{
		$R .= '<input type="checkbox" name="KW[]" value="' . $Element . '" ' . (in_array($Element,$Keywords)?'checked="checked"':'') . '/>' . $Element . '<br />';
		if($SubCategories!=1)
			$R .= getChecks($SubCategories);
	}
	$R .= "</div>\n";
	return $R;
}

//Charger les menus
unset($Categories['Liste']);
foreach($Categories as $MainCategory=>$SubCategories)
{
	if($SubCategories!=1)
		$Contenu = getChecks($SubCategories);
	else
		$Contenu = '<div><input type="checkbox" name="KW[]" value="' . $MainCategory . '" ' . (in_array($MainCategory,$Keywords)?'checked="checked"':'') . '/>' . $MainCategory . '</div>';

	prependPod($MainCategory,$MainCategory,$Contenu);
}


$Total = SQL::singleQuery('SELECT COUNT(*) AS S
FROM OMNI_Omnilogismes O
WHERE ID NOT IN (SELECT DISTINCT News FROM OMNI_Liens)
AND Statut IN("ACCEPTE","INDETERMINE","EST_CORRIGE")');
$TotalParus = SQL::singleQuery('SELECT COUNT(*) AS S
FROM OMNI_Omnilogismes O
WHERE ID NOT IN (SELECT DISTINCT News FROM OMNI_Liens)
AND Statut = "ACCEPTE"');

prependPod("author-stats","À tagger...","<p>" . $Total['S'] . ' à tagger (dont ' . $TotalParus['S'] . ' parus)</p>');

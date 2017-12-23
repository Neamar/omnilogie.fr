<?php
/**
* Contrôleur : admin/showTag.php
* But : tagger un article
*/

//////////////////////////////////////////////////////
//Fonctionnalités du contrôleur :

function redirectToUntagged()
{
	$Article = SQL::singleQuery('SELECT Titre
	FROM OMNI_Omnilogismes O
	LEFT JOIN OMNI_Liens L ON (O.ID = L.News)
	WHERE Statut IN("ACCEPTE","INDETERMINE","EST_CORRIGE")
	GROUP BY O.ID
	HAVING COUNT(Categorie) = 0
	ORDER BY (Statut!="ACCEPTE")
	LIMIT 1');

	if(!is_null($Article))
		Debug::redirect(Link::omni($Article['Titre'],'/admin/Tag/'));
	else
	{
		$_SESSION['FutureMessage'] .= ' Et voilà qui met fin à la séance tagging :)';
		Debug::redirect('/admin/');
	}
}

// Redirect if access from /admin/Tag/
if(empty($_GET['Titre']))
{
	redirectToUntagged();
	exit();
}

//Récupérer le titre de la page

$TitreOmni = Encoding::decodeFromGet('Titre');


//Vérifier que l'article existe :

//L'article existe-t-il ?
$Param = Omni::buildParam(OMNI_SMALL_PARAM);
$Param->Where = 'Omnilogismes.Titre="' . $TitreOmni . '"';

$Article = Omni::getSingle($Param);

if(isset($_POST['KW']))
{
	SQL::query('DELETE FROM OMNI_Liens WHERE News=' . $Article->ID);
	$Cles = array_unique($_POST['KW']);

	foreach($Cles as $Cle)
	{
		SQL::insert('OMNI_Liens',array('News'=>$Article->ID,'Categorie'=>$Cle));
	}

	$Article->registerModif(Event::TAGGAGE);


	if(!isset($_POST['goNext']))
		$C['Message'] = 'Article taggé !';
	else
	{
		$_SESSION['FutureMessage'] = 'Article taggé !';
		redirectToUntagged();
	}
}
<?php
/**
* Contr�leur : admin/showTag.php
* But : tagger un article
*/

//////////////////////////////////////////////////////
//Fonctionnalit�s du contr�leur :

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
		$_SESSION['FutureMessage'] .= ' Et voil� qui met fin � la s�ance tagging :)';
		Debug::redirect('/admin/');
	}
}

// Redirect if access from /admin/Tag/
if(empty($_GET['Titre']))
{
	redirectToUntagged();
	exit();
}

//R�cup�rer le titre de la page

$TitreOmni = Encoding::decodeFromGet('Titre');


//V�rifier que l'article existe :

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
		$C['Message'] = 'Article tagg� !';
	else
	{
		$_SESSION['FutureMessage'] = 'Article tagg� !';
		redirectToUntagged();
	}
}
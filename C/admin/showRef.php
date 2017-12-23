<?php
/**
* ContrÙleur : admin/ref.php
* But : enregistrer les modifications apportÈes ‡ un article.
*/

//////////////////////////////////////////////////////
//FonctionnalitÈs du contrÙleur :

//RÈcupÈrer le titre de la page

$TitreOmni = Encoding::decodeFromGet('Titre');

if(isset($_GET['Search']))
{
	$Mots = explode(' ',$TitreOmni);

	foreach($Mots as $Mot)
	{
		if(strlen($Mot)<=5 && count($Mots)>1)
			continue;
		//$RPattern = 'Omnilogisme REGEXP "\\\\\\\\ref\\\\[[a-z0-9A-ZÈË‡ÁÍ…» ¿«,!?_;().: -]+\\\\]\\\\{[a-z0-9A-ZÈË‡ÁÍ…» ¿«,!?_;().: -]*' . preg_quote($Mot) . '[a-z0-9A-ZÈË‡ÁÍ…» ¿«,!?_;().: -]*\\\\}"';
		$RPattern = 'Omnilogisme REGEXP "\\\\\\\\ref\\\\[.+\\\\]\\\\{.*' . str_replace('\\','\\\\',preg_quote($Mot)) . '"';

		$Similaires = SQL::query('SELECT Titre, Omnilogisme
	FROM OMNI_Omnilogismes
	WHERE ' . $RPattern);

		$Retour = array();
		while($Similaire = mysql_fetch_assoc($Similaires))
		{
			if(preg_match('`\\\\ref\\[(.+)\\]{.*' . preg_quote($Mot) . '.*}`iU',$Similaire['Omnilogisme'],$Ref))
			{
				$Retour[$Ref[1]] = '<li>' . utf8_encode('<a href="#" title="Mot ´&nbsp;' . $Mot . '&nbsp;ª, article ´&nbsp;' . $Similaire['Titre'] . '&nbsp;ª" onclick="prepareRef(\'' . addslashes($Ref[1]) . '\')">' . str_replace('_',' ',$Ref[1])) . '</a></li>';
			}
		}
	}

	echo implode("\n",$Retour);

	exit();
}

//VÈrifier que l'article existe :

//L'article existe-t-il ?
$Param = Omni::buildParam(OMNI_SMALL_PARAM);
$Param->Select .=',Statut';
$Param->Where = 'Omnilogismes.Titre="' . $TitreOmni . '"';

$Article = Omni::getSingle($Param);

if(isset($_POST['titre']))
{
	//Nettoyer un peu les donnÈes :
	$_POST['article'] = Input::prepareOmni($_POST['article']);
	$_POST['titre']= str_replace(array('\"','&'),array('','et'),$_POST['titre']);

	$ToUpdate = array
	(
		'Titre'=>$_POST['titre'],
		'Omnilogisme'=>$_POST['article'],
	);

	if($_POST['titre']=='')
		$C['Message'] = 'Impossible de supprimer le titre d\'un article.';
	elseif($_POST['article']=='')
		$C['Message'] = 'Vandalisme dÈtectÈ. Aucune modification enregistrÈe.';
	elseif(!SQL::update('OMNI_Omnilogismes', $Article->ID, $ToUpdate))
		$C['Message'] = 'Impossible d\'enregistrer les modifs. Le titre est peut-Ítre dÈj‡ pris ?';
	else
	{
		//Logger la modification
		$Article->registerModif(Event::REF,OMNI_SAVE);

		//RÈussi !
		if(!isset($C['Message']))//Exemple de isset : Èchec lors du chargement de l'image.
			$C['Message'] = 'Modifications enregistrÈes !';
		$C['MessageClass'] = 'info';


		//Changement du titre : assurer la redirection vers la nouvelle page
		if(strtolower($Article->Titre)!=strtolower(stripslashes($_POST['titre'])))
			Debug::redirect(Link::omni(stripslashes($_POST['titre']),'/admin/Edit/'));

		//Remettre ‡ jour l'article :
		$Article = Omni::getSingle($Param);
	}
}
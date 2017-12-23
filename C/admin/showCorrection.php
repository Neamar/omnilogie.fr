<?php
/**
* Contr�leur : admin/edit.php
* But : enregistrer les modifications apport�es � un article.
*/

//////////////////////////////////////////////////////
//Fonctionnalit�s du contr�leur :

//R�cup�rer le titre de la page

$TitreOmni = Encoding::decodeFromGet('Titre');

//V�rifier que l'article existe :

//L'article existe-t-il ?
$Param = Omni::buildParam(OMNI_SMALL_PARAM);
$Param->Select .=',Statut';
$Param->Where = 'Omnilogismes.Titre="' . $TitreOmni . '"';

$Article = Omni::getSingle($Param);

if(isset($_POST['titre']))
{
	//Nettoyer un peu les donn�es :
	$_POST['article'] = Input::prepareOmni($_POST['article']);
	$_POST['titre']= str_replace(array('\"','&'),array('','et'),$_POST['titre']);

	$ToUpdate = array
	(
		'Titre'=>$_POST['titre'],
		'Omnilogisme'=>$_POST['article'],
	);

	//Noter comme corrig� les articles qui n'avaient jamais �t� relus, ou qui avaient une demande de modification.
	if($Article->Statut=='INDETERMINE' || $Article->Statut=='A_CORRIGER')
		$ToUpdate['Statut'] = 'EST_CORRIGE';

	if($_POST['titre']=='')
		$C['Message'] = 'Impossible de supprimer le titre d\'un article.';
	elseif($_POST['article']=='')
		$C['Message'] = 'Vandalisme d�tect�. Aucune modification enregistr�e.';
	elseif(!SQL::update('OMNI_Omnilogismes', $Article->ID, $ToUpdate))
		$C['Message'] = 'Impossible d\'enregistrer les modifs. Le titre est peut-�tre d�j� pris ?';
	else
	{
		//Comparer les sources avec celles actuellement en m�moire pour minimiser les op�rations sur la base :
		$NLiens = explode("\n",str_replace("\r\n","\n",$_POST['sources']));
		Util::commitUrls($Article,$NLiens);

		//Logger la modification
		$Article->registerModif(Event::EDITION,OMNI_SAVE);

		//R�ussi !
		if(!isset($C['Message']))//Exemple de isset : �chec lors du chargement de l'image.
			$C['Message'] = 'Modifications enregistr�es !';
		$C['MessageClass'] = 'info';


		//Changement du titre : assurer la redirection vers la nouvelle page
		if(strtolower($Article->Titre)!=strtolower(stripslashes($_POST['titre'])))
			Debug::redirect(Link::omni(stripslashes($_POST['titre']),'/admin/Edit/'));

		//Remettre � jour l'article :
		$Article = Omni::getSingle($Param);
	}
}
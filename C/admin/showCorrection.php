<?php
/**
* Contrôleur : admin/edit.php
* But : enregistrer les modifications apportées à un article.
*/

//////////////////////////////////////////////////////
//Fonctionnalités du contrôleur :

//Récupérer le titre de la page

$TitreOmni = Encoding::decodeFromGet('Titre');

//Vérifier que l'article existe :

//L'article existe-t-il ?
$Param = Omni::buildParam(OMNI_SMALL_PARAM);
$Param->Select .=',Statut';
$Param->Where = 'Omnilogismes.Titre="' . $TitreOmni . '"';

$Article = Omni::getSingle($Param);

if(isset($_POST['titre']))
{
	//Nettoyer un peu les données :
	$_POST['article'] = Input::prepareOmni($_POST['article']);
	$_POST['titre']= str_replace(array('\"','&'),array('','et'),$_POST['titre']);

	$ToUpdate = array
	(
		'Titre'=>$_POST['titre'],
		'Omnilogisme'=>$_POST['article'],
	);

	//Noter comme corrigé les articles qui n'avaient jamais été relus, ou qui avaient une demande de modification.
	if($Article->Statut=='INDETERMINE' || $Article->Statut=='A_CORRIGER')
		$ToUpdate['Statut'] = 'EST_CORRIGE';

	if($_POST['titre']=='')
		$C['Message'] = 'Impossible de supprimer le titre d\'un article.';
	elseif($_POST['article']=='')
		$C['Message'] = 'Vandalisme détecté. Aucune modification enregistrée.';
	elseif(!SQL::update('OMNI_Omnilogismes', $Article->ID, $ToUpdate))
		$C['Message'] = 'Impossible d\'enregistrer les modifs. Le titre est peut-être déjà pris ?';
	else
	{
		//Comparer les sources avec celles actuellement en mémoire pour minimiser les opérations sur la base :
		$NLiens = explode("\n",str_replace("\r\n","\n",$_POST['sources']));
		Util::commitUrls($Article,$NLiens);

		//Logger la modification
		$Article->registerModif(Event::EDITION,OMNI_SAVE);

		//Réussi !
		if(!isset($C['Message']))//Exemple de isset : échec lors du chargement de l'image.
			$C['Message'] = 'Modifications enregistrées !';
		$C['MessageClass'] = 'info';


		//Changement du titre : assurer la redirection vers la nouvelle page
		if(strtolower($Article->Titre)!=strtolower(stripslashes($_POST['titre'])))
			Debug::redirect(Link::omni(stripslashes($_POST['titre']),'/admin/Edit/'));

		//Remettre à jour l'article :
		$Article = Omni::getSingle($Param);
	}
}
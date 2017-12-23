<?php
/**
* Contr�leur : membres/correction.php
* But : Corriger un article du membre
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

if($Article->Auteur != AUTHOR)
	Debug::fail('Vous n\'avez pas le droit de modifier cet article !',404);
elseif(!is_null($Article->Date))
	Debug::fail('Vous n\'avez plus le droit de modifier cet article ! Contactez un administrateur sur admin@omnilogie.fr.',404);

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
	if(isset($_POST['brouillon']))
		$ToUpdate['Statut'] = 'BROUILLON';
	else
		$ToUpdate['Statut'] = 'INDETERMINE';

	if($_POST['titre']=='')
		$C['Message'] = 'Donnez un titre � votre article ! Impossible de l\'enregistrer sans titre.';
	elseif($_POST['article']=='')
		$C['Message'] = 'Vandalisme d�tect�. Aucune modification enregistr�e.';
	elseif(!SQL::update('OMNI_Omnilogismes', $Article->ID, $ToUpdate, ' AND Auteur=' . AUTHOR_ID))
		$C['Message'] = 'Oh non, impossible d\'enregistrer votre article :( Le titre est peut-�tre d�j� pris ?';
	else
	{
		//Comparer les sources avec celles actuellement en m�moire pour minimiser les op�rations sur la base :
		$NLiens = explode("\n",str_replace("\r\n","\n",$_POST['sources']));
		Util::commitUrls($Article,$NLiens);

		//Logger la modification
		$Article->registerModif(Event::EDITION,OMNI_SAVE);

		//R�ussi !
		if(!isset($C['Message']))//Exemple de isset : �chec lors du chargement d'une image inclue dans l'article
			$C['Message'] = 'Modifications enregistr�es !';
		$C['MessageClass'] = 'info';

		//Gestion de la banni�re
		$key = 'banniere-' . $Article->ID;
		if(isset($_FILES[$key]) && $_FILES[$key]['name']!='')
		{
			//Pas plus d'un Mo, merci.
			if($_FILES[$key]['size'] > 1000000)
			{
				$C['Message'] = 'Merci de ne pas envoyer d\'image trop lourde.';
			}
			else if(Admin::banniereControleurCallback($Article, null, 'banniere'))
			{
				$C['Message'] .= ' Les modifications sont enregistr�es.';
			}
			else
			{
				//Une erreur s'est produite : enlever la classe info.
				unset($C['MessageClass']);
			}
		}

		//Changement du titre :
		if(strtolower($Article->Titre)!=strtolower(stripslashes($_POST['titre'])))
			Debug::redirect(Link::omni(stripslashes($_POST['titre']),'/membres/Edit/'));

		//Remettre � jour l'article :
		$Article = Omni::getSingle($Param);
	}
}
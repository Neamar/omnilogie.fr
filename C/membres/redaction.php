<?php
/**
* Contr�leur : membres/redaction
* But : Ajouter l'article en BDD si possible
* En cas de succ�s, rediriger vers /membres/Edit
* Sinon, afficher le formulaire.
*/

//////////////////////////////////////////////////////
//Fonctionnalit�s du contr�leur :

if(isset($_POST['titre']))
{
	//Nettoyer un peu les donn�es :
	$_POST['titre'] = Input::sanitize($_POST['titre']);
	$_POST['titre']= str_replace(array('\"','&'),array('','et'),$_POST['titre']);

	$_POST['article'] = Input::sanitize($_POST['article']);


	$ToInsert = array
	(
		'Auteur'=>AUTHOR_ID,
		'Titre'=>$_POST['titre'],
		'Omnilogisme'=>$_POST['article'],
		'NbVues'=>0,
		'NbVotes'=>0,
		'Anecdote'=>"",
	);

	if(isset($_POST['brouillon']))
		$ToInsert['Statut'] = 'BROUILLON';

	if($_POST['titre']=='')
		$C['Message'] = 'Donnez un titre � votre article ! Impossible de l\'enregistrer sans titre.';
	elseif(strpos($_POST['titre'],'?') !==false && strpos($_POST['titre'],'?') != strlen($_POST['titre'])-1)
		$C['Message'] = "Le titre d'un article ne doit pas comporter de points d'interrogation. Une exception est tol�r�e si le point d'interrogation se situe en toute fin de l'article";
	elseif($_POST['article']=='')
		$C['Message'] = 'Les articles vides ont tendance � ne pas passionner les foules ;) Entrez un minimum de contenu !';
	elseif(!SQL::insert('OMNI_Omnilogismes',$ToInsert))
		$C['Message'] = 'Oh non, impossible d\'enregistrer votre article :( Le titre est peut-�tre d�j� pris ?';
	else
	{
		$NouvelArticle=new Omni();
		$NouvelArticle->ID= mysql_insert_id();
		$NouvelArticle->Titre=stripslashes($_POST['titre']);

		//Tenir � jour la liste des articles
		$_SESSION['Membre']['Articles'][$NouvelArticle->ID] = $NouvelArticle->ID;

		//Notez la r�alisation de la proposition si n�cessaire :
		if(isset($_POST['proposition']) && is_numeric($_POST['proposition']))
			SQL::update('OMNI_Propositions',$_POST['proposition'],array('OmniID'=>$NouvelArticle->ID),'AND ReservePar=' . AUTHOR_ID);

		//Enregistrer les sources :
		$Liens = explode("\n",$_POST['sources']);
		$ToInsert = array('Reference'=>$NouvelArticle->ID,'URL'=>'');
		foreach($Liens as $Lien)
		{
			if(strlen($Lien)>4)
			{
				$ToInsert['URL'] = $Lien;
				SQL::insert('OMNI_More',$ToInsert);
			}
		}

		//R�ussi !
		$NouvelArticle->registerModif(Event::NOUVEAU,OMNI_SAVE);

		$_SESSION['FutureMessage'] = 'Votre article est enregistr�, merci !';
		$_SESSION['FutureMessageClass'] = 'info';

		//Rediriger le membre vers l'�dition.
		Debug::redirect(Link::omni($NouvelArticle->Titre,'/membres/Edit/'));
	}
}

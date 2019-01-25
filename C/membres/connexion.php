<?php
/**
* Contr�leur : membres/connexion.php
* But : Effectuer la connexion si possible.
* En cas de succ�s, rediriger vers /membres/
* Sinon, afficher le formulaire.
* Note: on peut se connecter de deux fa�ons. Soit via le fomulaire disponibles sur membres/connexion, soit en sp�ciifiant la variable GET "membre" avec le hash de l'utilisateur.
*/

//////////////////////////////////////////////////////
//Fonctionnalit�s du contr�leur :


if((!empty($_POST['pseudo']) && !empty($_POST['password'])) || isset($_GET['membre']) || (isset($_SERVER['REMOTE_USER'])  && isset($_SERVER['SAFE_LOG'])))
{
	//Personnes tentant de se connecter � l'ancienne, avec un hash directement dans l'URL (venant d'un mail probablement)
	if(isset($_GET['membre']))
		$Where ='Hash="' . mysql_real_escape_string($_GET['membre']) . '"';
	//Personnes authentifi�es par HTTP (cf Authenticate::)
	elseif(isset($_SERVER['REMOTE_USER']) && isset($_SERVER['SAFE_LOG']))
		$Where = 'Auteur="' . mysql_real_escape_string($_SERVER['REMOTE_USER']) . '"';
	//Authentification POST depuis /membres/connexion
	else
		$Where ='Auteur="' . mysql_real_escape_string($_POST['pseudo']) . '" AND Pass="' . sha1($_POST['password']) . '"';


	$Auteur=SQL::singleQuery('SELECT ID, Auteur, Hash
	FROM OMNI_Auteurs
	WHERE ' . $Where);

	if(!is_numeric($Auteur['ID']))
		$C['Message'] = 'Uh oh... impossible de vous identifier avec ces valeurs. Merci de r�essayer !';
	else
	{
		//Rediriger vers l'espace membre, ou vers la page d'o� on vient de se faire jeter.
		$RedirectTo = (isset($_SESSION['Membre']['RedirectTo'])?$_SESSION['Membre']['RedirectTo']:'/membres/');

		$_SESSION['Membre'] = array(
			'ID'=>$Auteur['ID'],
			'Pseudo'=>$Auteur['Auteur'],
			'Hash'=>$Auteur['Hash'],
			'Articles'=>array(),
			);

		//Charger la liste des articles de l'auteur pour pouvoir afficher facilement des liens.
		//Cette liste doit �tre mise � jour lors de la r�daction d'un article !
		$Articles=SQL::query('SELECT ID, Titre FROM OMNI_Omnilogismes WHERE Auteur=' . $Auteur['ID']);
		while($Article = mysql_fetch_assoc($Articles))
			$_SESSION['Membre']['Articles'][$Article['ID']]=$Article['Titre'];

		$_SESSION['FutureMessage'] = 'Vous �tes connect� ! <a href="/membres/Redaction">Cliquez pour r�diger un nouvel article.</a>';
		$_SESSION['FutureMessageClass'] = 'info';

		session_write_close();
		//Fin de l'identification.
		Debug::redirect($RedirectTo,302);
	}
}

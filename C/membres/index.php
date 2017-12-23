<?php
/**
* Contr�leur : membres/index.php
* But : Enregistrer les modifications du membre si n�cessaire.
*/
define('DEFAUT_PRESENTATION','Entrez votre histoire. Ce champ ne sert pas pour un omnilogisme !');

//////////////////////////////////////////////////////
//Fonctionnalit�s du contr�leur :
if(!empty($_POST['auteur']))
{
	if($_POST['password']!=$_POST['password2'] && $_POST['password2']!='')
		$C['Message'] = 'Les deux mots de passe ne concordent pas !';
	else
	{
		$Update = array(
		'Auteur'=>$_POST['auteur'],
		'Mail'=>$_POST['mail'],
		'MailPublic'=>$_POST['public'],
		);

		if($_POST['histoire']!=DEFAUT_PRESENTATION)
			$Update['Histoire'] = $_POST['histoire'];

		if($_POST['password']!='' && $_POST['password2']!='')
			$Update['Pass'] = sha1($_POST['password']);

		if(preg_match('`^pub-[0-9]+$`', $_POST['adsense']))
		{
			$Update['Adsense'] = $_POST['adsense'];
		}
		else
		{
			$Update['Adsense'] = '';
		}

		if(preg_match('`^https://plus.google.com/`', $_POST['googleplus']))
		{
			$Update['GooglePlus'] = $_POST['googleplus'];
		}
		else
		{
			$Update['GooglePlus'] = '';
		}
		
		if(!SQL::update('OMNI_Auteurs',AUTHOR_ID,$Update))
			$C['Message']='Impossible de mettre � jour votre profil.' . mysql_error();
		else
		{
			//Rediriger pour mettre � jour AUTHOR.
			$_SESSION['Membre']['Pseudo'] = stripslashes($_POST['auteur']);
			$_SESSION['FutureMessage']='Informations modifi�es !';
			$_SESSION['FutureMessageClass']='info';
			Debug::redirect('/membres/');
		}
	}
}
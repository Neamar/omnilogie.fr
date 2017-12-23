<?php
/**
* Contrôleur : membres/inscription.php
* But : Effectuer l'inscription si possible.
* En cas de succès, rediriger vers /membres/
* Sinon, afficher le formulaire.
*/

//////////////////////////////////////////////////////
//Fonctionnalités du contrôleur :

if(!empty($_POST['pseudo']) && isset($_POST['password']))
{
	$Hash = md5('OMNI_LOGIEv2' . uniqid());
	if($_POST['password']=='')
		$C['Message']='Le mot de passe ne doit pas être vide.';
	elseif($_POST['password']!=$_POST['password2'])
		$C['Message']='Les deux mots de passe ne concordent pas :)';
	elseif(!preg_match('#^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$#i',$_POST['mail']))
		$C['Message']='Adresse e-mail incorrecte : ' . $_POST['mail'];
	elseif(!SQL::insert('OMNI_Auteurs',array('Auteur'=>$_POST['pseudo'],'Pass'=>sha1($_POST['password']),'Mail'=>$_POST['mail'],'DernierMail'=>date('Y-m-d'),'Hash'=>$Hash)))
		$C['Message'] = 'Impossible de vous enregistrer avec cet identifiant / cet e-mail.' . mysql_error();
	else
	{
		//Réussi !
		//Connecter le membre :
		$_SESSION['Membre'] = array('ID'=>mysql_insert_id(),'Pseudo'=>stripslashes($_POST['pseudo']), 'Hash' =>$Hash);
		//Et afficher son espace membre :
		Debug::redirect('/membres/',302);
	}
}
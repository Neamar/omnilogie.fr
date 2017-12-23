<?php
/**
* Contrôleur : membres/oubli
* But : Renvoyer un nouveau mot de passe
*/

//////////////////////////////////////////////////////
//Fonctionnalités du contrôleur :



if(isset($_POST['mail']))
{
	$Membre = SQL::singleQuery('SELECT ID, Auteur, Mail, Hash FROM OMNI_Auteurs WHERE Mail="' . $_POST['mail'] . '"');
	if(!is_null($Membre))
	{
		$length = 8;
		$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890';
		$chars_length = (strlen($chars) - 1);

		// Start our string
		$string = $chars{rand(0, $chars_length)};

		// Generate random string
		for ($i = 1; $i < $length; $i = strlen($string))
		{
			// Grab a random character from our list
			$r = $chars{rand(0, $chars_length)};

			// Make sure the same two characters don't appear next to each other
			if ($r != $string{$i - 1}) $string .=  $r;
		}

		// Return the string
		$Mdp = $string;

		External::mail($Membre['Mail'],'Remise à zéro du mot de passe','<p>Bonjour,</p>
		<p>Vous avez fait une demande pour réinitialiser votre mot de passe sur Omnilogie.fr.</p>
		<p>Votre nouveau mot de passe : <strong>' . $Mdp . '</strong></p>
		<p>Vous pouvez aussi vous connecter directement en <a href="' . URL . '/membres/?membre=' . $Membre['Hash'] . '">cliquant sur ce lien</a> (le changement de mot de passe reste effectif)</p>
		<p><small>Vous n\'avez rien demandé ? Répondez à ce mail en incluant ce message. IP du demandeur : ' . $_SERVER['REMOTE_ADDR'] . '</p>');

		SQL::update('OMNI_Auteurs',$Membre['ID'],array('Pass'=>sha1($Mdp)));
		$_SESSION['FutureMessage']='Mot de passe correctement réinitialisé.';
		$_SESSION['FutureMessageClass'] = 'info';
		Debug::redirect('/membres/Connexion');
	}
	else
		$C['Message'] = 'Aucun membre n\'utilise cette adresse mail.';
}
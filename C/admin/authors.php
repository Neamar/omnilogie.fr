<?php
/**
* Contrôleur : admin/authors
* But : enregistrer les nouvelles catégories
*/

//////////////////////////////////////////////////////
//Fonctionnalités du contrôleur :

if(isset($_POST['mail']) && AUTHOR_ID==1)
{
	function wrap($item)
	{
		return '%' . $item . '%';
	}

	function getRandomString()
	{
		$length = rand(7,12);
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
		return $string;
	}

	set_time_limit(480);
	$mails=SQL::query('SELECT ID, Auteur, Mail, Hash
	FROM OMNI_Auteurs
	WHERE DernierMail <>"2020-01-01"
	');

	Typo::setTexte(stripslashes($_POST['mail']));
	$Patron = Typo::Parse() . '<hr />
	<ul>
		<li><a href="/membres/Redaction?membre=%Hash%">Rédiger un article</a></li>
		<li><a href="/membres/Propositions?membre=%Hash%">Propositions d\'article</a></li>
	</ul>';

	$NbMailsEnvoyes = 0;

	while($mail = mysql_fetch_assoc($mails))
	{
		$Mdp = getRandomString();
		SQL::update('OMNI_Auteurs',$mail['ID'],array('Pass'=>sha1($Mdp)));
		$mail['Pass'] = $Mdp;

		$Message = str_replace(array_map('wrap',array_keys($mail)),array_values($mail),$Patron);

		External::mail($mail['Mail'],$_POST['sujet'],$Message);
		$NbMailsEnvoyes++;

		//Le mode test n'envoie qu'au premier membre.
		if(isset($_POST['test']))
			break;
	}
	$C['Message'] = 'Envoi terminé ! (' . $NbMailsEnvoyes . ' mails envoyés)';
}

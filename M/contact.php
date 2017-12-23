<?php
/**
* Modèle : contact
* But : Permettre le contact des admins.
* Données à charger : aucune, peut envoyer des mails si $_POST existe.
*
*/

$C['PageTitle']='Formulaire de contact';
$C['CanonicalURL']='/Contact';


if(isset($_POST['captcha']) && isset($_POST['mail']) && isset($_POST['message']))
{
	if(!preg_match('`^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$`i',$_POST['mail']))
		$C['Message'] = 'Adresse email invalide.';
	elseif(empty($_POST['message']))
		$C['Message'] = 'Message vide.';
	elseif($_POST['captcha']!=2)
		$C['Message'] = 'Robots, beware ! Remplissez la question de sécurité.';
	else
	{
		if(strpos($_POST['titre'], 'Remarque sur : /O/') !== false)
		{
			preg_match('`/O/(.+)$`', $_POST['titre'], $Titre);
			$_POST['message'] .= '</p><p><a href="http://omnilogie.fr' . $Titre[0] . '">Accéder à l\'article ' . str_replace('_', ' ', $Titre[1]) . '</a>';
		}

		External::mail('admin@omnilogie.fr','[Contact] ' . stripslashes($_POST['titre']),'<p>Expéditeur : ' . $_POST['mail'] . '</p>' . '<p>' . nl2br(stripslashes($_POST['message'])) . '</p>',$_POST['mail']);
		$C['Message'] = 'Message envoyé';
		$C['MessageClass'] = 'info';
	}
}
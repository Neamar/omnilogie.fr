<?php
/**
* Mod�le : membres/Connexion
* But : offir un formulaire de connexion au membre.
* Donn�es � charger : Rien.
*
*/

$C['PageTitle']='Connexion � Omnilogie';
$C['CanonicalURL']='/membres/Connexion';

if(defined('AUTHOR'))
{
	unset($C['SpecialPods']);
	$_SESSION['Membre']=array();
	$C['Message'] = 'Vous avez �t� d�connect� de votre identit� ' . AUTHOR . '.';
	$C['MessageClass'] = 'info';
}

if(isset($_SESSION['PostData']))
	$C['Message'] = 'Vous avez �t� d�connect� pour inactivit�, merci de vous r�identifier pour valider l\'enregistrement de ces donn�es.';
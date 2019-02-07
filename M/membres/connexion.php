<?php
/**
* Modèle : membres/Connexion
* But : offir un formulaire de connexion au membre.
* Données à charger : Rien.
*
*/

$C['PageTitle']='Connexion à Omnilogie';
$C['CanonicalURL']='/membres/Connexion';

if(defined('AUTHOR'))
{
	unset($C['SpecialPods']);
	$_SESSION['Membre']=array();
	$C['Message'] = 'Vous avez été déconnecté de votre identité ' . AUTHOR . '.';
	$C['MessageClass'] = 'info';
}

if(isset($_SESSION['PostData']))
	$C['Message'] = 'Vous avez été déconnecté pour inactivité, merci de vous réidentifier pour valider l\'enregistrement de ces données.';
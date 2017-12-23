<?php
/**
* Modèle : membres/Inscription
* But : offir un formulaire de connexion au membre.
* Données à charger : Rien.
*
*/

$C['PageTitle']='Inscription à Omnilogie';
$C['CanonicalURL']='/membres/Inscription';

if(isset($_SESSION['Membres']['ID']))
	$C['Message'] = 'Vous êtes déjà connecté ! <a href="/membres/">Cliquez ici pour accéder à votre espace membre</a>.';
<?php
/**
* Mod�le : membres/Inscription
* But : offir un formulaire de connexion au membre.
* Donn�es � charger : Rien.
*
*/

$C['PageTitle']='Inscription � Omnilogie';
$C['CanonicalURL']='/membres/Inscription';

if(isset($_SESSION['Membres']['ID']))
	$C['Message'] = 'Vous �tes d�j� connect� ! <a href="/membres/">Cliquez ici pour acc�der � votre espace membre</a>.';
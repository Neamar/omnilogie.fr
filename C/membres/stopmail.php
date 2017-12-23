<?php
/**
* Contrôleur : membres/stopmail.php
* But : désactiver l'envoi de mail
*/

//////////////////////////////////////////////////////
//Fonctionnalités du contrôleur :

SQL::update('OMNI_Auteurs',AUTHOR_ID,array('DernierMail'=>'2020-01-01'));

$_SESSION['FutureMessage'] = 'Vous ne recevrez plus de mails de notre part.';
$_SESSION['FutureMessageClass'] = 'info';
Debug::redirect('/membres/');
<?php
/**
* Contr�leur : membres/stopmail.php
* But : d�sactiver l'envoi de mail
*/

//////////////////////////////////////////////////////
//Fonctionnalit�s du contr�leur :

SQL::update('OMNI_Auteurs',AUTHOR_ID,array('DernierMail'=>'2020-01-01'));

$_SESSION['FutureMessage'] = 'Vous ne recevrez plus de mails de notre part.';
$_SESSION['FutureMessageClass'] = 'info';
Debug::redirect('/membres/');
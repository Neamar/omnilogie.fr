<?php
/**
* Contr�leur : admin/passerPour.php
* But :d�connecter l'admin et le reconnecter comme le membre choisi.
*/

//////////////////////////////////////////////////////
//Fonctionnalit�s du contr�leur :
$Membre = SQL::singleQuery('SELECT Hash FROM OMNI_Auteurs WHERE Auteur="' . $_GET['Auteur'] . '"');

Debug::redirect('/membres/?membre=' . $Membre['Hash']);
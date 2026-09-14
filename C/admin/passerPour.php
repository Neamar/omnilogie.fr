<?php
/**
* Contrôleur : admin/passerPour.php
* But :déconnecter l'admin et le reconnecter comme le membre choisi.
*/

//////////////////////////////////////////////////////
//Fonctionnalités du contrôleur :
$Membre = SQL::singleQuery('SELECT Hash FROM OMNI_Auteurs WHERE Auteur="' . mysql_real_escape_string($_GET['Auteur']) . '"');

Debug::redirect('/membres/?membre=' . $Membre['Hash']);
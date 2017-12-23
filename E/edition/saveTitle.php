<?php
/**
* Fichier d'évènement
* Event::CORRECTION
*
* Enregistre le nouveau titre de l'article pour pouvoir assurer des redirections dans le futur, si quelqu'un a conservé l'adresse avec l'ancien titre.
*/

SQL::insert('OMNI_Redirection',array('Histoire'=>addslashes($Article->Titre),'ID'=>$Article->ID));
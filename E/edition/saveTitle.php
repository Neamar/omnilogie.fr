<?php
/**
* Fichier d'�v�nement
* Event::CORRECTION
*
* Enregistre le nouveau titre de l'article pour pouvoir assurer des redirections dans le futur, si quelqu'un a conserv� l'adresse avec l'ancien titre.
*/

SQL::insert('OMNI_Redirection',array('Histoire'=>addslashes($Article->Titre),'ID'=>$Article->ID));
<?php
/**
* Contrôleur : index
* But : gérer la mise en cache
*/

// Pas besoin de cache, on affiche un article différent tout le temps.

// if(Cache::page('index'))
// {
// 	//Si la mise en cache est active, on tient quand même à jour le compteur :
// 	SQL::update('OMNI_Omnilogismes',-1,array('_NbVues'=>'NbVues+1'),'OR Sortie=CURDATE()');
// }

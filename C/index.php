<?php
/**
* Contr�leur : index
* But : g�rer la mise en cache
*/

// Pas besoin de cache, on affiche un article diff�rent tout le temps.

// if(Cache::page('index'))
// {
// 	//Si la mise en cache est active, on tient quand m�me � jour le compteur :
// 	SQL::update('OMNI_Omnilogismes',-1,array('_NbVues'=>'NbVues+1'),'OR Sortie=CURDATE()');
// }

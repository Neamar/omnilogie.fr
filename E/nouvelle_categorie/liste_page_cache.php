<?php
/**
* Fichier d'�v�nement
* Event::NOUVELLE_CATEGORIE
*
* @standalone
* @access taggers
* Mettre � jour la page affichant l'arbre des cat�gories
*/
if(Cache::exists('Page', 'liste'))
	Cache::remove('Page','liste');
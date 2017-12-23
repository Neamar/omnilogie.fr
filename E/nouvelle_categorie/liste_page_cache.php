<?php
/**
* Fichier d'évènement
* Event::NOUVELLE_CATEGORIE
*
* @standalone
* @access taggers
* Mettre à jour la page affichant l'arbre des catégories
*/
if(Cache::exists('Page', 'liste'))
	Cache::remove('Page','liste');
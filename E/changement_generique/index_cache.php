<?php
/**
* Fichier d'évènement
* Event::CHANGEMENT_GENERIQUE
*
* Mettre à jour la page d'accueil si on modifie un article paru récemment.
*/
if(is_numeric($Article->Timestamp) && $Article->Timestamp > time() - 4 * 24 * 3600)
{
	if(Cache::exists('Page','index'))
		Cache::remove('Page','index');
}
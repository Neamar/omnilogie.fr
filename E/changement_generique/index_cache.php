<?php
/**
* Fichier d'�v�nement
* Event::CHANGEMENT_GENERIQUE
*
* Mettre � jour la page d'accueil si on modifie un article paru r�cemment.
*/
if(is_numeric($Article->Timestamp) && $Article->Timestamp > time() - 4 * 24 * 3600)
{
	if(Cache::exists('Page','index'))
		Cache::remove('Page','index');
}
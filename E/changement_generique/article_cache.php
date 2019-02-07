<?php
/**
* Fichier d'évènement
* Event::CHANGEMENT_GENERIQUE
*
* Supprimer le cache de l'article et son image
*/

if(Cache::exists('Page','omni-' . $Article->ID))
	Cache::remove('Page','omni-' . $Article->ID);

if(is_file(PATH . '/images/GD/OmniCache/' . $Article->ID . '.png'))
	unlink(PATH . '/images/GD/OmniCache/' . $Article->ID . '.png');
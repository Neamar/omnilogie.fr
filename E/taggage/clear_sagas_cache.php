<?php
/**
* Fichier d'évènement
* Event::TAGGAGE
*
* @standalone
* @access taggers
* Supprimer les caches de tous les pods "sagas"
*/
Cache::removeNamespace('Sagas');
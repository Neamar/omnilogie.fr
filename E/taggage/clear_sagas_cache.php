<?php
/**
* Fichier d'�v�nement
* Event::TAGGAGE
*
* @standalone
* @access taggers
* Supprimer les caches de tous les pods "sagas"
*/
Cache::removeNamespace('Sagas');
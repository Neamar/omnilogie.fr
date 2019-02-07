<?php
/**
* Fichier d'évènement
* Event::PARUTION
*
* @standalone
* Supprime les historiques de version ayant plus de 30 jours
*/
SQL::query('UPDATE OMNI_Modifs SET Sauvegarde = NULL WHERE !ISNULL(Sauvegarde) AND Date < DATE_SUB(NOW(), INTERVAL 30 DAY)');
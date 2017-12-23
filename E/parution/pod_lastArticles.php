<?php
/**
* Fichier d'�v�nement
* Event::PARUTION
*
* @standalone
* Mettre � jour le pod "Derniers articles parus"
*/

$DerniersParams=Omni::buildParam();
$DerniersParams->Where = '!ISNULL(Sortie)';
$DerniersParams->Order = 'Sortie DESC';
$DerniersParams->Limit = '5';

Cache::set('Pods','lastArticles',Formatting::makeList(Omni::getTrailers($DerniersParams)));

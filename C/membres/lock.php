<?php
/**
* Contrôleur : membres/lock.php
* But : Récupérer un verrou en écriture sur la ressource désignée
*/

//////////////////////////////////////////////////////
//Fonctionnalités du contrôleur :

$TitreOmni = Encoding::decodeFromGet('Titre');

//L'article existe-t-il ?
$Param = Omni::buildParam(Omni::TRAILER_PARAM);

$Param->Where = 'Omnilogismes.Titre="' . $TitreOmni . '" OR Omnilogismes.Titre="' . $TitreOmni . '?"';

$Article = Omni::get($Param);

if(count($Article)==0)
	exit();

//Sinon, l'article existe. L'enregistrer, puis passer au modèle.
$Article = $Article[0];
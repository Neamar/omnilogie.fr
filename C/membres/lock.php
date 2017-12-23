<?php
/**
* Contr�leur : membres/lock.php
* But : R�cup�rer un verrou en �criture sur la ressource d�sign�e
*/

//////////////////////////////////////////////////////
//Fonctionnalit�s du contr�leur :

$TitreOmni = Encoding::decodeFromGet('Titre');

//L'article existe-t-il ?
$Param = Omni::buildParam(Omni::TRAILER_PARAM);

$Param->Where = 'Omnilogismes.Titre="' . $TitreOmni . '" OR Omnilogismes.Titre="' . $TitreOmni . '?"';

$Article = Omni::get($Param);

if(count($Article)==0)
	exit();

//Sinon, l'article existe. L'enregistrer, puis passer au mod�le.
$Article = $Article[0];
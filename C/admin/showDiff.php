<?php
/**
* Contrôleur : admin/showDiff
* But :comparer les différences entre deux versions d'un article
*/

//////////////////////////////////////////////////////
//Fonctionnalités du contrôleur :
$ComparaisonID = intval(Encoding::decodeFromGet('Diff'));

//Vérifier que l'article existe :
$Param = Omni::buildParam(OMNI_FULL_PARAM);

$Param->Where = 'Omnilogismes.ID=(SELECT Reference FROM OMNI_Modifs WHERE ID=' . $ComparaisonID . ')';

$Article = Omni::getSingle($Param);
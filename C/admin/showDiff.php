<?php
/**
* Contr�leur : admin/showDiff
* But :comparer les diff�rences entre deux versions d'un article
*/

//////////////////////////////////////////////////////
//Fonctionnalit�s du contr�leur :
$ComparaisonID = intval(Encoding::decodeFromGet('Diff'));

//V�rifier que l'article existe :
$Param = Omni::buildParam(OMNI_FULL_PARAM);

$Param->Where = 'Omnilogismes.ID=(SELECT Reference FROM OMNI_Modifs WHERE ID=' . $ComparaisonID . ')';

$Article = Omni::getSingle($Param);
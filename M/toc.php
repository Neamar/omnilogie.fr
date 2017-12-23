<?php
/**
* Mod�le : toc
* But : G�n�rer la liste des articles existants
* Donn�es � charger :
* - Tous les articles !
*/

$C['PageTitle'] = 'Liste des articles parus sur Omnilogie.fr';
$C['CanonicalURL'] = '/TOC';

$Param = Omni::buildParam(OMNI_TRAILER_PARAM);
$Param->Where = 'Statut="ACCEPTE" AND !ISNULL(Sortie)';
$Param->Limit =null;
$Param->Order = 'REPLACE(REPLACE(REPLACE(REPLACE(Omnilogismes.Titre,"Le ",""),"La ",""),"Les ",""),"L\'","")';

$C['Articles'] = Omni::getTrailers($Param);
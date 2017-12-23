<?php
/**
* Mod�le : flux
* But : G�n�rer le flux XML du site
* Donn�es � charger :
* - Derniers articles
* Sp�cial : la vue est directement appel�e par le mod�le, afin de ne pas charger le template
*/

//R�cup�rer les articles
$Param = Omni::buildParam(OMNI_HUGE_PARAM);

$Param->Where = 'Omnilogismes.Statut="ACCEPTE" AND !ISNULL(Omnilogismes.Sortie)';
$Param->Order = 'Omnilogismes.Sortie DESC';
$Param->Limit = 10;

$C['Articles'] = Omni::get($Param);

$Remplacements = array(
'<img src="/'=>'<img src="' . URL . '/',
'<a href="/'=>'<a href="' . URL . '/',
);

$C['Cherche'] = array_keys($Remplacements);
$C['Remplace'] = array_values($Remplacements);

include(PATH . '/V/flux.php');
exit();
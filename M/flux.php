<?php
/**
* Modèle : flux
* But : Générer le flux XML du site
* Données à charger :
* - Derniers articles
* Spécial : la vue est directement appelée par le modèle, afin de ne pas charger le template
*/

//Récupérer les articles
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
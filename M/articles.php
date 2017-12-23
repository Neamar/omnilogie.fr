<?php
/**
* Mod�le : Articles
* But : Afficher la liste des articles
* Donn�es � charger : tous les articles, sous forme de teaser.
* Liste des statuts en specialPods
*/
//Articles
$C['PageActuelle'] = (isset($_GET['Page'])?' : page ' . intval($_GET['Page']):'');

$C['PageTitle'] = 'Liste des articles' . $C['PageActuelle'];

$C['CanonicalURL'] = '/O/'  . (isset($_GET['Page'])?'Page-' . $_GET['Page']:'');


//R�cup�rer tous les articles parus.
//Utiliser le syst�me de pagination
$Param = Omni::buildParam(OMNI_SMALL_PARAM);

$Param->Where = '
!ISNULL(Sortie)
AND Statut="ACCEPTE"';

$Param->Order = 'Omnilogismes.Sortie DESC';

Formatting::makePage($Param,'/O/');





//Special pods : liste des statuts
if(Cache::exists('Pods', 'status-nb')) {
  prependPod('status-nb','Statuts',Cache::get('Pods','status-nb'));
}

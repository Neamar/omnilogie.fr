<?php
/**
* Fichier d'évènement générique
*
* @standalone
* Mettre à jour le pod "Statuts" listant les articles par statuts
*/

//Special pods : liste des statuts
$Status=array(
	'ACCEPTE'=>'accepté%s',
	'BROUILLON'=>' en gestation',
	'INDETERMINE'=>' à valider',
	'A_CORRIGER'=>' à corriger',
	'REVOIR_FOND'=>' dont le fond est à revoir',
	'REVOIR_FORME'=>' dont la forme est à revoir',
	'REFUSE'=>' refusé%s',
	'DEJA_TRAITE'=>' sur un sujet déjà abordé',
	'MYSTIQUE'=>' "fermeture mystique"',
	'EST_CORRIGE'=>' en validation imminente',
	);
$Nbs = SQL::query('SELECT Statut, COUNT(*) AS Nb
FROM OMNI_Omnilogismes
GROUP BY Statut
ORDER BY Nb DESC');

$Tableau=array();
while($Nb=mysql_fetch_assoc($Nbs))
	$Tableau[] = $Nb['Nb'] . ' article' . ($Nb['Nb']==1?'':'s') . ' ' . str_replace('%s',($Nb['Nb']==1?'':'s'),$Status[$Nb['Statut']]);

Cache::set('Pods','status-nb',Formatting::makeList($Tableau));
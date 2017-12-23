<?php
/**
* Mod�le : Authors
* But : Afficher la liste des auteurs
* Donn�es � charger : Les auteurs avec le nombre d'articles �crits
* Les derniers auteurs inscrits ayant particip�
*/
//Categories

$C['PageTitle'] = 'Liste des auteurs';
$C['CanonicalURL'] = '/Omnilogistes/';

$C['Roles'] = Member::$Roles;

//R�cuperer la liste des auteurs, la traiter pour l'afficher facilement.
//Assez sensible, l'utilisation du WITH ROLLUP SQL n'arrange pas les choses.
$Authors=SQL::query('SELECT OMNI_Auteurs.Auteur, COUNT(*) As Nombre, Statut
	FROM OMNI_Omnilogismes
	LEFT JOIN OMNI_Auteurs ON (OMNI_Auteurs.ID=OMNI_Omnilogismes.Auteur)
	WHERE !ISNULL(OMNI_Auteurs.Auteur)
	GROUP BY OMNI_Auteurs.Auteur,Statut
	WITH ROLLUP');

$C['AuthorList']=array();

$Status=array(
	'ACCEPTE'=>'accept�%s',
	'BROUILLON'=>' en gestation%s',
	'INDETERMINE'=>' � valider',
	'A_CORRIGER'=>' � corriger',
	'REVOIR_FOND'=>' dont le fond est � revoir',
	'REVOIR_FORME'=>' dont la forme est � revoir',
	'REFUSE'=>' refus�%s',
	'DEJA_TRAITE'=>' sur un sujet d�j� abord�',
	'MYSTIQUE'=>' fermeture mystique',
	'EST_CORRIGE'=>' en validation imminente',
	);

$Current=array();
while($Author=mysql_fetch_assoc($Authors))
{
	if(!is_null($Author['Statut']))
		$Current[] = $Author['Nombre'] . ' ' . str_replace('%s',($Author['Nombre']==1?'':'s'),$Status[$Author['Statut']]);
	elseif(!is_null($Author['Auteur']))
	{
		//On atteint un rollup.
		$C['AuthorList'][] = array(
			'Auteur'=>Anchor::author($Author['Auteur']),
			'Total'=>$Author['Nombre'],
			'Details'=>$Current,
		);
		$Current = array();
	}
	else
		$Total=$Author['Nombre'];//Le dernier rollup
}

//Derniers auteurs inscrits (et qui ont �crit)

//On pourrait utiliser SqlParam, mais on ne r�cup�re pas vraiment des auteurs. On fait donc la requ�te directement.
$LastAuthor = SQL::query('SELECT DISTINCT(OMNI_Auteurs.Auteur) AS Auteur
FROM OMNI_Omnilogismes
LEFT JOIN OMNI_Auteurs ON (OMNI_Auteurs.ID=OMNI_Omnilogismes.Auteur)
ORDER BY OMNI_Omnilogismes.Auteur DESC
LIMIT 5');

$List=array();
while($Author = mysql_fetch_assoc($LastAuthor))
	$List[] = Anchor::author($Author['Auteur']);

prependPod('last-writer','Derniers auteurs inscrits',Formatting::makeList($List));
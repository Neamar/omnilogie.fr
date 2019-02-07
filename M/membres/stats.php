<?php
/**
* Modèle : membres/Stats
* But : Afficher des statistiques sur l'article
* Données à charger : Rien.
*
*/

$C['PageTitle']='Statistiques de ' . AUTHOR;
$C['CanonicalURL']='/membres/Stats';

if(defined('NOOB_MODE') && !isset($C['Message']))
	$C['Message'] = 'Vous trouverez ici des statistiques intéressantes... dès que vous aurez rédigé votre premier article :)';
else
{
	//Liste des articles avec nombre de vues
	$Param = Omni::buildParam(OMNI_TRAILER_PARAM);
	$Param->Select = $Param->Select . ', NbVues';
	$Param->Where = 'Auteur = ' . AUTHOR_ID;
	$Param->Order = 'NbVues DESC';

	$C['StatsArticles']=array();

	$Articles=Omni::get($Param);
	$C['StatsTotal'] = 0;
	foreach($Articles as &$Article)
	{
		$C['StatsArticles'][] = $Article->outputTrailer() . ' <small>(<strong>' . Formatting::makeNumber($Article->NbVues) . '</strong> vues)</small>';
		$C['StatsTotal'] += $Article->NbVues;
	}
	$C['StatsTotal'] = Formatting::makeNumber($C['StatsTotal']);
	$C['StatsAuteur'] = Stats::It(
		'SELECT COUNT(*) AS Somme, COUNT(DISTINCT Statut) AS Statuts, SUM(NbVues)/COUNT(*) AS Moyenne
		FROM OMNI_Omnilogismes
		WHERE Auteur=' . AUTHOR_ID,
		'Vous avez écrit $Somme articles sur le site, actuellement répartis dans $Statuts statut(s).<br />
		En moyenne, chacun de vos articles a été vu $Moyenne fois.');

	$C['StatsAuteur2'] = Stats::It(
		'SELECT COUNT(*) AS NbAction, DATE_FORMAT(MIN(Date), "%d/%m/%Y à %T") AS Min, DATE_FORMAT(MAX(Date), "%d/%m/%Y à %T") AS Max
		FROM OMNI_Modifs
		WHERE Auteur=' . AUTHOR_ID,
		'Vous avez effectué $NbAction actions sur le site.<br />
		La première action remonte au $Min, et la dernière action était le $Max.');

	$C['HeureModif'] = Stats::GraphIt(
		'SELECT CONCAT(HOUR(Date),"h") As Abscisse,COUNT(*) AS Ordonnee
		FROM OMNI_Modifs
		WHERE Auteur=' . AUTHOR_ID . '
		GROUP BY HOUR(Date)
		ORDER BY HOUR(Date)',
		array('cht'=>'bvs','chtt'=>'Modifications par heure','chxt'=>'x,y','chxl'=>'0:|$ABSCISSES|1:|0|$MAX','chbh'=>'a'),
		STATS_NO_OTHER_COLUMN);

	$C['TypeModif'] = Stats::GraphIt(
		'SELECT Modification AS Abscisse,COUNT(*) AS Ordonnee
		FROM OMNI_Modifs
		WHERE Auteur=' . AUTHOR_ID . '
		GROUP BY OMNI_Modifs.Modification
		ORDER BY COUNT(*) DESC
		LIMIT 12',
		array('cht'=>'p3','chs'=>'800x200','chtt'=>'Actions','chco'=>'FF0000,00FF00,88AAD6','chl'=>'$ABSCISSES'));

	$C['JourModif'] = Stats::GraphIt(
		'SELECT Calendrier AS Abscisse, COALESCE(Somme,0) AS Ordonnee
		FROM (SELECT DISTINCT(DATE(Date)) AS Calendrier FROM OMNI_Modifs) Toutes_Dates
		LEFT JOIN (SELECT DATE(Date) AS Jour, COUNT(*) As Somme
		FROM OMNI_Modifs
		WHERE Auteur = ' . AUTHOR_ID . '
		GROUP BY DATE(Date)) Auteur_Dates ON (Jour = Calendrier)
		WHERE Calendrier >= (SELECT MIN(Date) FROM OMNI_Modifs WHERE Auteur=' . AUTHOR_ID . ')
		ORDER BY Calendrier',
		array('chtt'=>'Modifications par jour','chxt'=>'y','chxr'=>'0,0,$MAX'),STATS_NO_OTHER_COLUMN);
		
	$Param = Omni::buildParam(OMNI_TRAILER_PARAM);
	$Param->Select = $Param->Select . ', NbVues / DATEDIFF(NOW(), Sortie) AS PerDay';
	$Param->Where = 'Auteur = ' . AUTHOR_ID . ' AND Sortie IS NOT NULL';
	$Param->Order = 'PerDay DESC';

	$C['StatsArticlesPerDay']=array();

	$Articles=Omni::get($Param);
	foreach($Articles as &$Article)
	{
		$C['StatsArticlesPerDay'][] = $Article->outputTrailer() . ' <small>(<strong>' . Formatting::makeNumber($Article->PerDay) . '</strong> vues par jour)</small>';
	}
}
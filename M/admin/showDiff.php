<?php
/**
* Modèle : admin/showDiff
* But : afficher les différences entre deux versions d'un article
* Pré-requis : $Article chargé par le contrôleur
*/

$C['PageTitle']='Logs';
$C['CanonicalURL']='/admin/Logs/';

/*
* MODIFICATION DES PODS.
* Cette page ajoute plusieurs pods, et en supprime certains pour gagner de la place.
*/
//Virer certains pods pour gagner de la place :
unset($C['Pods']['author-stats'],$C['Pods']['modifiable'],$C['Pods']['publiable']);

//Affichage des dernières actions
$C['Pods']['lastactions']['Content']=Formatting::makeList(Event::getLast(150,'Reference=' . $Article->ID,'%DATE% %MODIF% par %AUTEUR% %DIFF%'));

//Récupérer la version à comparer
$Origine=mysql_fetch_assoc(mysql_query('SELECT Reference,Sauvegarde,Date, OMNI_Auteurs.Auteur
FROM OMNI_Modifs
LEFT JOIN OMNI_Auteurs ON (OMNI_Modifs.Auteur=OMNI_Auteurs.ID)
WHERE OMNI_Modifs.ID=' . $ComparaisonID . ' AND !ISNULL(Sauvegarde)'));

//Récupérer les modifs plus récentes :
$Nouveaux=SQL::query('SELECT OMNI_Modifs.ID, Date,Sauvegarde, OMNI_Auteurs.Auteur
FROM OMNI_Modifs
LEFT JOIN OMNI_Auteurs ON (OMNI_Modifs.Auteur=OMNI_Auteurs.ID)
WHERE OMNI_Modifs.ID>' . $ComparaisonID . ' AND !ISNULL(Sauvegarde) AND Reference=' . $Article->ID . '
ORDER BY OMNI_Modifs.ID DESC');

if(mysql_num_rows($Nouveaux)==0)
{
	$Origine=mysql_fetch_assoc(mysql_query('SELECT Reference,Sauvegarde,Date, OMNI_Auteurs.Auteur
	FROM OMNI_Modifs
	LEFT JOIN OMNI_Auteurs ON (OMNI_Modifs.Auteur=OMNI_Auteurs.ID)
	WHERE !ISNULL(Sauvegarde) AND Reference=' . $Article->ID . '
	ORDER BY OMNI_Modifs.ID DESC
	LIMIT 1,1'));

	//Récupérer les modifs plus récentes :
	$Nouveaux=SQL::query('SELECT OMNI_Modifs.ID, Date,Sauvegarde, OMNI_Auteurs.Auteur
	FROM OMNI_Modifs
	LEFT JOIN OMNI_Auteurs ON (OMNI_Modifs.Auteur=OMNI_Auteurs.ID)
	WHERE !ISNULL(Sauvegarde) AND Reference=' . $Article->ID . '
	ORDER BY OMNI_Modifs.ID DESC
	LIMIT 1');
	$C['Message']='Comparaison de la dernière version avec la révision précédente.';
	$C['MessageClass'] = 'info';
}

/*
        Paul's Simple Diff Algorithm v 0.1
        (C) Paul Butler 2007 <http://www.paulbutler.org/>
        May be used and distributed under the zlib/libpng license.

        This code is intended for learning purposes; it was written with short
        code taking priority over performance. It could be used in a practical
        application, but there are a few ways it could be optimized.

        Given two arrays, the function diff will return an array of the changes.
        I won't describe the format of the array, but it will be obvious
        if you use print_r() on the result of a diff on some test data.

        htmlDiff is a wrapper for the diff command, it takes two strings and
        returns the differences in HTML. The tags used are <ins> and <del>,
        which can easily be styled with CSS.
*/

function diff(array $old, array $new)
{
	$maxlen=0;
	foreach($old as $oindex => $ovalue)
	{
			$nkeys = array_keys($new, $ovalue);
			foreach($nkeys as $nindex){
					$matrix[$oindex][$nindex] = isset($matrix[$oindex - 1][$nindex - 1]) ?
							$matrix[$oindex - 1][$nindex - 1] + 1 : 1;
					if($matrix[$oindex][$nindex] > $maxlen)
					{
							$maxlen = $matrix[$oindex][$nindex];
							$omax = $oindex + 1 - $maxlen;
							$nmax = $nindex + 1 - $maxlen;
					}
			}
	}
	if($maxlen == 0) return array(array('d'=>$old, 'i'=>$new));
	return array_merge(
			diff(array_slice($old, 0, $omax), array_slice($new, 0, $nmax)),
			array_slice($new, $nmax, $maxlen),
			diff(array_slice($old, $omax + $maxlen), array_slice($new, $nmax + $maxlen)));
}

function htmlDiff($old, $new)
{
	$Search=array('','[',']','{','}',',','.');
	$Replace=array('\'','[ ',' ]','{ ',' }',' ,',' .');
	$old=str_replace($Search,$Replace,$old);
	$new=str_replace($Search,$Replace,$new);

	$diff = diff(explode(' ', $old), explode(' ', $new));
	$ret='';
	foreach($diff as $k){
			if(is_array($k))
					$ret .= (!empty($k['d'])?"<del>".implode(' ',$k['d'])."</del> ":'').
							(!empty($k['i'])?"<ins>".implode(' ',$k['i'])."</ins> ":'');
			else $ret .= $k . ' ';
	}
	return $ret;
}

$C['Sections'] = array();
$C['Original'] = $Origine;
$C['Article'] = $Article;
$C['TOC']=array();
$isFirst=true;//Le premier passage est spécial.
while($Nouveau=mysql_fetch_assoc($Nouveaux))
{
	$C['TOC'][] = '<a href="#log-' . $Nouveau['ID'] . '"><small>' . $Nouveau['Date'] . '</small> par ' . $Nouveau['Auteur'] . '</a>';

	$C['Sections'][]=array(
		'ID'=>$Nouveau['ID'],
		'Date'=>$Nouveau['Date'],
		'Auteur'=>$Nouveau['Auteur'],
		'Header'=>(!$isFirst?'<p class="petitTexte"><a href="/admin/Diff/' . $Nouveau['ID'] . '">Comparer à partir de cette version.</a></p>':'<p class="important centre">Ceci est la version actuelle.</p>'),
		'Content'=>($Origine['Sauvegarde']==$Nouveau['Sauvegarde']?'<p style="text-align:left;">Il n\'y a aucune modification avec l\'original.</p>':'<p style="text-align:left;"><tt>' . nl2br(htmlDiff($Origine['Sauvegarde'],$Nouveau['Sauvegarde'])) . '</tt></p>'),
	);

	$isFirst=false;
}
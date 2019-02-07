<?php
/**
* Modèle : timeline
* But : Générer les données nécessaire à l'affichage de la frise chronologique
* Données à charger :
* - Articles contenant une date numérique
* - Reverse index de ces articles
*/

$C['PageTitle'] = 'La grande Histoire racontée par les petites histoires';
$C['CanonicalURL'] = '/Timeline';

//Ãtape 1 : récupérer toutes les dates
$Param = Omni::buildParam(Omni::SMALL_PARAM);

$Param->Where = '!ISNULL(Sortie) AND Statut="ACCEPTE" AND Omnilogisme REGEXP "([^0-9.]|^)(20[0-9]{2}|1[0-9]{3}|\\\\c(entury)?{-?[XVI]{1,3}}|\\\\date{-?[0-9]{0,4}})([^0-9]|$)"';

$Articles = Omni::get($Param);

$Siecles=array('I','II','III','IV','V','VI','VII','VIII','IX','X','XI','XII','XIII','XIV','XV','XVI','XVII','XVIII','XIX','XX','XXI');
$Dates=array();

//Fonction spéciale : si un article contient plusieurs fois la mÃªme date, par défaut c'est toujours la mÃªme phrase qui est affichée. Pour pallier à ce défaut, une fois la date traitée, on la remplace par ses équivalents HTML.
function echappe($Date)
{
	$Date=str_split($Date);
	foreach($Date as &$Part)
		$Part='&#' . ord($Part) . ';';
	return implode($Date);
}


//Ãtape 2 : pour toutes les dates, les transformer en chiffres (y compris les century)
foreach($Articles as $Date)
{
	preg_match_all('#(\s|\.|^|\(|-)(20[0-9]{2}|1[0-9]{3}|\\\\c(entury)?{-?[XVI]{1,3}}|\\\\date{-?[0-9]{0,4}})(\s|\.|,|-|\)|$)#',$Date->Omnilogisme,$DatesArticle,PREG_SET_ORDER);
	//Pour chacun des résultats "dates" de l'omnilogisme.
	foreach($DatesArticle as $DateArticle)
	{
		//Trouver la valeur numérique de l'année
		if(is_numeric($DateArticle[2]))
			$Curseur=$DateArticle[2];
		elseif(strpos($DateArticle[2],'date')!==false)
		{
			$Curseur=preg_replace('#\\\\date{(-?[0-9]+)}#','$1',$DateArticle[2]);
		}
		elseif(strpos($DateArticle[2],'c')!==false)
		{
			$Signe=1;//Par défaut, les siècles sont après J.-C.
			$Siecle=strtoupper(preg_replace('#\\\\c(entury)?{(-?[XVI]{1,3})}#','$2',$DateArticle[2]));
			if($Siecle[0]=='-')
			{
				$Signe = -1;//Retenir qu'il s'agit d'un siècle avant J.-C.
				$Siecle=substr($Siecle,1);//Enlever le signe -
			}
			$Curseur=$Signe * array_search($Siecle,$Siecles)*100;
		}
		else
			Debug::fail('Erreur dans la timeline.');

		//Puis l'enregistrer
		if(!isset($Dates[$Curseur]))
			$Dates[$Curseur]=array();

		//Partie pas forcément la plus facile : récupérer la phrase entourante
		if(!preg_match("#(^|\\. |\n)(.*" . preg_quote($DateArticle[2]) . ".*)($|\\. |\n)#U",$Date->Omnilogisme,$Phrase))
			Debug::fail('Impossible de récupérer une phrase cohérente pour la timeline.');
		$Phrase=preg_replace('#\\\\date{(-?[0-9]+)}#','$1',$Phrase);//Rappelons-le, \date est une balise purement sémantique.
		if(is_numeric($DateArticle[2]))
			$ValeurEchapee=echappe($DateArticle[2]);
		else
			$ValeurEchapee=preg_replace('#\{(-?[XVI]{1,3})\}#','{ $1}',$DateArticle[2]);

		//S'il y a plusieurs occurences de la même année, il faut supprimer la phrase actuelle pour pouvoir avoir les suivantes.
		$Date->Omnilogisme=substr_replace($Date->Omnilogisme,$ValeurEchapee,strpos($Date->Omnilogisme,$DateArticle[2]),strlen($DateArticle[2]));

		$Dates[$Curseur][]=array($Phrase[2] . $Phrase[3],$Date);//Ajouter la date dans le tableau correspondant à l'année citée. []
	}
}

ksort($Dates);

$C['Dates'] = $Dates;
$C['Siecles'] = $Siecles;
<?php
/**
* Mod�le : timeline
* But : G�n�rer les donn�es n�cessaire � l'affichage de la frise chronologique
* Donn�es � charger :
* - Articles contenant une date num�rique
* - Reverse index de ces articles
*/

$C['PageTitle'] = 'La grande Histoire racont�e par les petites histoires';
$C['CanonicalURL'] = '/Timeline';

//Étape 1 : r�cup�rer toutes les dates
$Param = Omni::buildParam(Omni::SMALL_PARAM);

$Param->Where = '!ISNULL(Sortie) AND Statut="ACCEPTE" AND Omnilogisme REGEXP "([^0-9.]|^)(20[0-9]{2}|1[0-9]{3}|\\\\c(entury)?{-?[XVI]{1,3}}|\\\\date{-?[0-9]{0,4}})([^0-9]|$)"';

$Articles = Omni::get($Param);

$Siecles=array('I','II','III','IV','V','VI','VII','VIII','IX','X','XI','XII','XIII','XIV','XV','XVI','XVII','XVIII','XIX','XX','XXI');
$Dates=array();

//Fonction sp�ciale : si un article contient plusieurs fois la même date, par d�faut c'est toujours la même phrase qui est affich�e. Pour pallier � ce d�faut, une fois la date trait�e, on la remplace par ses �quivalents HTML.
function echappe($Date)
{
	$Date=str_split($Date);
	foreach($Date as &$Part)
		$Part='&#' . ord($Part) . ';';
	return implode($Date);
}


//Étape 2 : pour toutes les dates, les transformer en chiffres (y compris les century)
foreach($Articles as $Date)
{
	preg_match_all('#(\s|\.|^|\(|-)(20[0-9]{2}|1[0-9]{3}|\\\\c(entury)?{-?[XVI]{1,3}}|\\\\date{-?[0-9]{0,4}})(\s|\.|,|-|\)|$)#',$Date->Omnilogisme,$DatesArticle,PREG_SET_ORDER);
	//Pour chacun des r�sultats "dates" de l'omnilogisme.
	foreach($DatesArticle as $DateArticle)
	{
		//Trouver la valeur num�rique de l'ann�e
		if(is_numeric($DateArticle[2]))
			$Curseur=$DateArticle[2];
		elseif(strpos($DateArticle[2],'date')!==false)
		{
			$Curseur=preg_replace('#\\\\date{(-?[0-9]+)}#','$1',$DateArticle[2]);
		}
		elseif(strpos($DateArticle[2],'c')!==false)
		{
			$Signe=1;//Par d�faut, les si�cles sont apr�s J.-C.
			$Siecle=strtoupper(preg_replace('#\\\\c(entury)?{(-?[XVI]{1,3})}#','$2',$DateArticle[2]));
			if($Siecle[0]=='-')
			{
				$Signe = -1;//Retenir qu'il s'agit d'un si�cle avant J.-C.
				$Siecle=substr($Siecle,1);//Enlever le signe -
			}
			$Curseur=$Signe * array_search($Siecle,$Siecles)*100;
		}
		else
			Debug::fail('Erreur dans la timeline.');

		//Puis l'enregistrer
		if(!isset($Dates[$Curseur]))
			$Dates[$Curseur]=array();

		//Partie pas forc�ment la plus facile : r�cup�rer la phrase entourante
		if(!preg_match("#(^|\\. |\n)(.*" . preg_quote($DateArticle[2]) . ".*)($|\\. |\n)#U",$Date->Omnilogisme,$Phrase))
			Debug::fail('Impossible de r�cup�rer une phrase coh�rente pour la timeline.');
		$Phrase=preg_replace('#\\\\date{(-?[0-9]+)}#','$1',$Phrase);//Rappelons-le, \date est une balise purement s�mantique.
		if(is_numeric($DateArticle[2]))
			$ValeurEchapee=echappe($DateArticle[2]);
		else
			$ValeurEchapee=preg_replace('#\{(-?[XVI]{1,3})\}#','{ $1}',$DateArticle[2]);

		//S'il y a plusieurs occurences de la m�me ann�e, il faut supprimer la phrase actuelle pour pouvoir avoir les suivantes.
		$Date->Omnilogisme=substr_replace($Date->Omnilogisme,$ValeurEchapee,strpos($Date->Omnilogisme,$DateArticle[2]),strlen($DateArticle[2]));

		$Dates[$Curseur][]=array($Phrase[2] . $Phrase[3],$Date);//Ajouter la date dans le tableau correspondant � l'ann�e cit�e. []
	}
}

ksort($Dates);

$C['Dates'] = $Dates;
$C['Siecles'] = $Siecles;
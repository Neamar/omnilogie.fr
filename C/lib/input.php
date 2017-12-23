<?php
/**
* But : g�rer les donn�es en entr�e pour les s�curiser / les nettoyer / les pr�parer
*
*/
//Input

//////////////////////////////////////////////////////
//Fonctionnalit�s du contr�leur :

class Input
{
	private static $Remplacements = array(
		'�'=>"'",
		'�'=>'...',
		'�'=>'oe',
		'�'=>' ',//espace ins�cable
	);

	/**
	* Pr�pare un omnilogisme � son insertion en base de donn�es.
	* @param Data:String les donn�es � nettoyer
	* @return :String les donn�es nettoy�es. Remplit aussi $_POST['keywords'].
	*/
	public static function prepareOmni($Omni)
	{
		$Omni = self::sanitize($Omni);
		$Omni = self::downloadImages($Omni);

		return $Omni;
	}

	/**
	* Supprime les aberrations typographiques qui sont automatiquement faites par le typographe.
	* @param Data:String les donn�es � nettoyer
	* @return :String les donn�es nettoy�es
	*/
	public static function sanitize($Data)
	{
		$Data=str_replace(array_keys(self::$Remplacements),array_values(self::$Remplacements),$Data);

		return $Data;
	}

	/**
	* T�l�charge toutes les images contenues dans $Texte en local
	* @param Texte:String le texte qui contient les images
	* @return :String le texte avec l'url des images distantes remplac�es par la version locale.
	*/
	public static function downloadImages($Texte)
	{
		//T�l�charge les images distantes en local :
		$Externes=array();
		preg_match_all('#\\\\image\\[.+\\]{http://(.+)}#iU',$Texte,$Externes);
		foreach($Externes[1] as $Match)
		{
			$Extension= substr($Match, strrpos($Match,'.'));
			if(in_array(strtolower(substr($Extension,1)),array('png','jpg','jpeg','gif','tiff','svg')))
			{
				$Relatif='/images/O/' . md5(SALT . $Match) . $Extension;
				$Unique_ID=PATH . $Relatif;

				if(!is_file($Unique_ID))
				{
					$DL = curl_init();
					curl_setopt($DL, CURLOPT_URL,urldecode($Match));
					curl_setopt($DL, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($DL, CURLOPT_FOLLOWLOCATION, true);
					curl_setopt($DL, CURLOPT_MAXREDIRS, 5);

					$image = curl_exec($DL);
					if(curl_getinfo($DL,CURLINFO_HTTP_CODE)==200)
					{
						curl_close($DL);
						$handle=fopen($Unique_ID, 'w');
						fputs($handle,$image);
						fclose($handle);
					}
					else
					{
						global $C;
						$Relatif = 'http://' . $Match;
						$C['Message'] = 'Impossible de charger l\'image ' . $Relatif . '. Merci d\'en v�rifier l\'adresse !';
					}

				}
				$Texte=str_replace('http://' . $Match,$Relatif,$Texte);
			}
		}

		return $Texte;
	}

	/**
	* R�cup�rer une liste de mots cl�s � partir de $Texte
	* @param Texte:String le texte dont il faut extraire les mots cl�s
	* @return :void la fonction remplit directement le tableau $_POST['keywords']
	*/
	public static function getKeyWords($Texte)
	{
		//G�n�rer les mots cl�s
		Typo::setTexte(stripslashes($Texte));

		$Raw=strtolower(preg_replace('#&(\S)+;#','',preg_replace('#\<([^\<]+)\>#','',ParseMath(Typo::Parse()))));

		$Words=preg_split('(\s|[-,\'\.��:\(\)\?!;"&])',$Raw);
		array_map('trim',$Words);
		$Freq=array_count_values($Words);//�quivalent du GROUP BY.
		arsort($Freq);//Trier par nombre d'apparition du mot.

		$KeyWords=array();
		foreach($Freq as $Word=>$Nb)
		{
			if(strlen($Word)>4)
			{
				$KeyWords[]=$Word;
				if(count($KeyWords)>20)
					break;
			}
		}
		$_POST['keywords'] = addslashes(implode(', ',$KeyWords));
	}
}
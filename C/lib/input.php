<?php
/**
* But : gérer les données en entrée pour les sécuriser / les nettoyer / les préparer
*
*/
//Input

//////////////////////////////////////////////////////
//Fonctionnalités du contrôleur :

class Input
{
	private static $Remplacements = array(
		''=>"'",
		''=>'...',
		''=>'oe',
		' '=>' ',//espace insécable
	);

	/**
	* Prépare un omnilogisme à son insertion en base de données.
	* @param Data:String les données à nettoyer
	* @return :String les données nettoyées. Remplit aussi $_POST['keywords'].
	*/
	public static function prepareOmni($Omni)
	{
		$Omni = self::sanitize($Omni);
		$Omni = self::downloadImages($Omni);

		return $Omni;
	}

	/**
	* Supprime les aberrations typographiques qui sont automatiquement faites par le typographe.
	* @param Data:String les données à nettoyer
	* @return :String les données nettoyées
	*/
	public static function sanitize($Data)
	{
		$Data=str_replace(array_keys(self::$Remplacements),array_values(self::$Remplacements),$Data);

		return $Data;
	}

	/**
	* Télécharge toutes les images contenues dans $Texte en local
	* @param Texte:String le texte qui contient les images
	* @return :String le texte avec l'url des images distantes remplacées par la version locale.
	*/
	public static function downloadImages($Texte)
	{
		//Télécharge les images distantes en local :
		$Externes=array();
		preg_match_all('#\\\\(?:label)?image\\[.+\\]{(https?://.+)}#iU',$Texte,$Externes);
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
					curl_setopt($DL, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; Linux x86_64; rv:89.0) Gecko/20100101 Firefox/89.0');

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
						$Relatif = $Match;
						$C['Message'] = 'Impossible de charger l\'image ' . $Relatif . '. Merci d\'en vérifier l\'adresse !';
					}

				}
				$Texte=str_replace($Match,$Relatif,$Texte);
			}
		}

		return $Texte;
	}

	/**
	* Récupérer une liste de mots clés à partir de $Texte
	* @param Texte:String le texte dont il faut extraire les mots clés
	* @return :void la fonction remplit directement le tableau $_POST['keywords']
	*/
	public static function getKeyWords($Texte)
	{
		//Générer les mots clés
		Typo::setTexte(stripslashes($Texte));

		$Raw=strtolower(preg_replace('#&(\S)+;#','',preg_replace('#\<([^\<]+)\>#','',ParseMath(Typo::Parse()))));

		$Words=preg_split('(\s|[-,\'\.«»:\(\)\?!;"&])',$Raw);
		array_map('trim',$Words);
		$Freq=array_count_values($Words);//Équivalent du GROUP BY.
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

	/**
	* Renvoie l'adresse IP réelle du client.
	* L'application tourne derrière le nginx de dokku, qui réécrit systématiquement
	* X-Forwarded-For sous la forme "<valeur envoyée par le client>, <IP réelle du peer>".
	* Tout ce qui précède la dernière entrée est donc fourni par le client, et donc
	* trivialement falsifiable : on lit la chaîne de droite à gauche, en sautant nos
	* propres proxys.
	* @return :String l'IP du client, ou REMOTE_ADDR si aucune valeur fiable n'est disponible.
	*/
	public static function getClientIp()
	{
		$Remote = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';

		if(empty($_SERVER['HTTP_X_FORWARDED_FOR']))
			return $Remote;

		//Nombre de proxys de confiance devant l'application : 1 pour le nginx de dokku
		//seul, 2 si un CDN (Cloudflare…) est intercalé devant lui, etc.
		$Hops = (int) getenv('TRUSTED_PROXY_HOPS');
		if($Hops < 1)
			$Hops = 1;

		$Chaine = array_map('trim',explode(',',$_SERVER['HTTP_X_FORWARDED_FOR']));

		//La dernière entrée est celle ajoutée par notre nginx : on remonte de $Hops crans.
		$Index = count($Chaine) - $Hops;
		if($Index < 0)
			return $Remote;

		return filter_var($Chaine[$Index],FILTER_VALIDATE_IP) ? $Chaine[$Index] : $Remote;
	}
}

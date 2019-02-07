<?php
/**
* But : offrir des primitives facilement accessibles pour interagir avec les différents encodages.
*
*/
//Encoding

//////////////////////////////////////////////////////
//Fonctionnalités du contrôleur :

class Encoding
{
	/**
	* Décode la variable $_GET[$key] en prenant en compte l'encodage.
	* @param key:String la clé de l'index
	* @return :String la clé nettoyée.
	*/
	public static function decodeFromGet($key)
	{

		if(!isset($_GET[$key]))
			Debug::fail('Impossible de décoder la clé ' . $key);

		//Le "?" en fin de requête fait bugger l'URL rewriting car ? est un caractère spécial. Il faut l'ajouter manuellement
		if(substr($_SERVER['REQUEST_URI'],-1)=='?')
			$_GET[$key] .='?';

		//Enregistrer. Noter qu'il s'agit d'une variable protégée contre l'injection SQL.
		$Value = Link::unescape(stripslashes($_GET[$key]));

		//Noter aussi que le serveur nous envoie l'URL en UTF8, et qu'on travaille ici en ISO-8859-15.
		//Cependant, le navigateur peut avoir échappé l'URL auquel cas est sera en iso. Bref, il faut tester pour savoir !
		if(self::isUtf8($Value))
			$Value= utf8_decode($Value);

		if($Value[strlen($Value)-1]=='\\')
			$Value = substr($Value,0,strlen($Value)-1);

		$Value = str_replace('"','\\"', $Value);
		return $Value;
	}

	/**
	* Détermine si une chaine est encodée en utf8.
	* @param str:String la chaine à tester
	* @return :bool Renvoie true si $str est encodé en utf8, false sinon.
	*/
	public static function isUtf8($str)
	{
		return ($str === mb_convert_encoding(mb_convert_encoding($str, "UTF-32", "UTF-8"), "UTF-8", "UTF-32"));
	}
}
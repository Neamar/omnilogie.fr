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

		if($Value[strlen($Value)-1]=='\\')
			$Value = substr($Value,0,strlen($Value)-1);

		$Value = str_replace('"','\\"', $Value);
		return $Value;
	}
}

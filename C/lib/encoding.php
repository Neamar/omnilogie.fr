<?php
/**
* But : offrir des primitives facilement accessibles pour interagir avec les diff�rents encodages.
*
*/
//Encoding

//////////////////////////////////////////////////////
//Fonctionnalit�s du contr�leur :

class Encoding
{
	/**
	* D�code la variable $_GET[$key] en prenant en compte l'encodage.
	* @param key:String la cl� de l'index
	* @return :String la cl� nettoy�e.
	*/
	public static function decodeFromGet($key)
	{

		if(!isset($_GET[$key]))
			Debug::fail('Impossible de d�coder la cl� ' . $key);

		//Le "?" en fin de requ�te fait bugger l'URL rewriting car ? est un caract�re sp�cial. Il faut l'ajouter manuellement
		if(substr($_SERVER['REQUEST_URI'],-1)=='?')
			$_GET[$key] .='?';

		//Enregistrer. Noter qu'il s'agit d'une variable prot�g�e contre l'injection SQL.
		$Value = Link::unescape(stripslashes($_GET[$key]));

		//Noter aussi que le serveur nous envoie l'URL en UTF8, et qu'on travaille ici en ISO-8859-15.
		//Cependant, le navigateur peut avoir �chapp� l'URL auquel cas est sera en iso. Bref, il faut tester pour savoir !
		if(self::isUtf8($Value))
			$Value= utf8_decode($Value);

		if($Value[strlen($Value)-1]=='\\')
			$Value = substr($Value,0,strlen($Value)-1);

		$Value = str_replace('"','\\"', $Value);
		return $Value;
	}

	/**
	* D�termine si une chaine est encod�e en utf8.
	* @param str:String la chaine � tester
	* @return :bool Renvoie true si $str est encod� en utf8, false sinon.
	*/
	public static function isUtf8($str)
	{
		return ($str === mb_convert_encoding(mb_convert_encoding($str, "UTF-32", "UTF-8"), "UTF-8", "UTF-32"));
	}
}
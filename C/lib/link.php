<?php
/**
* But : r�cup�rer un lien � partir d'un titre d'article, d'une cat�gorie...
*
*/
//Link

//////////////////////////////////////////////////////
//Fonctionnalit�s du contr�leur :


class Link
{
	private static $Replacements=array(' '=>CESURE,'&'=>'%26');

	/**
	* Renvoie un lien absolu � partir du titre d'un article
	* @param Titre:String le titre de l'article
	* @return :String le lien /O/Titre_modifi�
	*/
	public static function omni($Title,$Prefix='/O/')
	{
		return $Prefix . self::escape($Title);
	}

	/**
	* Renvoie un lien absolu � partir du titre d'un article, vers l'adresse raccourcie d'un article
	* @param ID:int l'identifiant de l'article
	* @return :String le lien /IDenbase35
		*/
	public static function omniShort($ID)
	{
		if($ID > 35)
			return '/' . strtoupper(base_convert($ID,10,35));
		else
			return '/:' . strtoupper(base_convert($ID,10,35));
	}

	/**
	* Renvoie un lien absolu � partir du nom d'un auteur
	* @param Author:String le nom de l'auteur
	* @return :String le lien /Omnilogistes/Auteur_modifi�
	*/
	public static function author($Author)
	{
		return '/Omnilogistes/' . self::escape($Author) . '/';
	}

	/**
	* Renvoie un lien absolu � partir du nom d'une cat�gorie
	* @param Category:String le nom de la cat�gorie
	* @return :String le lien /Liste/Categorie_modifi�e
	*/
	public static function category($Category)
	{
		return '/Liste/' . self::escape($Category) . '/';
	}

	/**
	* R�cup�re la classe CSS associ�e � un statut.
	* @param Status:String le statut
	* @return :String le statut sous forme de classe.
	*/
	public static function status($Status)
	{
		return strtolower($Status);
	}

	/**
	* R�cup�re l'h�te d'un lien � partir d'une adresse web.
	* Exemple : http://neamar.fr/Res => neamar.fr
	* @param URL:String l'adresse � analyser
	* @return :String l'h�te
	*/
	public static function getHost($URL)
	{
		$URL_parts=parse_url($URL);
		if(isset($URL_parts['host']))
			return $URL_parts['host'];
		else
			return '';
	}

	/**
	* �chappe les caract�res sp�ciaux qui pourraient g�rer lors de l'�chappement d'une URL.
	* Ces caract�res sont stock�s dans le tableau $Remplacements
	* @param $Str:String la cha�ne � �chapper
	* @return :String la cha�ne �chapp�e
	*/
	private static function escape($Str)
	{
		return str_replace(array_keys(self::$Replacements),array_values(self::$Replacements),$Str);
	}

	/**
	* D�s�chappe les caract�res sp�ciaux.
	* Fonctionne � l'exact inverse de self::escape()
	*/
	public static function unescape($Str)
	{
		return str_replace(array_values(self::$Replacements),array_keys(self::$Replacements),$Str);
	}
}
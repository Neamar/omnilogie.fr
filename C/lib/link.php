<?php
/**
* But : récupérer un lien à partir d'un titre d'article, d'une catégorie...
*
*/
//Link

//////////////////////////////////////////////////////
//Fonctionnalités du contrôleur :


class Link
{
	private static $Replacements=array(' '=>CESURE,'&'=>'%26');

	/**
	* Renvoie un lien absolu à partir du titre d'un article
	* @param Titre:String le titre de l'article
	* @return :String le lien /O/Titre_modifié
	*/
	public static function omni($Title,$Prefix='/O/')
	{
		return $Prefix . self::escape($Title);
	}

	/**
	* Renvoie un lien absolu à partir du titre d'un article, vers l'adresse raccourcie d'un article
	* @param ID:int l'identifiant de l'article
	* @return :String le lien /IDenbase35
		*/
	public static function omniShort($ID)
	{
		return '/' . strtoupper(base_convert($ID,10,35));
	}

	/**
	* Renvoie un lien absolu à partir du nom d'un auteur
	* @param Author:String le nom de l'auteur
	* @return :String le lien /Omnilogistes/Auteur_modifié
	*/
	public static function author($Author)
	{
		return '/Omnilogistes/' . self::escape($Author) . '/';
	}

	/**
	* Renvoie un lien absolu à partir du nom d'une catégorie
	* @param Category:String le nom de la catégorie
	* @return :String le lien /Liste/Categorie_modifiée
	*/
	public static function category($Category)
	{
		return '/Liste/' . self::escape($Category) . '/';
	}

	/**
	* Récupère la classe CSS associée à un statut.
	* @param Status:String le statut
	* @return :String le statut sous forme de classe.
	*/
	public static function status($Status)
	{
		return strtolower($Status);
	}

	/**
	* Récupère l'hôte d'un lien à partir d'une adresse web.
	* Exemple : http://neamar.fr/Res => neamar.fr
	* @param URL:String l'adresse à analyser
	* @return :String l'hôte
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
	* Échappe les caractères spéciaux qui pourraient gérer lors de l'échappement d'une URL.
	* Ces caractères sont stockés dans le tableau $Remplacements
	* @param $Str:String la chaîne à échapper
	* @return :String la chaîne échappée
	*/
	private static function escape($Str)
	{
		return str_replace(array_keys(self::$Replacements),array_values(self::$Replacements),$Str);
	}

	/**
	* Déséchappe les caractères spéciaux.
	* Fonctionne à l'exact inverse de self::escape()
	*/
	public static function unescape($Str)
	{
		return str_replace(array_values(self::$Replacements),array_keys(self::$Replacements),$Str);
	}
}

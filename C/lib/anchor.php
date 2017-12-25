<?php
/**
* But : Récupérer des liens HTML pour différents éléments.
*
*/
//Anchor

//////////////////////////////////////////////////////
//Fonctionnalités du contrôleur :

class Anchor
{
	private static $Voyelles=array('a','e','i','o','u');

	/**
	* Créer une ancre à partir d'un article
	* @param Omni:Omni un article
	* @param Class:String la classe CSS à appliquer. Si rien n'est spécifié, c'est le statut de l'article qui sert de classe.
	* @return :String un lien HTML
	*/
	public static function omni(Omni $Omni,$Class=null)
	{
		// var_dump($Omni);
		if(is_null($Class))
			$Class = $Omni->Statut;
		if($Omni->Statut!='')
			$Class = ' class="' . Link::status($Omni->Statut) . '"';
		Typo::setTexte($Omni->Titre);
		return '<a href="' . Link::omni($Omni->Titre) . '" title="' . $Omni->Accroche . '"' . $Class . '>' . Typo::parseLinear() . '</a>';
	}

	/**
	* Créer une ancre à partir d'un article vers l'URL raccourcie
	* @param ID:int l'identifiant de l'article
	* @return :String un lien HTML
		*/
	public static function omniShort($ID)
	{
		$Encoding = Link::omniShort($ID);
		return '<a href="' . $Encoding . '">https://omnilogie.fr' . $Encoding . '</a>';
	}

	/**
	* Créer une ancre à partir d'un auteur
	* @param Author:String un auteur
	* @return :String un lien HTML
	*/
	public static function author($Author)
	{
		$Role = Member::getRoleFor($Author);
		return '<a href="' . Link::author($Author) . '" class="' . Member::getClassFor($Author) . '" title="Liste des articles ' . (in_array(strtolower($Author[0]),self::$Voyelles)?"d'":'de ') . $Author . ($Role!=''?' ('.preg_replace('`s$`','',$Role).')':'') . '" rel="author">' . $Author . '</a>';
	}

	/**
	* Créer une ancre à partir d'une catégorie
	* @param Category:String un auteur
	* @return :String un lien HTML
	*/
	public static function category($Category)
	{
		return '<a href="' . Link::category($Category) . '" title="Liste des articles dans la catégorie ' . $Category . '">' . $Category . '</a>';
	}

}

<?php
/**
* But : gérer le template de sortie.
* Voir le fichier V/lib/Template.php
*/
//Template

//////////////////////////////////////////////////////
//Fonctionnalités du contrôleur :

class Template
{
	/**
	* Affiche le contenu de la variable $C[$Var] si elle existe.
	* @param Var:String la variable à remplacer
	* @return :HTML
	*/
	public static function put($Var,$Prefix=null)
	{
		global $C;
		if(is_null($Prefix))
		{
			if(isset($C[$Var]))
				echo $C[$Var];
			else
				Debug::fail('Template : élément ' . $Var . ' inconnu.');
		}
		else
		{
			if(isset($C[$Prefix][$Var]))
				echo $C[$Prefix][$Var];
			else
				Debug::fail('Template : élément ' . $Var . ' du tableau ' . $Prefix . ' inconnu.');
		}
	}

	/**
	* Inclut le fichier $Path demandé s'il existe.
	* @param Path:String le chemin du fichier à inclure, relatif à /V/
	* @return :HTML
	*/
	public static function includeFile($Path)
	{
		global $C;
		if(is_file(PATH . '/V/' . $Path))
			include(PATH . '/V/' . $Path);
		else
			Debug::fail('Template : fichier ' . $Path . ' inconnu.');
	}

	/**
	* Inclut le fichier de la vue actuelle.
	* @return :HTML
	*/
	public static function includeView()
	{
		global $C;
		if(is_file(PATH . '/V/' . $_GET['P'] . '.php'))
			include(PATH . '/V/' . $_GET['P'] . '.php');
		else
			Debug::fail('Template : fichier de vue inexistant.');
	}
}
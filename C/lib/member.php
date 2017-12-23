<?php
/**
* But : faciliter les opérations des membres.
*
*/
//Member

//////////////////////////////////////////////////////
//Fonctionnalités du contrôleur :

class Member
{
	/**
	* Liste des administrateurs du site.
	* Tableau rempli dynamiquement au chargement de la classe à partir des groupes Apache.
	* Structure : Auteur=>role
	*/
	public static $Admins=array();

	/**
	* Liste des rôles sur le site.
	* Tableau rempli dynamiquement au chargement de la classe à partir des groupes Apache.
	* Structure : Role=>explication du rôle
	*/
	public static $Roles=array();

	/**
	* Récupérer la classe CSS à appliquer à un auteur.
	* Utilisée par Anchor::author().
	* @param Membre:String le membre à vérifier
	* @return :String la classe CSS.
	*/
	public static function getClassFor($Membre)
	{
		if(isset(self::$Admins[$Membre]))
			return 'author ' . self::$Admins[$Membre][0];
		else
			return 'author';
	}

	/**
	* Récupère le rôle principal d'un utilisateur.
	* @param Membre:String le membre à vérifier
	* @return :String
	*/
	public static function getRoleFor($Membre)
	{
		return (isset(self::$Admins[$Membre])?self::$Admins[$Membre][0]:'');
	}

	/**
	* Récupère tous les rôles d'un utilisateur.
	* Si l'utilisateur n'a aucun rôle, un tableau vide est renvoyé.
	* @param Membre:String le membre à vérifier
	* @return :array
	*/
	public static function getRolesFor($Membre)
	{
		return (isset(self::$Admins[$Membre])?self::$Admins[$Membre]:array());
	}

	/**
	* Renvoie true sur $membre appartient au groupe $role.
	* NOTE: Les admins font partie de tous les groupes.
	* NOTE: Le rôle 'any' est attribué à toutes les personnes qui sont plus que de simples membres.
	* @param Membre:String
	* @param Role:String le role, e.g. admins, censeurs, any.
	* @return :boolean
	*/
	public static function is($Membre,$Role)
	{
		if($Role=='any')
			return (count(self::getRolesFor($Membre))>0);
		else
			return (in_array($Role,self::getRolesFor($Membre)) || in_array('admins',self::getRolesFor($Membre)));
	}
}

//Créer le tableau des Admins :
$Liste = file(DATA_PATH . '/.groupes',FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
foreach($Liste as $Categorie)
{
	if($Categorie[0]=='#')
	{
		Member::$Roles[$AdminType] = substr($Categorie,1);
		continue;//Sauter les commentaires
	}

	$Categorie = explode(':',$Categorie);
	$AdminType = $Categorie[0];
	$Membres = explode(' ',$Categorie[1]);
	foreach($Membres as $Membre)
	{
		//Le fichier de groupe est classé par ordre croissant de responsabilité, l'élément 0 du tableau est donc le rôle principal de l'utilisateur.
		if(!isset(Member::$Admins[$Membre]))
			Member::$Admins[$Membre] = array($AdminType);
		else
			array_unshift(Member::$Admins[$Membre],$AdminType);
	}
}
unset($Liste,$Categorie,$AdminType,$Membres,$Membre);
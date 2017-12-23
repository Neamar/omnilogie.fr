<?php
/**
* But : faciliter les op�rations des membres.
*
*/
//Member

//////////////////////////////////////////////////////
//Fonctionnalit�s du contr�leur :

class Member
{
	/**
	* Liste des administrateurs du site.
	* Tableau rempli dynamiquement au chargement de la classe � partir des groupes Apache.
	* Structure : Auteur=>role
	*/
	public static $Admins=array();

	/**
	* Liste des r�les sur le site.
	* Tableau rempli dynamiquement au chargement de la classe � partir des groupes Apache.
	* Structure : Role=>explication du r�le
	*/
	public static $Roles=array();

	/**
	* R�cup�rer la classe CSS � appliquer � un auteur.
	* Utilis�e par Anchor::author().
	* @param Membre:String le membre � v�rifier
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
	* R�cup�re le r�le principal d'un utilisateur.
	* @param Membre:String le membre � v�rifier
	* @return :String
	*/
	public static function getRoleFor($Membre)
	{
		return (isset(self::$Admins[$Membre])?self::$Admins[$Membre][0]:'');
	}

	/**
	* R�cup�re tous les r�les d'un utilisateur.
	* Si l'utilisateur n'a aucun r�le, un tableau vide est renvoy�.
	* @param Membre:String le membre � v�rifier
	* @return :array
	*/
	public static function getRolesFor($Membre)
	{
		return (isset(self::$Admins[$Membre])?self::$Admins[$Membre]:array());
	}

	/**
	* Renvoie true sur $membre appartient au groupe $role.
	* NOTE: Les admins font partie de tous les groupes.
	* NOTE: Le r�le 'any' est attribu� � toutes les personnes qui sont plus que de simples membres.
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

//Cr�er le tableau des Admins :
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
		//Le fichier de groupe est class� par ordre croissant de responsabilit�, l'�l�ment 0 du tableau est donc le r�le principal de l'utilisateur.
		if(!isset(Member::$Admins[$Membre]))
			Member::$Admins[$Membre] = array($AdminType);
		else
			array_unshift(Member::$Admins[$Membre],$AdminType);
	}
}
unset($Liste,$Categorie,$AdminType,$Membres,$Membre);
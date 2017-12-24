<?php
/**
* Classe Top, permettant de g�rer les "Top" : les articles du mois.
*
*/
//Top

class Top
{
	/**
	 * Le mois pour lequel les votes sont actuellement ouverts
	 * Format MySql utilisable avec LIKE : 2011-01-%
	 */
	public static $month = null;

	/**
	 * Le mois pour lequel les votes sont actuellement ouverts
	 * Sous forme abr�g�e (01/10)
	 */
	public static $monthAbridged;

	/**
	 * Le mois pour lequel les votes sont actuellement ouverts
	 * Sous forme humainement lisible (janvier 2010)
	 */
	public static $monthReadable;

	/**
	* Ajoute une notification dans l'agenda de la personne d�finie par GOOGLE_PSEUDO et GOOGLE_PASSWORD.
	* L'enregistrement de l'�v�nement se fait en fin de page, afin de ne pas ralentir l'affichage.
	* @param Title:String le titre de la notification
	* @param Desc:String la description associ�e.
	* @param Date:int timestamp la date � laquelle il faut ajouter l'�venement. NOW() + 2 minutes par d�faut.
	*/
	public static function init()
	{
		if(self::$month != null)
			return;

		$datas = file(PATH . '/_D/mois_top', FILE_IGNORE_NEW_LINES);
		self::$month = $datas[0];
		self::$monthAbridged = $datas[1];
		self::$monthReadable = $datas[2];
	}

	/**
	 * Passer au vote pour le mois suivant
	 */
	public static function goNextMonth()
	{
		//R�cup�rer le timestamp du concours actuellement
		list($mois, $annee) = explode('/', self::$monthAbridged);
		$ts = mktime(0, 0, 0, $mois, 1, $annee);
		$ts = $ts + 35 * 24 * 3600;
		$ts = mktime(0, 0, 0, date('n', $ts), 1, date('Y', $ts));

		self::$month = date('Y-m-%', $ts);
		self::$monthAbridged = date('m/y', $ts);
		self::$monthReadable = str_replace(array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'), array('janvier', 'f�vrier', 'mars', 'avril', 'mai', 'juin', 'juillet', 'ao�t', 'septembre', 'octobre', 'novembre', 'd�cembre'), date('M Y', $ts));
	}
}

Top::init();

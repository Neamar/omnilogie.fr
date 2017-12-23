<?php
/**
* Classe Cache, permettant d'abstraire la logique du cache.
* Primitives bas niveau : set(), get().
* L'id�e de base est de regrouper par namespace les fichiers de cache, et de masquer la mise en oeuvre pour pouvoir passer � des solutions plus effectives quand cela deviendra n�cessaire (memcache par exemple)
*/
//External

class Cache
{
	public static $pageCachee;
	public static $pageExists;
	public static $pageId;

	/**
	* Enregistre un nouvel �l�ment dans le cache.
	* @param namespace:String l'espace de nom � utiliser, par exemple "pods"
	* @param name:String le nom de l'�l�ment � cacher
	*/
	public static function set($namespace,$name,$content)
	{
		if(!is_dir('/tmp/' . $namespace))
			mkdir('/tmp/' . $namespace);

		file_put_contents(self::getPath($namespace,$name),$content);
	}

	/**
	* R�cup�re un �l�ment pr�c�demment enregistr� dans le cache.
	* @param namespace:String l'espace de nom � utiliser, par exemple "pods"
	* @param name:String le nom de l'�l�ment � r�cup�rer dans l'espace de nom sp�cifi�.
	* @return :String le cache associ�
	* @throws exit crashe en cas d'acc�s � un �l�ment inconnu. V�rifier l'existence avant avec Cache::exists() si le cache peut ne pas exister.
	*/
	public static function get($namespace,$name)
	{
		return file_get_contents(self::getPath($namespace,$name));
	}

	/**
	* Supprime un �l�ment du cache.
	* @param namespace:String l'espace de nom � utiliser, par exemple "pods"
	* @param name:String le nom de l'�l�ment � supprimer dans l'espace de nom sp�cifi�.
	* @throws exit crashe en cas d'acc�s � un �l�ment inconnu. V�rifier l'existence avant avec Cache::exists() si le cache peut ne pas exister.
	*/
	public static function remove($namespace,$name)
	{
		unlink(self::getPath($namespace,$name));
	}

	/**
	* R�cup�re la date de derni�re modification d'un �l�ment.
	* @param namespace:String l'espace de nom � utiliser, par exemple "pods"
	* @param name:String le nom de l'�l�ment � tester dans l'espace de nom sp�cifi�.
	* @return :int le timestamp de derniere modification, ou 0 si l'�l�ment n'existe pas.
	*/
	public static function modified($namespace,$name)
	{
		if(self::exists($namespace,$name))
			return filemtime(self::getPath($namespace,$name));
		else
			return 0;
	}

	/**
	* R�cup�re la date de derni�re modification d'un �l�ment.
	* @param namespace:String l'espace de nom � utiliser, par exemple "pods"
	* @param name:String le nom de l'�l�ment � tester dans l'espace de nom sp�cifi�.
	* @return :bool true si l'�l�ment existe.
	*/
	public static function exists($namespace,$name)
	{
		return is_file(self::getPath($namespace,$name));
	}

	/**
	* Nettoie un namespace en supprimant toutes les donn�es en cache.
	* @param namespace:String l'espace de nom � utiliser, par exemple "pods"
	*/
	public static function removeNamespace($namespace)
	{
		$dh = opendir((PATH . '/Cache/' . $namespace . '/'));

		while (($file = readdir($dh)) !== false)
		{
			if(is_file($file))
				self::remove($namespace,$file);
		}
		closedir($dh);
	}

	/**
	* Met en cache une page compl�te.
	* Cette fonction peut �tre appel�e � n'importe quel moment, le plus utile �tant bien �videmment au tout d�but du contr�leur pour �viter d'appeler inutilement des fonctions.
	* Au prochain appel, mod�le et contr�leur (apr�s l'appel de la fonction) seront pass�s pour renvoyer directement la vue
	* @param uniqid:String un identifiant d�finissant de fa�on unique la page en cours. Si non sp�cifi�, l'url de la page en cours.
	* @return :bool true si un cache existe d�j�, � charge au contr�leur de s'arr�ter dans ce cas l�, false si le cache n'est pas pr�sent et qu'il va �tre g�n�r� ce coup-ci.
	* @example
	* //Depuis un contr�leur :
	* if(Cache::page())
	* 	return;
	*/
	public static function page($uniqid=null)
	{
		if(is_null($uniqid))
			$uniqid = $_SERVER['REQUEST_URI'];

		self::$pageCachee = $_GET['P'];
		self::$pageExists = self::exists('Page',$uniqid);
		self::$pageId = $uniqid;
		$_GET['P'] = 'cache';
		return self::$pageExists;

	}

	private static function getPath($namespace,$name)
	{
		return '/tmp/' . $namespace . '/' . preg_replace('`[^a-z0-9_.()=-]`i','',$name);
	}
}

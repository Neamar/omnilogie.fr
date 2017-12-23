<?php
/**
* Classe Cache, permettant d'abstraire la logique du cache.
* Primitives bas niveau : set(), get().
* L'idée de base est de regrouper par namespace les fichiers de cache, et de masquer la mise en oeuvre pour pouvoir passer à des solutions plus effectives quand cela deviendra nécessaire (memcache par exemple)
*/
//External

class Cache
{
	public static $pageCachee;
	public static $pageExists;
	public static $pageId;

	/**
	* Enregistre un nouvel élément dans le cache.
	* @param namespace:String l'espace de nom à utiliser, par exemple "pods"
	* @param name:String le nom de l'élément à cacher
	*/
	public static function set($namespace,$name,$content)
	{
		if(!is_dir('/tmp/' . $namespace))
			mkdir('/tmp/' . $namespace);

		file_put_contents(self::getPath($namespace,$name),$content);
	}

	/**
	* Récupère un élément précédemment enregistré dans le cache.
	* @param namespace:String l'espace de nom à utiliser, par exemple "pods"
	* @param name:String le nom de l'élément à récupérer dans l'espace de nom spécifié.
	* @return :String le cache associé
	* @throws exit crashe en cas d'accès à un élément inconnu. Vérifier l'existence avant avec Cache::exists() si le cache peut ne pas exister.
	*/
	public static function get($namespace,$name)
	{
		return file_get_contents(self::getPath($namespace,$name));
	}

	/**
	* Supprime un élément du cache.
	* @param namespace:String l'espace de nom à utiliser, par exemple "pods"
	* @param name:String le nom de l'élément à supprimer dans l'espace de nom spécifié.
	* @throws exit crashe en cas d'accès à un élément inconnu. Vérifier l'existence avant avec Cache::exists() si le cache peut ne pas exister.
	*/
	public static function remove($namespace,$name)
	{
		unlink(self::getPath($namespace,$name));
	}

	/**
	* Récupère la date de dernière modification d'un élément.
	* @param namespace:String l'espace de nom à utiliser, par exemple "pods"
	* @param name:String le nom de l'élément à tester dans l'espace de nom spécifié.
	* @return :int le timestamp de derniere modification, ou 0 si l'élément n'existe pas.
	*/
	public static function modified($namespace,$name)
	{
		if(self::exists($namespace,$name))
			return filemtime(self::getPath($namespace,$name));
		else
			return 0;
	}

	/**
	* Récupère la date de dernière modification d'un élément.
	* @param namespace:String l'espace de nom à utiliser, par exemple "pods"
	* @param name:String le nom de l'élément à tester dans l'espace de nom spécifié.
	* @return :bool true si l'élément existe.
	*/
	public static function exists($namespace,$name)
	{
		return is_file(self::getPath($namespace,$name));
	}

	/**
	* Nettoie un namespace en supprimant toutes les données en cache.
	* @param namespace:String l'espace de nom à utiliser, par exemple "pods"
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
	* Met en cache une page complète.
	* Cette fonction peut être appelée à n'importe quel moment, le plus utile étant bien évidemment au tout début du contrôleur pour éviter d'appeler inutilement des fonctions.
	* Au prochain appel, modèle et contrôleur (après l'appel de la fonction) seront passés pour renvoyer directement la vue
	* @param uniqid:String un identifiant définissant de façon unique la page en cours. Si non spécifié, l'url de la page en cours.
	* @return :bool true si un cache existe déjà, à charge au contrôleur de s'arrêter dans ce cas là, false si le cache n'est pas présent et qu'il va être généré ce coup-ci.
	* @example
	* //Depuis un contrôleur :
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

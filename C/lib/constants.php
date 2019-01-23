<?php
/**
* But : Charger toutes les constantes nécessaires pour Omnilogie.
* La classe en elle-même est inutile, seules les valeurs définies ont leur utilité.
*/
//Constants

//Le mode de fonctionnement actuel du site.
	//True : afficher les messages d'erreurs et la pile d'appels, débugger SQL...
	//False : masquer les erreurs et les logger.
define('MODE_DEBUG',true);

//Chemin vers les librairies
define('LIB_PATH','/app/lib/');

//Chemin vers les données
define('DATA_PATH','/app/raw/');
define('PROCHAIN_PATH','/app/prochain/prochain');

//L'adresse sur laquelle le site est hébergé. Utile pour les redirections ou les liens qu'on doit placer en absolu.
define('URL','https://omnilogie.fr');

//Le sel utilisé sur le site pour tous les hashages
define('SALT','Omnilogie_bf9edb51f88dc31e');

//Le caractère de césure utilisé sur les articles pour représenter [espace]
define('CESURE','_');

//Nombre maximum d'articles à afficher sur une page.
define('OMNI_MAX_PAGE',50);

//Permet de contrôler la façon dont se fait l'affichage d'un article via la méthode Omni->outputHeader()
	//Normal : tout afficher
	define('OMNI_NORMAL_HEADER',false);
	//Small : n'afifcher que les informations principales
	define('OMNI_SMALL_HEADER',true);

//Permet de contrôler les données à récupérer lors de la récupération d'une liste d'articles avec la méthode Omni->get()
	//Uniquement le titre et l'accroche (et l'ID).
	define('OMNI_TRAILER_PARAM',0);
	//Minimiser la récupération d'information, et les jointures effectuées sur la requête.
	define('OMNI_TEASER_PARAM',1);
	//Normal : récupérer les infos pour l'afifchage, et faire les jointures sur l'auteur. Pas les catégories.
	define('OMNI_SMALL_PARAM',2);
	//Maximiser les infos récupérées, pour l'affichage complet ; par exemple dans le flux RSS. Ajoute les catégories
	define('OMNI_FULL_PARAM',3);
	//Maximiser les infos récupérées, pour l'affichage complet. Ajoute l'article suivant et les dernières modifications.s
	define('OMNI_HUGE_PARAM',4);

//Paramètre à passer quand on veut enregistrer et versionner un article pour comparaison entre versions sur /Log/
define('OMNI_SAVE',true);

//Nombre d'articles à afficher dans les liens "cet articles vous plairont surement"
define('OMNI_NB_SIMILAR',6);

//Nombre d'articles à afficher quand on mentionne une saga dans les menus
define('SAGA_NB_SHOW',10);

//Nombre d'articles à afficher quand on affiche la page d'une catégorie
define('CATEGORY_NB_SHOW',10);

//Nombre de tags pour avoir une taille de 2 dans le nuage de catégorie
define('CAT_CLOUD_BIG_SIZE',60);
//Nombre de tags à afficher dans le nuage de catégories
define('CAT_CLOUD',10);
//À combien se limiter lors de la recherche d'auteurs récents dans le menu "Actifs récemment" ?
define('AUT_LOOKUP',2);

//Infos de connexion pour External::
define('TWITTER_PSEUDO','Omnilogie');
define('TWITTER_PASSWORD','BotOmnilogie69');

define('GOOGLE_PSEUDO','omni@neamar.fr');
define('GOOGLE_PASSWORD','Omniscient[LJo9jab4');

//////////////////////////////////////////////////////
//Fonctionnalités du contrôleur :

class Constants
{
	/**
	* Force le chargement du fichier et la liste des define.
	*/
	public static function load()
	{}
}

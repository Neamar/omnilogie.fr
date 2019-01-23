<?php
/**
* But : Charger toutes les constantes n�cessaires pour Omnilogie.
* La classe en elle-m�me est inutile, seules les valeurs d�finies ont leur utilit�.
*/
//Constants

//Le mode de fonctionnement actuel du site.
	//True : afficher les messages d'erreurs et la pile d'appels, d�bugger SQL...
	//False : masquer les erreurs et les logger.
define('MODE_DEBUG',true);

//Chemin vers les librairies
define('LIB_PATH','/app/lib/');

//Chemin vers les donn�es
define('DATA_PATH','/app/raw/');
define('PROCHAIN_PATH','/app/prochain/prochain');

//L'adresse sur laquelle le site est h�berg�. Utile pour les redirections ou les liens qu'on doit placer en absolu.
define('URL','https://omnilogie.fr');

//Le sel utilis� sur le site pour tous les hashages
define('SALT','Omnilogie_bf9edb51f88dc31e');

//Le caract�re de c�sure utilis� sur les articles pour repr�senter [espace]
define('CESURE','_');

//Nombre maximum d'articles � afficher sur une page.
define('OMNI_MAX_PAGE',50);

//Permet de contr�ler la fa�on dont se fait l'affichage d'un article via la m�thode Omni->outputHeader()
	//Normal : tout afficher
	define('OMNI_NORMAL_HEADER',false);
	//Small : n'afifcher que les informations principales
	define('OMNI_SMALL_HEADER',true);

//Permet de contr�ler les donn�es � r�cup�rer lors de la r�cup�ration d'une liste d'articles avec la m�thode Omni->get()
	//Uniquement le titre et l'accroche (et l'ID).
	define('OMNI_TRAILER_PARAM',0);
	//Minimiser la r�cup�ration d'information, et les jointures effectu�es sur la requ�te.
	define('OMNI_TEASER_PARAM',1);
	//Normal : r�cup�rer les infos pour l'afifchage, et faire les jointures sur l'auteur. Pas les cat�gories.
	define('OMNI_SMALL_PARAM',2);
	//Maximiser les infos r�cup�r�es, pour l'affichage complet ; par exemple dans le flux RSS. Ajoute les cat�gories
	define('OMNI_FULL_PARAM',3);
	//Maximiser les infos r�cup�r�es, pour l'affichage complet. Ajoute l'article suivant et les derni�res modifications.s
	define('OMNI_HUGE_PARAM',4);

//Param�tre � passer quand on veut enregistrer et versionner un article pour comparaison entre versions sur /Log/
define('OMNI_SAVE',true);

//Nombre d'articles � afficher dans les liens "cet articles vous plairont surement"
define('OMNI_NB_SIMILAR',6);

//Nombre d'articles � afficher quand on mentionne une saga dans les menus
define('SAGA_NB_SHOW',10);

//Nombre d'articles � afficher quand on affiche la page d'une cat�gorie
define('CATEGORY_NB_SHOW',10);

//Nombre de tags pour avoir une taille de 2 dans le nuage de cat�gorie
define('CAT_CLOUD_BIG_SIZE',60);
//Nombre de tags � afficher dans le nuage de cat�gories
define('CAT_CLOUD',10);
//� combien se limiter lors de la recherche d'auteurs r�cents dans le menu "Actifs r�cemment" ?
define('AUT_LOOKUP',2);

//Infos de connexion pour External::
define('TWITTER_PSEUDO','Omnilogie');
define('TWITTER_PASSWORD','BotOmnilogie69');

define('GOOGLE_PSEUDO','omni@neamar.fr');
define('GOOGLE_PASSWORD','Omniscient[LJo9jab4');

//////////////////////////////////////////////////////
//Fonctionnalit�s du contr�leur :

class Constants
{
	/**
	* Force le chargement du fichier et la liste des define.
	*/
	public static function load()
	{}
}

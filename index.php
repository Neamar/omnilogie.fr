<?php
/**
* Fichier principal d'Omnilogie, appelé à chaque page.
* Gère les pages selon une architecture type "MVC-customisée" :
* Au démarrage, le contrôleur prépare à la mise en cache si nécessaire, fait des redirections, vérifie la cohérence de la page, commite les modifications en BDD s'il y en a. Il bénéficie donc d'ores et déjà d'une connexion à la Base de données.
* Ensuite, le modèle charge les données qui devront être affichées. Toutes ces données sont placées dans le tableau $C (comme Content).
* On appelle ensuite le template générique, et on le remplit avec les informations de la vue associée à la page en cours.
*/

/**
* Conventions de nommage :
* Les fonctions génériques sont placées dans le dossier C/lib/
* Les fonctions génériques sont du type type_Nom, par exemple formatting_ShowColumn()
* Les noms de fonction sont en lowerCamelCase
* Les noms de variable sont en UpperCamelCase
* Les noms sont en anglais.
* Les vues génériques sont dans le dossier V/lib/
*/

/////////////////////////////////////////////////////////////
//PARTIE CONTRÔLE
/////////////////////////////////////////////////////////////
//error_reporting(-1); //Inutile, un module set_error_handler se chargera de traiter ces exceptions.

define('PATH',substr(__FILE__,0,strrpos(__FILE__,'/')));
date_default_timezone_set("Europe/Paris");

$C=array('menus'=>array(), 'head'=>array());//Contiendra tout le contenu des pages, la variable est chargée par le contrôleur pour le titre et le modèle pour le reste.




/**
* On utilise un système d'include 'à la volée', pour ne pas avoir à marquer manuellement les dépendances de chaque fichier.
* Cette fonction est appelée automatiquement par PHP lorsqu'il renconctre un appel à une classe qu'il ne connait pas encore.
* @param Class:String la classe recherchée
*/
spl_autoload_register(function ($Class)
{
	//Ne pas charger les classes de Zend
	if(substr($Class,0,5)=='Zend_')
		return;

	//Charger les autres en les cherchant dans les librairies
	$File = 'C/lib/' . strtolower($Class) . '.php';

	if(is_file($File))
		include($File);
	else
		Debug::fail('Impossible de charger dynamiquement ' . $Class . ' dans ' . $File);
});

//Si rien n'est spécifié, on demande l'index
if(empty($_GET['P']))
	$_GET['P']='index';

//Charger les constantes utiles au site.
Constants::load();

//Vérifier qu'au moins un des fichiers MVC existe
if(!is_file('M/' . $_GET['P'] . '.php') && !is_file('V/' . $_GET['P'] . '.php') && !is_file('C/' . $_GET['P'] . '.php'))
	Debug::fail('Page inconnue : ' . $_GET['P']);


//On démarre la session
session_start();

//Démarrer le serveur SQL
Sql::connect();

//Démarrer le gestionnaire d'erreurs
set_error_handler('Debug::err_handler',-1);

//Préparer l'affichage des messages différés
if(isset($_SESSION['FutureMessage']))
{
	$C['Message'] = $_SESSION['FutureMessage'];
	if(isset($_SESSION['FutureMessageClass']))
		$C['MessageClass'] = $_SESSION['FutureMessageClass'];
	unset($_SESSION['FutureMessage'],$_SESSION['FutureMessageClass']);
}


//Personnes tentant de se connecter à l'ancienne, avec un hash directement dans l'URL (venant d'un mail probablement)
if(preg_match('`membre=([0-9abcdef]{32})$`',$_SERVER['REQUEST_URI']))
{
	$_SESSION['Membre']['RedirectTo'] = $_SERVER['REQUEST_URI'];
	$_GET['membre']=substr($_SERVER['REQUEST_URI'],-32);
	include(PATH . '/C/membres/connexion.php');
}
































//Faut-il faire paraître un article ?
$time = time();
$Date=file_get_contents(PROCHAIN_PATH);//NE RIEN METTRE ENTRE CES TROIS LIGNES
if($Date<$time) //IL FAUT QU'ELLES S'EXECUTENT D'UN BLOC
{
	file_put_contents(PROCHAIN_PATH,$Date + 86400 * 2); //POUR ÉVITER LA PARUTION MULTIPLE

	$Params = Admin::getProchains();
	$AParaitre = Omni::get($Params);
	$Article = array_shift($AParaitre);
	if(!is_null($Article))
	{
		SQL::update('OMNI_Omnilogismes', $Article->ID, array('_Sortie'=>'FROM_UNIXTIME(' . $Date . ')'));
		$Article->registerModif(Event::PARUTION,false,50);
	}
	else
		External::mail('omni@neamar.fr','Erreur critique.','Impossible de faire paraître un article, la liste est vide. Réagissez !');
}

$DateParutionProchainArticle = $Date;
$C['Snippet']['nextArticle'] = date('d/m/Y à G:i', $Date);
$C['Snippet']['nextArticleDateTime'] = date('Y-m-d', $Date);














































//On inclut le contrôleur du dossier s'il existe
if (is_file('C/' . dirname($_GET['P']) . '/generic.php'))
    include 'C/' . dirname($_GET['P']) . '/generic.php';
//On inclut le contrôleur s'il existe
if (is_file('C/' . $_GET['P'] . '.php'))
    include 'C/' . $_GET['P'] . '.php';

































/////////////////////////////////////////////////////////////
//PARTIE MODÈLE
/////////////////////////////////////////////////////////////
//On charge les menus
include 'M/lib/menu.php';

//On inclut le modèle
include 'M/' . $_GET['P'] . '.php';
SQL::disconnect();



//Logger les messages :
if(isset($C['Message']))
	Event::log('Message : ' . $C['Message'] . (isset($C['MessageClass']) && $C['MessageClass']!='info'?'[' . $C['MessageClass'] . ']':''));


if(isset($_SERVER['HTTP_USER_AGENT']) && stripos($_SERVER['HTTP_USER_AGENT'], 'android') === false)
{
	if(!isset($_SESSION['Membre']['Pseudo']))
	{
		$C['SpecialPod'] = '<p>Omnilogie est un site collaboratif ouvert à tous&nbsp;: chaque jour, nous faisons paraître un court article de culture générale. Pour cela, nous avons besoin de rédacteurs : <a href="https://omnilogie.fr/membres/Inscription">n\'hésitez pas à participer</a>. Tous, nous avons quelque chose à partager&nbsp;!</p>
		<p>&rarr; <a href="/membres/Inscription">Je m\'inscris !</a></p>';
		prependPod('special-pod','Engagez-vous !','<div id="div-special-pod">' . $C['SpecialPod'] . '</div>');
	}
}
else
{
	//Menu spécial pour Android
	$C['SpecialPod'] = '<p style="text-align:center"><img src="/images/app/android.png" alt="Market" /><p>Vous pouvez aussi consulter Omnilogie depuis l\'application Android dédiée</p>
	<p>&rarr; <a href="https://market.android.com/details?id=fr.omnilogie.app">Je la télécharge !</a></p>';

	prependPod('special-pod','Le saviez-vous ?','<div id="div-special-pod">' . $C['SpecialPod'] . '</div>');
}



































/////////////////////////////////////////////////////////////
//PARTIE VUE
/////////////////////////////////////////////////////////////

//On inclut le template général.
include('V/lib/Template.php');

<?php
/**
* Fichier principal d'Omnilogie, appel� � chaque page.
* G�re les pages selon une architecture type "MVC-customis�e" :
* Au d�marrage, le contr�leur pr�pare � la mise en cache si n�cessaire, fait des redirections, v�rifie la coh�rence de la page, commite les modifications en BDD s'il y en a. Il b�n�ficie donc d'ores et d�j� d'une connexion � la Base de donn�es.
* Ensuite, le mod�le charge les donn�es qui devront �tre affich�es. Toutes ces donn�es sont plac�es dans le tableau $C (comme Content).
* On appelle ensuite le template g�n�rique, et on le remplit avec les informations de la vue associ�e � la page en cours.
*/

/**
* Conventions de nommage :
* Les fonctions g�n�riques sont plac�es dans le dossier C/lib/
* Les fonctions g�n�riques sont du type type_Nom, par exemple formatting_ShowColumn()
* Les noms de fonction sont en lowerCamelCase
* Les noms de variable sont en UpperCamelCase
* Les noms sont en anglais.
* Les vues g�n�riques sont dans le dossier V/lib/
*/

/////////////////////////////////////////////////////////////
//PARTIE CONTR�LE
/////////////////////////////////////////////////////////////
//error_reporting(-1); //Inutile, un module set_error_handler se chargera de traiter ces exceptions.

define('PATH',substr(__FILE__,0,strrpos(__FILE__,'/')));
date_default_timezone_set("Europe/Paris");

$C=array('menus'=>array(), 'head'=>array());//Contiendra tout le contenu des pages, la variable est charg�e par le contr�leur pour le titre et le mod�le pour le reste.




/**
* On utilise un syst�me d'include '� la vol�e', pour ne pas avoir � marquer manuellement les d�pendances de chaque fichier.
* Cette fonction est appel�e automatiquement par PHP lorsqu'il renconctre un appel � une classe qu'il ne connait pas encore.
* @param Class:String la classe recherch�e
*/
function __autoload($Class)
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
}

//Si rien n'est sp�cifi�, on demande l'index
if(empty($_GET['P']))
	$_GET['P']='index';

//Charger les constantes utiles au site.
Constants::load();

//V�rifier qu'au moins un des fichiers MVC existe
if(!is_file('M/' . $_GET['P'] . '.php') && !is_file('V/' . $_GET['P'] . '.php') && !is_file('C/' . $_GET['P'] . '.php'))
	Debug::fail('Page inconnue : ' . $_GET['P']);


//On d�marre la session
session_start();

//D�marrer le serveur SQL
Sql::connect();

//D�marrer le gestionnaire d'erreurs
set_error_handler('Debug::err_handler',-1);

//Pr�parer l'affichage des messages diff�r�s
if(isset($_SESSION['FutureMessage']))
{
	$C['Message'] = $_SESSION['FutureMessage'];
	if(isset($_SESSION['FutureMessageClass']))
		$C['MessageClass'] = $_SESSION['FutureMessageClass'];
	unset($_SESSION['FutureMessage'],$_SESSION['FutureMessageClass']);
}


//Personnes tentant de se connecter � l'ancienne, avec un hash directement dans l'URL (venant d'un mail probablement)
if(preg_match('`membre=([0-9abcdef]{32})$`',$_SERVER['REQUEST_URI']))
{
	$_SESSION['Membre']['RedirectTo'] = $_SERVER['SCRIPT_URL'];
	$_GET['membre']=substr($_SERVER['REQUEST_URI'],-32);
	include(PATH . '/C/membres/connexion.php');
}
































//Faut-il faire para�tre un article ?
$time = time();
$Date=file_get_contents(DATA_PATH . '/prochain');//NE RIEN METTRE ENTRE CES TROIS LIGNES
if($Date<$time) //IL FAUT QU'ELLES S'EXECUTENT D'UN BLOC
{
	file_put_contents(DATA_PATH . '/prochain',$Date + 86400 * 2); //POUR �VITER LA PARUTION MULTIPLE

	$Params = Admin::getProchains();
	$AParaitre = Omni::get($Params);
	$Article = array_shift($AParaitre);
	if(!is_null($Article))
	{
		SQL::update('OMNI_Omnilogismes', $Article->ID, array('_Sortie'=>'FROM_UNIXTIME(' . $Date . ')'));
		$Article->registerModif(Event::PARUTION,false,50);
	}
	else
		External::mail('omni@neamar.fr','Erreur critique.','Impossible de faire para�tre un article, la liste est vide. R�agissez !');
}

$DateParutionProchainArticle = $Date;
$C['Snippet']['nextArticle'] = date('d/m/Y � G:i', $Date);
$C['Snippet']['nextArticleDateTime'] = date('Y-m-d', $Date);














































//On inclut le contr�leur du dossier s'il existe
if (is_file('C/' . dirname($_GET['P']) . '/generic.php'))
    include 'C/' . dirname($_GET['P']) . '/generic.php';
//On inclut le contr�leur s'il existe
if (is_file('C/' . $_GET['P'] . '.php'))
    include 'C/' . $_GET['P'] . '.php';

































/////////////////////////////////////////////////////////////
//PARTIE MOD�LE
/////////////////////////////////////////////////////////////
//On charge les menus
include 'M/lib/menu.php';

//On inclut le mod�le
include 'M/' . $_GET['P'] . '.php';
SQL::disconnect();



//Logger les messages :
if(isset($C['Message']))
	Event::log('Message : ' . $C['Message'] . (isset($C['MessageClass']) && $C['MessageClass']!='info'?'[' . $C['MessageClass'] . ']':''));


if(isset($_SERVER['HTTP_USER_AGENT']) && stripos($_SERVER['HTTP_USER_AGENT'], 'android') === false)
{
	if(!isset($_SESSION['Membre']['Pseudo']))
	{
		$C['SpecialPod'] = '<p>Omnilogie est un site collaboratif ouvert � tous&nbsp;: chaque jour, nous faisons para�tre un court article de culture g�n�rale. Pour cela, nous avons besoin de r�dacteurs : <a href="https://omnilogie.fr/membres/Inscription">n\'h�sitez pas � participer</a>. Tous, nous avons quelque chose � partager&nbsp;!</p>
		<p>&rarr; <a href="/membres/Inscription">Je m\'inscris !</a></p>';
		prependPod('special-pod','Engagez-vous !','<div id="div-special-pod">' . $C['SpecialPod'] . '</div>');
	}
}
else
{
	//Menu sp�cial pour Android
	$C['SpecialPod'] = '<p style="text-align:center"><img src="/images/app/android.png" alt="Market" /><p>Vous pouvez aussi consulter Omnilogie depuis l\'application Android d�di�e</p>
	<p>&rarr; <a href="https://market.android.com/details?id=fr.omnilogie.app">Je la t�l�charge !</a></p>';

	prependPod('special-pod','Le saviez-vous ?','<div id="div-special-pod">' . $C['SpecialPod'] . '</div>');
}



































/////////////////////////////////////////////////////////////
//PARTIE VUE
/////////////////////////////////////////////////////////////

//On inclut le template g�n�ral.
include('V/lib/Template.php');

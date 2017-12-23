<?php
/**
* But : offrir des fonctions pour faciliter le d�buggage. En changeant la constante define DEBUG sur false, les informations ne sont plus affich�es, ce qui correspond � mettre le site en production.
*
*/
//Debug

//////////////////////////////////////////////////////
//Fonctionnalit�s du contr�leur :

class Debug
{
	/**
	* Appel� quand une requ�te SQL produit une erreur.
	* Affiche l'erreur SQL et la pile d'appel.
	*/
	public static function sqlFail()
	{
		self::fail(mysql_error());
	}

	/**
	* Appel� quand une erreur se produit / est d�clench�e par le code et n�cessite l'affichage d'un message d'erreur.
	* Note : cette fonction est pr�f�rable � exit() car elle facilite le d�buggage et le trace des erreurs en production.
	* @param Msg:String Le message � afficher
	* @return :void La fonction ne retourne jamais, le script est interrompu.
	*/
	public static function fail($Msg)
	{

		echo '<p style="border:1px dashed red;"><strong>D�sol�, une erreur critique s\'est produite. Nous tenterons de la corriger dans les plus brefs d�lais.</strong></p>';

		$trace = self::getDebugLog();

		if(isset($_SESSION['Membre']['Pseudo']) && $_SESSION['Membre']['Pseudo']=='Neamar')
		{
			exit('<pre>' . $trace . '</pre>');
		}
		else
		{
			External::mail('neamar@neamar.fr','Debug::fail sur ' . $_SERVER['REQUEST_URI'],'<pre>' . $trace . '</pre>');
			exit();
		}
	}

	/**
	* Gestionnaire personnalis� d'erreurs d�fini avec set_error_handler.
	* Permet d'�viter d'afficher les erreurs PHP � l'utilisateur, et de r�cup�rer proprement, par exemple en affichant une page d'erreur et en envoyant un mail � l'administrateur avec le contexte de l'erreur.
	* @param errno:int le num�ro de l'erreur, inutile.
	* @param errstr:String la description de l'erreur, plus utile ;)
	* @param errfile:String le fichier dans lequel s'est produit l'erreur
	* @param errline:int la ligne du fichier
	* @param errcontext:array un gros tableau (tr�s gros tableau m�me dans la plupart des cas) qui contient la liste des variables d�finies au moment du bug.
	* @return :void La fonction ne retourne jamais, le script est interrompu.
	*/
	public static function err_handler($errno, $errstr, $errfile, $errline, array $errcontext)
	{
		if(error_reporting()!=0)
			Debug::fail('Erreur PHP : ' . $errstr);
	}

	/**
	* Arr�te le script sans message, par exemple suite � une redirection HTML.
	* @return :void La fonction ne retourne jamais, le script est interrompu.
	*/
	public static function stop()
	{
		exit();
	}

	//http://gif.phpnet.org/frederic/programs/http_status_codes/
	private static $Codes=array
	(
		200=>'OK',
		301=>'Moved Permanently',
		302=>'Found',
		404=>'Not Found',
		403=>'Forbidden',
	);

	/**
	* Arr�te le script en faisant une redirection HTML avec le code d'erreur sp�cifi�.
	* @param Location:String le chemin absolu du nouvel emplacement
	* @param Code:Int le code d'erreur � renvoyer ; 301 si non sp�cifi�.
	* @return :void La fonction ne retourne jamais, le script est interrompu.
	*/
	public static function redirect($Location,$Code=301)
	{
		self::status($Code);
		header('Location: ' . URL . $Location);
		self::stop();
	}

	/**
	* Change le code HTTP associ� � la page.
	* S'il s'agit d'un code d'erreur, l'ex�cution du script est d�vi�e vers la page de gestion d'erreur.
	* @param Code:int Le nouveau code qui remplace l'ancien.
	* @example
	*	//Depuis un contr�leur
	*	//Renvoie un code 404, ce qui changera la page demand�e pour erreur lors du chargement des mod�les et vues.
	*	//Le return est important pour arr�ter le premier contr�leur.
	* 	return Debug::status(404);
	*/
	public static function status($Code)
	{
		if(!isset(self::$Codes[$Code]))
			self::fail('Code inconnu.');

		header('Status: ' . $Code. ' ' . self::$Codes[$Code], true, $Code);

		if($Code>400)//403,404,500
		{
			$_GET['Erreur'] = $Code;
			$_GET['P']='erreur';

			Event::log('==Statut ' . $Code . ' sur ' . $_SERVER['REQUEST_URI'] . (isset($_SERVER['HTTP_REFERER'])?' (referer : '  . $_SERVER['HTTP_REFERER'] . ')':''));
		}
	}

	private static function getDebugLog()
	{
		ob_start();
		echo '<strong>PAGE : </strong>' . '<a href="' . URL . $_SERVER['REQUEST_URI'] . '">' . $_SERVER['REQUEST_URI'] . '</a>';
		echo "\n\n\n";

		if(isset($_SERVER['HTTP_REFERER']))
		{
			echo '<strong>REFERRER : </strong>' . $_SERVER['HTTP_REFERER'];
			echo "\n\n\n";
		}

		$SESSION = $_SESSION;
		unset($SESSION['Membre']['Articles']);
		echo '<strong>SESSION : </strong>';
		print_r($SESSION);
		echo "\n\n\n";

		echo '<strong>TRACE : </strong>';
		debug_print_backtrace();
		echo "\n\n\n";

		echo '<strong>SERVEUR : </strong>';
		print_r($_SERVER);
		echo "\n\n\n";

		return preg_replace('`#0 .+ called at`','Erreur sur',ob_get_clean());
	}
}
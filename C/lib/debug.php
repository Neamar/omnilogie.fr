<?php
/**
* But : offrir des fonctions pour faciliter le débuggage. En changeant la constante define DEBUG sur false, les informations ne sont plus affichées, ce qui correspond à mettre le site en production.
*
*/
//Debug

//////////////////////////////////////////////////////
//Fonctionnalités du contrôleur :

class Debug
{
	/**
	* Appelé quand une requête SQL produit une erreur.
	* Affiche l'erreur SQL et la pile d'appel.
	*/
	public static function sqlFail()
	{
		self::fail(mysql_error());
	}

	/**
	* Appelé quand une erreur se produit / est déclenchée par le code et nécessite l'affichage d'un message d'erreur.
	* Note : cette fonction est préférable à exit() car elle facilite le débuggage et le trace des erreurs en production.
	* @param Msg:String Le message à afficher
	* @return :void La fonction ne retourne jamais, le script est interrompu.
	*/
	public static function fail($Msg)
	{
		if(!headers_sent())
		{
			http_response_code(500);
		}

		echo '<p style="border:1px dashed red;"><strong>Désolé, une erreur critique s\'est produite. Nous tenterons de la corriger dans les plus brefs délais.</strong></p><p>' . $Msg . '</p>';

		$trace = self::getDebugLog();

		if(getenv("DEBUG") == 1)
		{
			exit('<pre>' . $trace . '</pre>');
		}
		else if(isset($_SESSION['Membre']['Pseudo']) && $_SESSION['Membre']['Pseudo']=='Neamar')
		{
			exit('<pre>' . $trace . '</pre>');
		}
		else
		{
			$subject = 'Debug::fail sur ' . $_SERVER['REQUEST_URI'];

			$email = new \SendGrid\Mail\Mail();
			$email->setFrom('contact@neamar.fr');
			$email->setReplyTo('contact@neamar.fr');
			$email->setSubject($subject);
			$email->addTo('neamar@neamar.fr');
			$email->addContent("text/plain", $trace);
			$sendgrid = new \SendGrid(getenv('SENDGRID_API_KEY'));
			$sendgrid->send($email);
			exit();
		}
	}

	/**
	* Gestionnaire personnalisé d'erreurs défini avec set_error_handler.
	* Permet d'éviter d'afficher les erreurs PHP à l'utilisateur, et de récupérer proprement, par exemple en affichant une page d'erreur et en envoyant un mail à l'administrateur avec le contexte de l'erreur.
	* @param errno:int le numéro de l'erreur, inutile.
	* @param errstr:String la description de l'erreur, plus utile ;)
	* @param errfile:String le fichier dans lequel s'est produit l'erreur
	* @param errline:int la ligne du fichier
	* @param errcontext:array un gros tableau (très gros tableau même dans la plupart des cas) qui contient la liste des variables définies au moment du bug.
	* @return :void La fonction ne retourne jamais, le script est interrompu.
	*/
	public static function err_handler($errno, $errstr, $errfile, $errline)
	{
		if(error_reporting()!=0)
			Debug::fail('Erreur PHP : ' . $errstr);
	}

	/**
	* Arrête le script sans message, par exemple suite à une redirection HTML.
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
	* Arrête le script en faisant une redirection HTML avec le code d'erreur spécifié.
	* @param Location:String le chemin absolu du nouvel emplacement
	* @param Code:Int le code d'erreur à renvoyer ; 301 si non spécifié.
	* @return :void La fonction ne retourne jamais, le script est interrompu.
	*/
	public static function redirect($Location,$Code=301)
	{
		self::status($Code);
		header('Location: ' . URL . $Location);
		self::stop();
	}

	/**
	* Change le code HTTP associé à la page.
	* S'il s'agit d'un code d'erreur, l'exécution du script est déviée vers la page de gestion d'erreur.
	* @param Code:int Le nouveau code qui remplace l'ancien.
	* @example
	*	//Depuis un contrôleur
	*	//Renvoie un code 404, ce qui changera la page demandée pour erreur lors du chargement des modèles et vues.
	*	//Le return est important pour arrêter le premier contrôleur.
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

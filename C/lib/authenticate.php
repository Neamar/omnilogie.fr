<?php
/**
* But : réaliser une authentification HTTP en n'utilisant que PHP (sans passer par le .htaccess de Apache)
*
*/
//Authenticate
//////////////////////////////////////////////////////
//Fonctionnalités du contrôleur :
class Authenticate
{

	const NB_ESSAIS_AVANT_BAN = 3;

	/**
	* Tente de connecter l'utilisateur qui veut voir la page.
	* Utilise une authentification HTTP si l'utilisateur n'est pas encore enregistré. S'il est déjà connecté, vérifier ses permissions.
	* @param roles:array les rôles autorisés. L'utilisateur qui se connecte doit faire partie d'au moins un des groupes de $roles pour être autorisé.
	* @return :boolean true si l'identification a fonctionné ; si elle échoue le script s'arrête.
	*/
	public static function login(array $roles)
	{
		if(isset($_SESSION['Membre']['Pseudo']) && empty($_SERVER['REDIRECT_REDIRECT_LOGIN']))
			$Login = $_SESSION['Membre']['Pseudo'];
		else
		{
			error_log("OMNI Not connected");
			//Est-on connecté ?
			if(empty($_SERVER['REDIRECT_REDIRECT_LOGIN']))
				self::askForLogin();

			//Récupérer les infos de connexion entrées :
			$Login = base64_decode(substr($_SERVER['REDIRECT_REDIRECT_LOGIN'],5));
			error_log("OMNI " . $Login);

			$Infos = explode(':',$Login,2);

			$Login = $Infos[0];

			//Récupérer le mot de passe de l'utilisateur :
			$Liste = file(DATA_PATH . '/.admin',FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
			foreach($Liste as $Membre)
			{
				if($Membre[0]=='#')
					continue;//Sauter les commentaires

				$Membre = explode(':',$Membre);
				error_log("OMNI Membres " . $Membre[0]);

				if($Membre[0]==$Login)
					break;
			}

			//A-t-il entré le bon mot de passe ?
			//Pour cela, on crypte en salant avec le hash en mémoire, et on compare avec ce même hash : les résultats doivent être similaires.
			error_log("OMNI PWD " . $Membre[1]);

			if($Membre[0]==$Login && crypt($Infos[1],$Membre[1])==$Membre[1])
			{
				error_log("OMNI LOGGED IN");
				$_SERVER['REMOTE_USER'] = $Login;
				$_SERVER['SAFE_LOG'] = true;
			}
			else
				self::askForLogin();

			//Si un admin était connecté sur un faux compte, la condition qui suit permet de le déconnecter du pseudo et de la repasser sur son compte admin, qui dispose des droits d'accès.
			if(!isset($_SESSION['Membre']['Pseudo']) || $Login != $_SESSION['Membre']['Pseudo'])
			{
				//Si tout est OK, le connecter en tant que membre (en plus d'admin)
				$_SESSION['Membre']['RedirectTo'] = $_SERVER['SCRIPT_URL'];
				include(PATH . '/C/membres/connexion.php');
			}
		}

		//L'utilisateur est-il autorisé ?
		$estAutorise=false;
		foreach($roles as $role)
		{
			if(Member::is($Login,$role))
			{
				$estAutorise = true;
				break;
			}
		}
		if(!$estAutorise)
			self::askForLogin();

		//Fin de l'identification !
		return true;
	}

	/**
	* Envoie les headers demandant la connexion du visiteur.
	* Bloque après self::NB_ESSAIS_AVANT_BAN essais infructueux.
	*/
	private static function askForLogin()
	{
		if(!isset($_SESSION['NbEssaiConnexion']))
			$_SESSION['NbEssaiConnexion']=0;

		$_SESSION['NbEssaiConnexion']++;

		if($_SESSION['NbEssaiConnexion'] > self::NB_ESSAIS_AVANT_BAN && 0)
			exit("Bon, soyons franc... t'as aucune idée du mot de passe ! Passe ton chemin, le site a des pages intéressantes même quand on n'est pas admin ;)");
		else
		{
			header('HTTP/1.1 401 Authorization Required');
			header('WWW-Authenticate: Basic realm="Pages protégées"');

			exit("L'accès à ces pages est protégé.");
		}
	}

}

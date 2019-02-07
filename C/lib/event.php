<?php
/**
* But : simuler une gestion d'évenements en PHP.
* Permet d'enregistrer des actions, et de déclencher des fonctions sur ces actions.
*/
//Omni

//////////////////////////////////////////////////////
//Fonctionnalités du contrôleur :


class Event
{
	const NOUVEAU = 'Création de l\'omnilogisme';
	const EDITION = 'Correction des erreurs';
	const TAGGAGE = 'Modification des mots clés de l\'omnilogisme';
	const LOADING = 'Chargement des données distantes';
	const ACCROCHAGE = 'Ajout d\'une accroche';
	const PARUTION = 'Parution officielle de l\'omnilogisme';
	const ACCEPTE = 'Statut changé vers ACCEPTE';
	const BANNIERE = 'Ajout d\'une bannière';
	const A_CORRIGER = 'Statut changé vers A_CORRIGER';
	const REF = 'Modification des références';
	const CHANGEMENT_GENERIQUE = 'Modification d\'une des données de l\'article';

	//Constantes non associées à un article en particulier
	const NOUVELLE_PROPOSITION = 'Nouvelle proposition';
	const NOUVELLE_CATEGORIE = 'Nouvelle catégorie';

	private static $Events = array();

	/**
	* Transmet un événement aux écouteurs associés.
	* @param Event:String l'évenement à dispatcher. Théoriquement une constante statique de Event ;)
	* @param Article:Omni l'article concerné par la modification. Dans certains cas particuliers, il s'agit d'une info non pertinente (par exemple à l'enregistrement d'une proposition) auquel cas $Article est considéré comme null.
	*/
	public static function dispatch($Event, Omni $Article = null)
	{
		if(count(self::$Events)==0)
			self::buildEvents();

		$EventType = array_search($Event,self::$Events);

		//S'il y a des listeners associés :
		if($EventType!=false && is_dir(PATH . '/E/' . strtolower($EventType)))
		{

			//Les lister et les exécuter.
			$handle = opendir(PATH . '/E/' .strtolower($EventType));
			while (false !== ($file = readdir($handle)))
			{
				if ($file != "." && $file != "..")
					include(PATH . '/E/' .strtolower($EventType) . '/' . $file);

			}
			closedir($handle);
        }

        self::log('Dispatch : ' . ($EventType==false?$Event:$EventType) . (!is_null($Article)?' sur ' . $Article->Titre:''));
	}

	/**
	* Appelle un évenement générique.
	* Les événements génériques sont des évènements qui peuvent être déclenchés de plusieurs façons ; pour éviter de dupliquer le code on les met dans le dossier _generic.
	* @param $File le nom du fichier
	* @example
	* //Cette ligne redispatche l'évenement sur un fichier générique de même nom.
	* Event::callGeneric(basename(__FILE__));
	*/
	public static function callGeneric($File)
	{
		include(PATH . '/E/generique/' . $File);
	}

	/**
	* Logge un événement quelconque : crash d'une page, dispatche d'un événement, action externe, bref toute action potentiellement intéressante (et crashable).
	* Format : timestamp	IP	Auteur	Action
	*/
	public static function log($Event)
	{


		if(isset($_SESSION['Membre']['Pseudo']))
			$Auteur = $_SESSION['Membre']['Pseudo'];
		else
			$Auteur = '-----';

		$Ligne = "APPLOG" . '	' . str_pad($_SERVER['REMOTE_ADDR'],15) . '	' . str_pad($Auteur,12) . '	' . $Event . "\n";

		error_log($Ligne);
	}

	/**
	* Transforme les constantes en tableau pour les manipuler facilement.
	*/
	private static function buildEvents()
	{
		$oClass = new ReflectionClass ('Event');
		self::$Events = $oClass->getConstants ();
	}

	/**
	* Récupérer les $Limites dernières actions répondant à $Condition
	* @param Limit:int le nombre d'actions à renvoyer
	* @param Condition:String une condition SQL
	*/
	public static function getLast($Limite,$Condition='1',$Pattern='%DATE% %LIEN% : %MODIF% par %AUTEUR%')
	{
		$Actions = SQL::query('SELECT Modifs.ID, DATE_FORMAT(Date, "%d/%m/%Y à %T") as Date, Titre, Modification, Auteurs.Auteur, !ISNULL(Sauvegarde) AS Svg
		FROM OMNI_Modifs Modifs
		LEFT JOIN OMNI_Omnilogismes Omnilogismes ON (Reference=Omnilogismes.ID)
		LEFT JOIN OMNI_Auteurs Auteurs ON (Modifs.Auteur=Auteurs.ID)
		WHERE ' . $Condition . '
		ORDER BY Modifs.ID DESC
		LIMIT ' . $Limite . '
		');

		$A=array();
		while($Action = mysql_fetch_array($Actions))
			$A[] = str_replace(
				array('%DATE%','%LIEN%','%MODIF%','%AUTEUR%','%DIFF%'),
				array('<small>[' . $Action['Date'] . ']</small>','<a href="' . Link::omni($Action['Titre']) . '">' . $Action['Titre'] . '</a>',$Action['Modification'],Anchor::author($Action['Auteur']),($Action['Svg']?'(<a href="/admin/Diff/' . $Action['ID'] . '">diff</a>)':'')),
				$Pattern);

		return $A;
	}
}

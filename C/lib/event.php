<?php
/**
* But : simuler une gestion d'�venements en PHP.
* Permet d'enregistrer des actions, et de d�clencher des fonctions sur ces actions.
*/
//Omni

//////////////////////////////////////////////////////
//Fonctionnalit�s du contr�leur :


class Event
{
	const NOUVEAU = 'Cr�ation de l\'omnilogisme';
	const EDITION = 'Correction des erreurs';
	const TAGGAGE = 'Modification des mots cl�s de l\'omnilogisme';
	const LOADING = 'Chargement des donn�es distantes';
	const ACCROCHAGE = 'Ajout d\'une accroche';
	const PARUTION = 'Parution officielle de l\'omnilogisme';
	const ACCEPTE = 'Statut chang� vers ACCEPTE';
	const BANNIERE = 'Ajout d\'une banni�re';
	const A_CORRIGER = 'Statut chang� vers A_CORRIGER';
	const REF = 'Modification des r�f�rences';
	const CHANGEMENT_GENERIQUE = 'Modification d\'une des donn�es de l\'article';

	//Constantes non associ�es � un article en particulier
	const NOUVELLE_PROPOSITION = 'Nouvelle proposition';
	const NOUVELLE_CATEGORIE = 'Nouvelle cat�gorie';

	private static $Events = array();

	/**
	* Transmet un �v�nement aux �couteurs associ�s.
	* @param Event:String l'�venement � dispatcher. Th�oriquement une constante statique de Event ;)
	* @param Article:Omni l'article concern� par la modification. Dans certains cas particuliers, il s'agit d'une info non pertinente (par exemple � l'enregistrement d'une proposition) auquel cas $Article est consid�r� comme null.
	*/
	public static function dispatch($Event, Omni $Article = null)
	{
		if(count(self::$Events)==0)
			self::buildEvents();

		$EventType = array_search($Event,self::$Events);

		//S'il y a des listeners associ�s :
		if($EventType!=false && is_dir(PATH . '/E/' . strtolower($EventType)))
		{

			//Les lister et les ex�cuter.
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
	* Appelle un �venement g�n�rique.
	* Les �v�nements g�n�riques sont des �v�nements qui peuvent �tre d�clench�s de plusieurs fa�ons ; pour �viter de dupliquer le code on les met dans le dossier _generic.
	* @param $File le nom du fichier
	* @example
	* //Cette ligne redispatche l'�venement sur un fichier g�n�rique de m�me nom.
	* Event::callGeneric(basename(__FILE__));
	*/
	public static function callGeneric($File)
	{
		include(PATH . '/E/generique/' . $File);
	}

	/**
	* Logge un �v�nement quelconque : crash d'une page, dispatche d'un �v�nement, action externe, bref toute action potentiellement int�ressante (et crashable).
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
	* R�cup�rer les $Limites derni�res actions r�pondant � $Condition
	* @param Limit:int le nombre d'actions � renvoyer
	* @param Condition:String une condition SQL
	*/
	public static function getLast($Limite,$Condition='1',$Pattern='%DATE% %LIEN% : %MODIF% par %AUTEUR%')
	{
		$Actions = SQL::query('SELECT Modifs.ID, DATE_FORMAT(Date, "%d/%m/%Y � %T") as Date, Titre, Modification, Auteurs.Auteur, !ISNULL(Sauvegarde) AS Svg
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

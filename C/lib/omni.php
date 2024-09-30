<?php
/**
* But : interagir facilement avec les articles.
* Un des principaux contrôleurs.
*/
//Omni

//////////////////////////////////////////////////////
//Fonctionnalités du contrôleur :



//Cette classe est construite directement par mysql_fetch_object, et déroge en conséquence aux règles de nommage des variables en anglais.
class Omni
{
	function __construct() {
  }

	//Uniquement le titre et l'accroche (et l'ID).
	const TRAILER_PARAM = 0;

	//Minimiser la récupération d'information, et les jointures effectuées sur la requête.
	const TEASER_PARAM = 1;

	//Normal : récupérer les infos pour l'affichage, et faire les jointures sur l'auteur. Pas les catégories.
	const SMALL_PARAM = 2;

	//Maximiser les infos récupérées, pour l'affichage complet ; par exemple dans le flux RSS. Ajoute les catégories
	const FULL_PARAM = 3;

	//Maximiser les infos récupérées, pour l'affichage complet. Ajoute l'article suivant et les dernières modifications.
	const HUGE_PARAM = 4;


	const BIG_BANNER = '';
	const THUMB_BANNER = 'Thumbs/';

	public $ID;
	public $Auteur;
	public $Adsense;
	public $GooglePlus;
	public $Message;
	public $Date;
	public $Timestamp;
	public $Titre;
	public $Omnilogisme;
	public $Statut;
	public $Accroche;
	public $Mots;		//Les mots-clés asosciés à l'article
	public $Categories;	//Les catégories DE PREMIER NIVEAU, pas l'arbre.
	public $TitreSuivant;	//L'article qui suit celui-là
	public $AccrocheSuivant;	//L'article qui suit celui-là
	public $TitrePrecedent;	//Et celui qui le précède.
	public $AccrochePrecedent;	//Et celui qui le précède.
	public $NbVues;
	public $Anecdote;
	public $SourceAnecdote;

	/**
	* Enregistre une modification sur l'article.
	* @param Action:String L'action à enregistrer, e.g. "Correction des erreurs". Théoriquement (mais pas obligatoire) une constante statique sur Event::
	* @param Svg:String cette modification nécessite-t-elle de logger le contenu pour comparaisons ultérieures ?
	* @param $Author:int l'auteur qui a effectué cette modification. Si non défini, c'est l'auteur actuellement connecté qui est choisi. Si non défini et que personne n'est connecté, la page s'arrête.
	*/
	public function registerModif($Action,$Svg=false,$Author=-1)
	{
		if($Author==-1)
		{
			if(!isset($_SESSION['Membre']['ID']))
				Debug::fail('Impossible d\'enregistrer une action effectuée par un membre non connecté !');
			$Author = $_SESSION['Membre']['ID'];
		}

		$Modifs=array(
			'Auteur'=>$Author,
			'Reference'=>$this->ID,
			'Modification'=>$Action,
			'_Date'=>'NOW()'
			);

		if($Svg)
			$Modifs['_Sauvegarde']='(SELECT Omnilogisme FROM OMNI_Omnilogismes WHERE ID=' . $this->ID . ')';

		if(!SQL::insert('OMNI_Modifs',$Modifs))
			Debug::fail(mysql_error());

		//Appeler les listeners associés :
		Event::dispatch($Action,$this);
		Event::dispatch(Event::CHANGEMENT_GENERIQUE,$this);
	}

	/**
	* Renvoie la liste des sources utilisées (si il y en a) dans une liste.
	* @return :array une liste des sources, sous la forme URL=>Titre
	*/
	public function getURLs()
	{
		static $Replacements=array('&'=>'&amp;');
		static $SpecialsChars=array('Ã©'=>'é','&'=>'&amp;','Ã¨'=>'è','Ã¢'=>'â','Ã'=>'à');

		$URL_SQL=Sql::query('SELECT URL,Titre FROM OMNI_More WHERE Reference=' . intval($this->ID) . ' ORDER BY ID');

		$URLs=array();
		while($URL=mysql_fetch_assoc($URL_SQL))
		{
			if($URL['Titre']=='')
				$URL['Titre']=substr($URL['URL'],0, (substr($URL['URL'],0,4)=='http')?50:500);

			$URL['URL'] = trim(str_replace(array_keys($Replacements),array_values($Replacements),$URL['URL']));
			$URL['Titre'] = trim(str_replace(array_keys($SpecialsChars),array_values($SpecialsChars),$URL['Titre']));

			$URLs[$URL['URL']]=$URL['Titre'];
		}
		return $URLs;
	}

	/**
	* Renvoie une image HTML représentant la bannière de l'article.
	* Si la bannière n'existe pas, une image générique est renvoyée.
	* @param Type:(BIG_BANNER|THUMB_BANNER) récupérer l'URL de la bannière, ou de la miniature. BIG_BANNER par défaut.
	*/
	public function getBanner($Type = Omni::BIG_BANNER)
	{
		return '<img src="' . $this->getBannerUrl($Type) . '" alt="' . $this->Titre . '" />';
	}

	public function getBannerUrl($Type = Omni::BIG_BANNER)
	{
		$URL = '/images/Banner/' . $Type . $this->ID . '.png';

		if(is_file(PATH . $URL))
			return $URL;
		else
			return '/images/Banner/' . $Type . 'Default.png';
	}

	/**
	* Renvoie la liste des catégories.
	* @return :array
	*/
	public function getCategories()
	{
		error_log(print_r($this->Categories, TRUE));
		return explode(',',$this->Categories);
	}

	/**
	* Renvoie le titre de l'article mis en forme
	* @return:String
	*/
	public function getTitre()
	{
			Typo::setTexte($this->Titre);
			return Typo::parseLinear();
	}

	/**
	* Renvoie l'accroche de l'article mise en forme, pour éviter par exemple d'avoir un "?" qui se balade en début de ligne !
	* @return:String
	*/
	public function getAccroche()
	{
		if($this->Accroche=='')
			return '';
		else
		{
			Typo::setTexte($this->Accroche);
			return Typo::parseLinear();
		}
	}

	/**
	* Renvoie uniquement le titre de l'article, mis en forme avec un lien.
	* Les préfixes inutiles comme "Le", "La", "Les", "L'" sont mis en petit
	* @return :String <a href="/O/Titre" title="Accroche">Titre mis en forme</a>
	*/
	public function outputTrailer()
	{
		return preg_replace('#\>((Le|La|Les) |L\')#i','><small>$1</small>',Anchor::omni($this));
	}

	/**
	* Renvoie un teaser contenant une image, le titre, l'accroche et l'auteur pour donner envie de lire l'article.
	*/
	public function outputTeaser()
	{
		$Retour ='<section class="omni_teaser">' . "\n";
		$Retour .='<hgroup>' . "\n";
		$Retour .='<h1>' . Anchor::omni($this) . '</h1>' . "\n";
		$Retour .= '<h2>' . $this->getBanner(Omni::THUMB_BANNER) . $this->getAccroche() . '</h2>' . "\n";
		$Retour .='</hgroup>' . "\n";
		$Retour .='<p>Par ' . Anchor::author($this->Auteur) . '</p>' . "\n";
		$Retour .='<p class="read-more"><a href="' . Link::omni($this->Titre) . '">Lire la suite&hellip;</a></p>' . "\n";
		$Retour .='</section>' . "\n";

		return $Retour;
	}

	/**
	* Renvoie tout l'article mis en forme.
	* Met à jour le nombre de vues de l'article aussi.
	* @return :String l'article mis en forme.
	*/
	public function outputFull()
	{
		SQL::update('OMNI_Omnilogismes',$this->ID,array('_NbVues'=>'NbVues+1'));
		Typo::setTexte($this->Omnilogisme);
		return ParseMath(Typo::Parse());
	}

	/**
	* Renvoie le premier paragraphe de l'article.
	* @return :String le début de l'article mis en forme.
	*/
	public function outputStart()
	{
		$noImgTexte = preg_replace('`^\s*\\\\image.+}\r\n`iU','',$this->Omnilogisme);
		$Texte = substr($noImgTexte,0,strpos($noImgTexte,"\r\n\r\n"));
		if($Texte == '')
			$Texte = substr($noImgTexte,0,strpos($noImgTexte,"\n\n"));
		Typo::setTexte($Texte);
		return ParseMath(preg_replace('`<img.+/>`U','',Typo::Parse()));
	}

	/**
	* Renvoie le titre, l'accroche si elle existe, la date de parution et l'auteur.
	* Structure :
	* img
	* h1 Titre
	* h2 Accroche
	* p.by-line Par .author le time[pubdate]
	* @param Style:enum(NORMAL_HEADER,SMALL_HEADER) Permet de contrôler le nombre d'informations à afficher.
	*
	*/
	public function outputHeader($Style=OMNI_NORMAL_HEADER)
	{
		$Retour = '';
		$Retour = $this->getBanner() . "\n";
		$Retour .= '<hgroup>' . "\n";
		$Retour .= '<h1>' . Anchor::omni($this) . '</h1>' . "\n";
		if($this->Accroche!='')
			$Retour .= '<h2>' . $this->getAccroche() . '</h2>' . "\n";
		$Retour .= '</hgroup>' . "\n";

		$Retour .='<p class="byline">' . "\n";
		$Retour .='Par ' . Anchor::author($this->Auteur) . ' ';

		if($this->Date!='')
			$Retour .='le <time datetime="' . date('Y-m-d',$this->Timestamp) . '" pubdate="pubdate">' . $this->Date . '</time>';
		else if($this->Statut == 'DEJA_TRAITE')
			$Retour .='<span class="unpublished">[un article similaire est déjà paru, celui-ci ne sera donc pas publié]</span>';
		else
			$Retour .='<span class="unpublished">[pas encore paru]</span>';

		$Retour .= '</p>' . "\n";

		if($this->Message!='' && $Style==OMNI_NORMAL_HEADER)
		{
			Typo::setTexte($this->Message);
			$Retour .='<header role="note" class="message">' . Typo::Parse() . '</header>' . "\n";
		}

		return $Retour;
	}

	/**
	* Renvoie tout l'article sans aucune mise en forme, en texte pur.
	* Utilise DOMDocument pour récupérer le texte du document HTML parsé.
	* @return :String l'article en texte pur.
	*/
	public function outputRaw()
	{
		Typo::setTexte($this->Titre);
		$Texte = '= ' . Typo::parseLinear($this->Titre) . ' = (' . $this->Date . ' par ' . $this->Auteur . ')' ."\n\n";

		Typo::setTexte($this->Omnilogisme);
		$Texte .= ParseMath(Typo::Parse());
		$Texte = str_replace('<li>','<li>    * ',$Texte);
		$Texte = str_replace('<hr class="footnote court" />','----------------------------',$Texte);

		$doc = new DOMDocument();
		$doc->loadHTML('<body>' . $Texte . '</body>');
		return $doc->getElementsByTagName('body')->item(0)->textContent;
	}

	/**
	* Renvoie une liste de $Nb articles au contenu similaire qui peuvent intéresser le lecteur
	* @param Nb:int le nombre d'article similaires à afficher, OMNI_NB_SIMILAR par défaut.
	* @return :SQLParam un objet permettant de récupérer les articles similaires.
	*/
	public function outputSimilar($Nb=OMNI_NB_SIMILAR)
	{
		//Article pas encore classifié.
		if(is_null($this->Categories))
			return null;

		$Items=array();
		Category::getUnidimensional(Category::getTree($this->getCategories()),$Items);

		$SimilairesParam=self::buildParam(OMNI_TRAILER_PARAM);
		$SimilairesParam->Where = '!ISNULL(Sortie) AND Omnilogismes.ID<>' . intval($this->ID) .  ' AND Liens.Categorie IN(' . implode(',',$Items) . ')';
		$SimilairesParam->Order = 'COUNT(*) DESC,RAND()';
		$SimilairesParam->Limit = $Nb;

		return self::getTrailers($SimilairesParam);
	}







	//////////////////////////////////////////////
	//Partie statique
	//////////////////////////////

	/**
	* Permet de construire un objet SqlParam avec les options par défaut pour récupérer un omnilogisme.
	* @param Type:enum(OMNI_NORMAL_PARAM,OMNI_SMALL_PARAM,OMNI_VERY_SMALL_PARAM) Définit la quantité d'information à récupérer. Si SMALL, on ne prend que l'auteur, le titre et l'accroche.
	* @return :SqlParam un objet paramétré pour une récupération d'omnilogismes de base.
	*/
	public static function buildParam($Type=OMNI_TRAILER_PARAM)
	{
		$Param = new SqlParam();

		// à %T pour rétablir l'heure.
		if($Type==OMNI_TRAILER_PARAM)
			$Param->Select = 'ID, Titre, Accroche';
		elseif($Type==OMNI_TEASER_PARAM)
			$Param->Select = 'Omnilogismes.ID, Titre, Accroche, Auteurs.Auteur';
		elseif($Type==OMNI_SMALL_PARAM)
			$Param->Select = 'Omnilogismes.ID, Titre, Accroche, Auteurs.Auteur, DATE_FORMAT(Sortie, "%d/%m/%Y") as Date, UNIX_TIMESTAMP(Sortie) AS Timestamp, Message, Omnilogisme';
		elseif($Type==OMNI_FULL_PARAM)
			$Param->Select = 'Omnilogismes.ID, Omnilogismes.Statut, Titre, Accroche, Auteurs.Auteur, DATE_FORMAT(Sortie, "%d/%m/%Y") as Date, UNIX_TIMESTAMP(Sortie) AS Timestamp, Message, Omnilogisme, GROUP_CONCAT(Liens.Categorie) AS Categories';
		elseif($Type==OMNI_HUGE_PARAM)
			$Param->Select = 'Omnilogismes.ID, Omnilogismes.Statut, Omnilogismes.Titre, Omnilogismes.Accroche, Auteurs.Auteur, Auteurs.Adsense, Auteurs.GooglePlus, DATE_FORMAT(Omnilogismes.Sortie, "%d/%m/%Y") as Date, UNIX_TIMESTAMP(Omnilogismes.Sortie) AS Timestamp, Omnilogismes.Message, Omnilogismes.Omnilogisme, GROUP_CONCAT(Liens.Categorie) AS Categories, Suivant.Titre as TitreSuivant, Suivant.Accroche AS AccrocheSuivant, Precedent.Titre as TitrePrecedent, Precedent.Accroche AS AccrochePrecedent, Omnilogismes.Anecdote, Omnilogismes.SourceAnecdote';
		return $Param;
	}

	/**
	* Récupère une liste d'article satisfaisant les critères énoncés par l'objet SqlParam passé en paramètre.
	* @param Param:SqlParam les paramètres contrôlant les articles à renvoyer.
	* @return :array<Omni> un tableau d'article satisfaisant les conditions.
	*/
	public static function get(SqlParam &$Param)
	{
		//Calculer les tables à joindre :
		$Joins = array();
		$Champs = $Param->Select . $Param->Where;
		if(strpos($Champs,'Auteurs.')!==false)//Table des auteurs sur les omnilogismes
			$Joins[] = 'LEFT JOIN OMNI_Auteurs Auteurs ON (Auteurs.ID = Omnilogismes.Auteur)';
		if(strpos($Champs,'Liens.')!==false)
		{
			$Joins[] = 'LEFT JOIN OMNI_Liens Liens ON (Liens.News=Omnilogismes.ID)';
			$Param->Group = 'Omnilogismes.ID';
		}
		if(strpos($Champs,'Suivant.')!==false)
			$Joins[] = 'LEFT JOIN OMNI_Omnilogismes Suivant ON Omnilogismes.SuiviPar = Suivant.ID';
		if(strpos($Champs,'Precedent.')!==false)
			$Joins[] = 'LEFT JOIN OMNI_Omnilogismes Precedent ON Omnilogismes.ID = Precedent.SuiviPar';
		$SQL=
		$Param->getSelect() . '

		FROM OMNI_Omnilogismes Omnilogismes
		' . implode("\n",$Joins) . '

		' . $Param->getWhere() . '

		' . $Param->getGroup() . '

		' . $Param->getHaving() . '

		' . $Param->getOrder() . '
		' . $Param->getLimit();
		//echo "--------------\n" . $SQL . "-----------\n";
		//exit;
		$Articles = SQl::query($SQL);
		$Retour=array();

		while($Article=mysql_fetch_object($Articles,'Omni'))
			$Retour[] = $Article;

		return $Retour;
	}

	/**
	* Récupère un unique article satisfaisant les critères énoncés par l'objet SqlParam passé en paramètre.
	* @param Param:SqlParam les paramètres contrpolant les articles à renvoyer.
	* @return :Omni un tableau d'article satisfaisant les conditions.
	*/
	public static function getSingle(SqlParam &$Param)
	{
		$Article = Omni::get($Param);
		if(count($Article)==0)
			Debug::fail('Impossible de récupérer l\'article demandé.');
		return $Article[0];
	}

	/**
	* Récupère un unique article satisfaisant les critères énoncés par l'objet SqlParam passé en paramètre.
	* @param Param:SqlParam les paramètres contrpolant les articles à renvoyer.
	* @return :Omni un tableau d'article satisfaisant les conditions.
	*/
	public static function getSingleOrThrow(SqlParam &$Param)
	{
		$Article = Omni::get($Param);
		if(count($Article)==0)
			throw new Exception("Aucun article correspondant");
		return $Article[0];
	}

	/**
	* Récupère une liste d'article satisfaisant les critères énoncés par l'objet SqlParam passé en paramètre, et renvoie une liste affichable.
	* @param Param:SqlParam les paramètres controlant les articles à renvoyer, normalement construit avec OMNI_TRAILER_PARAM
	* @return :array un tableau de trailer.
	*/
	public static function getTrailers(SqlParam $Param)
	{
		$Articles=self::get($Param);
		foreach($Articles as &$Article)
			$Article = $Article->outputTrailer();//Transtypage tout moche de "Omni" vers "String".
		return $Articles;
	}
}

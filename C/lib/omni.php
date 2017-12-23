<?php
/**
* But : interagir facilement avec les articles.
* Un des principaux contr�leurs.
*/
//Omni

//////////////////////////////////////////////////////
//Fonctionnalit�s du contr�leur :



//Cette classe est construite directement par mysql_fetch_object, et d�roge en cons�quence aux r�gles de nommage des variables en anglais.
class Omni
{
	//Uniquement le titre et l'accroche (et l'ID).
	const TRAILER_PARAM = 0;

	//Minimiser la r�cup�ration d'information, et les jointures effectu�es sur la requ�te.
	const TEASER_PARAM = 1;

	//Normal : r�cup�rer les infos pour l'affichage, et faire les jointures sur l'auteur. Pas les cat�gories.
	const SMALL_PARAM = 2;

	//Maximiser les infos r�cup�r�es, pour l'affichage complet ; par exemple dans le flux RSS. Ajoute les cat�gories
	const FULL_PARAM = 3;

	//Maximiser les infos r�cup�r�es, pour l'affichage complet. Ajoute l'article suivant et les derni�res modifications.
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
	public $Mots;		//Les mots-cl�s asosci�s � l'article
	public $Categories;	//Les cat�gories DE PREMIER NIVEAU, pas l'arbre.
	public $TitreSuivant;	//L'article qui suit celui-l�
	public $AccrocheSuivant;	//L'article qui suit celui-l�
	public $TitrePrecedent;	//Et celui qui le pr�c�de.
	public $AccrochePrecedent;	//Et celui qui le pr�c�de.
	public $NbVues;
	public $Anecdote;
	public $SourceAnecdote;

	/**
	* Enregistre une modification sur l'article.
	* @param Action:String L'action � enregistrer, e.g. "Correction des erreurs". Th�oriquement (mais pas obligatoire) une constante statique sur Event::
	* @param Svg:String cette modification n�cessite-t-elle de logger le contenu pour comparaisons ult�rieures ?
	* @param $Author:int l'auteur qui a effectu� cette modification. Si non d�fini, c'est l'auteur actuellement connect� qui est choisi. Si non d�fini et que personne n'est connect�, la page s'arr�te.
	*/
	public function registerModif($Action,$Svg=false,$Author=-1)
	{
		if($Author==-1)
		{
			if(!isset($_SESSION['Membre']['ID']))
				Debug::fail('Impossible d\'enregistrer une action effectu�e par un membre non connect� !');
			$Author = $_SESSION['Membre']['ID'];
		}

		$Modifs=array(
			'Auteur'=>$Author,
			'Reference'=>$this->ID,
			'Modification'=>addslashes($Action),
			'_Date'=>'NOW()'
			);

		if($Svg)
			$Modifs['_Sauvegarde']='(SELECT Omnilogisme FROM OMNI_Omnilogismes WHERE ID=' . $this->ID . ')';

		if(!SQL::insert('OMNI_Modifs',$Modifs))
			Debug::fail(mysql_error());

		//Appeler les listeners associ�s :
		Event::dispatch($Action,$this);
		Event::dispatch(Event::CHANGEMENT_GENERIQUE,$this);
	}

	/**
	* Renvoie la liste des sources utilis�es (si il y en a) dans une liste.
	* @return :array une liste des sources, sous la forme URL=>Titre
	*/
	public function getURLs()
	{
		static $Replacements=array('&'=>'&amp;');
		static $SpecialsChars=array('é'=>'�','&'=>'&amp;','è'=>'�','â'=>'�','�'=>'�');

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
	* Renvoie une image HTML repr�sentant la banni�re de l'article.
	* Si la banni�re n'existe pas, une image g�n�rique est renvoy�e.
	* @param Type:(BIG_BANNER|THUMB_BANNER) r�cup�rer l'URL de la banni�re, ou de la miniature. BIG_BANNER par d�faut.
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
	* Renvoie la liste des cat�gories.
	* @return :array
	*/
	public function getCategories()
	{
		return explode(',',$this->Categories);
	}

	/**
	* Renvoie l'accroche de l'article mise en forme, pour �viter par exemple d'avoir un "?" qui se balade en d�but de ligne !
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
	* Les pr�fixes inutiles comme "Le", "La", "Les", "L'" sont mis en petit
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
	* Met � jour le nombre de vues de l'article aussi.
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
	* @return :String le d�but de l'article mis en forme.
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
	* @param Style:enum(NORMAL_HEADER,SMALL_HEADER) Permet de contr�ler le nombre d'informations � afficher.
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
		if(strlen($this->GooglePlus) > 0)
			$Retour .= '<small><a href="' . $this->GooglePlus . '?rel=author">g+</a></small> ';

		if($this->Date!='')
			$Retour .='le <time datetime="' . date('Y-m-d',$this->Timestamp) . '" pubdate="pubdate">' . $this->Date . '</time>';
		else if($this->Statut == 'DEJA_TRAITE')
			$Retour .='<span class="unpublished">[un article similaire est d�j� paru, celui-ci ne sera donc pas publi�]</span>';
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
	* Utilise DOMDocument pour r�cup�rer le texte du document HTML pars�.
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
	* Renvoie une liste de $Nb articles au contenu similaire qui peuvent int�resser le lecteur
	* @param Nb:int le nombre d'article similaires � afficher, OMNI_NB_SIMILAR par d�faut.
	* @return :SQLParam un objet permettant de r�cup�rer les articles similaires.
	*/
	public function outputSimilar($Nb=OMNI_NB_SIMILAR)
	{
		//Article pas encore classifi�.
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
	* Permet de construire un objet SqlParam avec les options par d�faut pour r�cup�rer un omnilogisme.
	* @param Type:enum(OMNI_NORMAL_PARAM,OMNI_SMALL_PARAM,OMNI_VERY_SMALL_PARAM) D�finit la quantit� d'information � r�cup�rer. Si SMALL, on ne prend que l'auteur, le titre et l'accroche.
	* @return :SqlParam un objet param�tr� pour une r�cup�ration d'omnilogismes de base.
	*/
	public static function buildParam($Type=OMNI_TRAILER_PARAM)
	{
		$Param = new SqlParam();

		// � %T pour r�tablir l'heure.
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
	* R�cup�re une liste d'article satisfaisant les crit�res �nonc�s par l'objet SqlParam pass� en param�tre.
	* @param Param:SqlParam les param�tres contr�lant les articles � renvoyer.
	* @return :array<Omni> un tableau d'article satisfaisant les conditions.
	*/
	public static function get(SqlParam &$Param)
	{
		//Calculer les tables � joindre :
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
	* R�cup�re un unique article satisfaisant les crit�res �nonc�s par l'objet SqlParam pass� en param�tre.
	* @param Param:SqlParam les param�tres contrpolant les articles � renvoyer.
	* @return :Omni un tableau d'article satisfaisant les conditions.
	*/
	public static function getSingle(SqlParam &$Param)
	{
		$Article = Omni::get($Param);
		if(count($Article)==0)
			Debug::fail('Impossible de r�cup�rer l\'article demand�.');
		return $Article[0];
	}

	/**
	* R�cup�re un unique article satisfaisant les crit�res �nonc�s par l'objet SqlParam pass� en param�tre.
	* @param Param:SqlParam les param�tres contrpolant les articles � renvoyer.
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
	* R�cup�re une liste d'article satisfaisant les crit�res �nonc�s par l'objet SqlParam pass� en param�tre, et renvoie une liste affichable.
	* @param Param:SqlParam les param�tres controlant les articles � renvoyer, normalement construit avec OMNI_TRAILER_PARAM
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
&<?php
/**
* But : offrir des primitives pour la manipulation des différents catégories et des arbres associés.
*
*/
//Category

class Category
{
	private static $Datas=null;

	/**
	* Construire l'arbre des catégories pour une utilisation ultérieure
	* Fonction appelée automatiquement au premier appel d'une méthode de la classe.
	*/
	private static function buildInitialTree()
	{
		if(is_null(self::$Datas))
			self::$Datas = unserialize(file_get_contents(DATA_PATH . '/categories'));
	}

	/**
	* Fonction permettant de savoir si une catégorie existe. Renvoie true si la catégorie existe, false sinon
	* @param Category:String la catégorie à tester
	* @return :bool true si la catégorie existe.
	*/
	public static function exists($Category)
	{
		self::buildInitialTree();
		return isset(self::$Datas[$Category]);
	}

	/**
	* Échappe l'élément pour pouvoir être utilisé en BDD sans problème.
	* @param Category:String l'élément à échapper
	* @return :String la catégorie échappée
	*/
	public static function escape($Category)
	{
		return addslashes($Cat);
	}

	/**
	* Échappe l'élément pour pouvoir être utilisé en BDD sans problème, ajoute des guillemets autour.
	* @param Category:String l'élément à échapper
	* @return :String la catégorie échappée
	*/
	public static function escapeAndQuote($Category)
	{
		return '"' . addslashes($Category) . '"';
	}

	/**
	* Construit un arbre entourant les catégories fournies en paramètres.
	* @param Categories:array une liste de mots clés autour desquels on construira l'arbre.
	*/
	public static function getTree(array $Categories)
	{
		self::buildInitialTree();

		$KeyWords=array();
		foreach($Categories as $Category)
			self::getTreeR($Category,$KeyWords);
		return $KeyWords;
	}

	/**
	* Transformer le tableau PHP en tableau affichable en HTML.
	* Il s'agit d'un tableau HTML assez complexe avec de multiples rowspan.
	* @param Table:array Le tableau à transformer.
	* @return :String le tableau HTML.
	*/
	public static function outputTree(array $Table)
	{
		$Lignes=self::linear(array(),$Table,0,0);
		if(count($Lignes)==0)
			return;

		$Table=array_fill(0,10,array());//10 est une constante arbitraire définissant l'indentation maximale de l'arbre.
		$NbColonnes=count($Lignes);
		$NbLignes=1;

		$Parent=array();

		$LastParent=-1;
		foreach($Lignes as $ID=>$Ligne)
		{
			$Start=0;
			foreach($Ligne as &$Item)
			{
				if(isset($Parent[$Item['P']]) && $Item['P']!=$LastParent)
				{
					$LastParent=$Item['P'];
					$Start=$Parent[$Item['P']]['S'];
				}

				$Item['N']=self::getMaxSiblings($Item['ID'],$Lignes);//Calculer "l'épaisseur"
				$Item['S']=$Start;
				$Table[$ID][$Start]=$Item;//Remplir le tableau

				$Start +=$Item['N'];//Ou doit commencer l'affchage du prochain item de cette colonne ?


				$NbLignes=max($NbLignes,$Start);

				$Parent[$Item['ID']]=$Item;//enregistrer les infos dans un tableau linéaire en fonction de l'ID pour plus de simplicité.
			}
		}

		$retour= '<table>' . "\n";

		for($j=0;$j<$NbLignes;$j++)
		{
			$retour .='	<tr>' . "\n";
			for($i=0;$i<$NbColonnes;$i++)
			{
				if(isset($Table[$i][$j]) && $Table[$i][$j]!='NIL')
				{
					$RS=$Table[$i][$j]['N'];

					if($RS==1)
						$retour .='		<td>';
					else
						$retour .='		<td rowspan="' . $RS . '">';

					$retour .=Anchor::category($Table[$i][$j]['I']) . '</td>' . "\n";
				}
			}
			$retour .='	</tr>' . "\n";
		}
		$retour .='</table>';
		return $retour;
	}

	/**
	* Renvoie la liste des mots clés contenus dans le tableau $Categorie, et place le résultat dans $Items.
	* NOTE : Les catégories sont échappées pour être utilisées dans une requête SQL.
	* @param Categorie:array Un tableau tel que renvoyé par  getTree()
	* @param Items:array le tableau final.
	*/
	public static function getUnidimensional(array $Categories,array &$Items)
	{
		foreach($Categories as $Nom=>$Arbre)
		{
			if(!isset($Items[$Nom]))
				$Items[$Nom]=self::escapeAndQuote(addslashes($Nom));

			if(is_array($Arbre))
				self::getUnidimensional($Arbre,$Items);
		}
	}

	/**
	* Renvoie les enfants de $Category.
	* @param Parent:String la catégorie à analyser
	* @return :array Un tableau avec tous les enfants.
	*/
	public static function getSiblings($Category)
	{
		return array_unique(self::getSiblingsR($Category));
	}

	/**
	* Récupérer les derniers articles d'une catégorie.
	* Ne récupère que le titre des articles, avec l'accroche.
	* @param Categorie:String la catégorie à rechercher. Contrainte : doit être une catégorie de bas niveau !
	* @param Nb:int le nombre d'article à récupérer, SAGA_NB_SHOW par défaut.
	* @return :array<Omni> un tableau de Nb articles maximum.
	*/
	public static function getOmni($Category,$Nb=SAGA_NB_SHOW)
	{
		$Param = Omni::buildParam(OMNI_TRAILER_PARAM);
		$Param->Where = 'Liens.Categorie="' . addslashes($Category) . '" AND Statut="ACCEPTE" AND !ISNULL(Sortie)';
		$Param->Limit = $Nb;
		$Param->Order = 'Omnilogismes.ID DESC';

		return Omni::getTrailers($Param);
	}

	/**
	* Construire les menus spéciaux de catégorie pour les sagas.
	* fonction récursive supportant les imbrications de sagas.
	* Utilisé pour : les articles dans une saga, l'affichage d'une catégorie qui est une saga.
	* @param Arbre:array L'arbre de départ, tel que retourné par Category::getTree
	* @return :void Remplit le tableau $C.
	*/
	public static function buildSaga(array $Arbre)
	{
		global $C;

		$MaxParSaga = 10;

		foreach($Arbre as $Saga=>$Child)
		{
			if(!Cache::exists('Sagas',md5($Saga)))
			{
				$Articles=Category::getOmni($Saga,$MaxParSaga);
				$Content=Formatting::makeList($Articles);
				if(count($Articles)==$MaxParSaga)
					$Content .='<span class="more-in-saga">Plus ? ' . Anchor::category($Saga) . '</span>';
				Cache::set('Sagas',md5($Saga),$Content);
			}

			prependPod('saga-' . count($C['Pods']),$Saga,Cache::get('Sagas',md5($Saga)));

			//Appel récursif.
			if(is_array($Child))
				self::buildSaga($Child);
		}
	}

	/**
	* Met toutes les clés de premier niveau dans un même tableau, puis toutes les clés de second niveau, et ainsi de suite...
	* Puis renvoie le tableau global. Ajoute des méta-informations pour pouvoir s'y retrouver :)
	* Fonction récursive (forcément !)
	* @param Tab:array le tableau des calculs précédents.
	* @param Origine:array Le tableau de départ, trié selon l'ordre logique.
	* @param Profondeur:int La profondeur du moment. 0 au début, puis +1 à chaque appel.
	* @param Parent:int L'identifiant du parent.
	* @return :Array Un tableau trié par profondeur.
	*/
	private static function linear(array $Tab,array &$Origine,$Profondeur,&$Parent)
	{
		$NewParent=$Parent;
		foreach($Origine as $Item=>$CurLigne)
		{
			$NewParent++;
			$Tab[$Profondeur][]=array('I'=>$Item,'P'=>$Parent,'ID'=>$NewParent);
			if(is_array($CurLigne))
				$Tab=self::linear($Tab,$CurLigne,$Profondeur+1,$NewParent);
		}
		$Parent=$NewParent;
		return $Tab;
	}

	/**
	* Renvoie la colonne avec le plus d'élements ayant comme parent $ID
	* @param ID:int L'identifiant de la colonne pour laquelle on fait la requête.
	* @param $Tab:array Le sous tableau à analyser.
	*/
	private static function getMaxSiblings($ID,array $Tab)
	{
		$max=1;

		foreach($Tab as $P=>$subTab)
		{
			$NbSiblings=0;
			foreach($subTab as $Item)
			{
				if($Item['P']==$ID)
					$NbSiblings +=max(1,self::getMaxSiblings($Item['ID'],array_slice($Tab,$P)));
			}
			$max=max($max,$NbSiblings);
		}
		return $max;
	}

	/**
	* Construit étape par étape l'arbre, en ajoutant $Category et sa descendance au tableau déjà formé dans $Base.
	* Fonction récursive.
	* @param Category:String la catégorie à ajouter à l'arbre Base
	* @param Base:array le tableau déjà crée.
	* @return :array Le tableau final
	*/
	private static function getTreeR($Category,array &$Base)
	{
		//Catégorie vide, rien à faire.
		if($Category=='')
			return $Base;

		//Préparation de l'algorithme
		$A_Examiner=array();
		$Depart=&$Base;

		//S'il s'agit d'une catégorie maitresse (du type Science, Au quotidien) qui n'a pas de parents.
		if(self::$Datas[$Category]==array() && !isset($Base[$Category]))
			$Base[$Category]=1;

		foreach(self::$Datas[$Category] as $Key)
		{
			foreach($Key as $parent)
			{
				if(!isset($Base[$parent]) || $Base[$parent]==1 )
					$Base[$parent]=array();
				if(count(self::$Datas[$parent])>1)
					$A_Examiner[]=$parent;

				$Base=&$Base[$parent];
			}
			if(!isset($Base[$Category]))
				$Base[$Category]=true;
			$Base=&$Depart;
		}

		foreach($A_Examiner as $HeritageMultiple)
			$Depart=self::getTreeR($HeritageMultiple,$Depart);
		return $Depart;
	}

	/**
	* Récupère les enfants de $Siblings récursivement.
	* @param Parent:String la catégorie à analyser
	* @param Base:array le tableau construit jusqu'à présent.
	*/
	private static function getSiblingsR($Parent,array $Base=array())
	{
		$Base[]=$Parent;
		foreach(self::$Datas as $Category=>$Siblings)
		{
			foreach($Siblings as $Alias)
			{
				if(in_array($Parent,$Alias))//$Category est un enfant de $Parent
					$Base=self::getSiblingsR($Category,$Base);//Relancer la recherche sur l'enfant
			}
		}
		return $Base;
	}
}

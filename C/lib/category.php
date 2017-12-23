&<?php
/**
* But : offrir des primitives pour la manipulation des diff�rents cat�gories et des arbres associ�s.
*
*/
//Category

class Category
{
	private static $Datas=null;

	/**
	* Construire l'arbre des cat�gories pour une utilisation ult�rieure
	* Fonction appel�e automatiquement au premier appel d'une m�thode de la classe.
	*/
	private static function buildInitialTree()
	{
		if(is_null(self::$Datas))
			self::$Datas = unserialize(file_get_contents(DATA_PATH . '/categories'));
	}

	/**
	* Fonction permettant de savoir si une cat�gorie existe. Renvoie true si la cat�gorie existe, false sinon
	* @param Category:String la cat�gorie � tester
	* @return :bool true si la cat�gorie existe.
	*/
	public static function exists($Category)
	{
		self::buildInitialTree();
		return isset(self::$Datas[$Category]);
	}

	/**
	* �chappe l'�l�ment pour pouvoir �tre utilis� en BDD sans probl�me.
	* @param Category:String l'�l�ment � �chapper
	* @return :String la cat�gorie �chapp�e
	*/
	public static function escape($Category)
	{
		return addslashes($Cat);
	}

	/**
	* �chappe l'�l�ment pour pouvoir �tre utilis� en BDD sans probl�me, ajoute des guillemets autour.
	* @param Category:String l'�l�ment � �chapper
	* @return :String la cat�gorie �chapp�e
	*/
	public static function escapeAndQuote($Category)
	{
		return '"' . addslashes($Category) . '"';
	}

	/**
	* Construit un arbre entourant les cat�gories fournies en param�tres.
	* @param Categories:array une liste de mots cl�s autour desquels on construira l'arbre.
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
	* @param Table:array Le tableau � transformer.
	* @return :String le tableau HTML.
	*/
	public static function outputTree(array $Table)
	{
		$Lignes=self::linear(array(),$Table,0,0);
		if(count($Lignes)==0)
			return;

		$Table=array_fill(0,10,array());//10 est une constante arbitraire d�finissant l'indentation maximale de l'arbre.
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

				$Item['N']=self::getMaxSiblings($Item['ID'],$Lignes);//Calculer "l'�paisseur"
				$Item['S']=$Start;
				$Table[$ID][$Start]=$Item;//Remplir le tableau

				$Start +=$Item['N'];//Ou doit commencer l'affchage du prochain item de cette colonne ?


				$NbLignes=max($NbLignes,$Start);

				$Parent[$Item['ID']]=$Item;//enregistrer les infos dans un tableau lin�aire en fonction de l'ID pour plus de simplicit�.
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
	* Renvoie la liste des mots cl�s contenus dans le tableau $Categorie, et place le r�sultat dans $Items.
	* NOTE : Les cat�gories sont �chapp�es pour �tre utilis�es dans une requ�te SQL.
	* @param Categorie:array Un tableau tel que renvoy� par  getTree()
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
	* @param Parent:String la cat�gorie � analyser
	* @return :array Un tableau avec tous les enfants.
	*/
	public static function getSiblings($Category)
	{
		return array_unique(self::getSiblingsR($Category));
	}

	/**
	* R�cup�rer les derniers articles d'une cat�gorie.
	* Ne r�cup�re que le titre des articles, avec l'accroche.
	* @param Categorie:String la cat�gorie � rechercher. Contrainte : doit �tre une cat�gorie de bas niveau !
	* @param Nb:int le nombre d'article � r�cup�rer, SAGA_NB_SHOW par d�faut.
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
	* Construire les menus sp�ciaux de cat�gorie pour les sagas.
	* fonction r�cursive supportant les imbrications de sagas.
	* Utilis� pour : les articles dans une saga, l'affichage d'une cat�gorie qui est une saga.
	* @param Arbre:array L'arbre de d�part, tel que retourn� par Category::getTree
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

			//Appel r�cursif.
			if(is_array($Child))
				self::buildSaga($Child);
		}
	}

	/**
	* Met toutes les cl�s de premier niveau dans un m�me tableau, puis toutes les cl�s de second niveau, et ainsi de suite...
	* Puis renvoie le tableau global. Ajoute des m�ta-informations pour pouvoir s'y retrouver :)
	* Fonction r�cursive (forc�ment !)
	* @param Tab:array le tableau des calculs pr�c�dents.
	* @param Origine:array Le tableau de d�part, tri� selon l'ordre logique.
	* @param Profondeur:int La profondeur du moment. 0 au d�but, puis +1 � chaque appel.
	* @param Parent:int L'identifiant du parent.
	* @return :Array Un tableau tri� par profondeur.
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
	* Renvoie la colonne avec le plus d'�lements ayant comme parent $ID
	* @param ID:int L'identifiant de la colonne pour laquelle on fait la requ�te.
	* @param $Tab:array Le sous tableau � analyser.
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
	* Construit �tape par �tape l'arbre, en ajoutant $Category et sa descendance au tableau d�j� form� dans $Base.
	* Fonction r�cursive.
	* @param Category:String la cat�gorie � ajouter � l'arbre Base
	* @param Base:array le tableau d�j� cr�e.
	* @return :array Le tableau final
	*/
	private static function getTreeR($Category,array &$Base)
	{
		//Cat�gorie vide, rien � faire.
		if($Category=='')
			return $Base;

		//Pr�paration de l'algorithme
		$A_Examiner=array();
		$Depart=&$Base;

		//S'il s'agit d'une cat�gorie maitresse (du type Science, Au quotidien) qui n'a pas de parents.
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
	* R�cup�re les enfants de $Siblings r�cursivement.
	* @param Parent:String la cat�gorie � analyser
	* @param Base:array le tableau construit jusqu'� pr�sent.
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

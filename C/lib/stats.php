<?php
/**
* But : Faire des statistiques sur un échantillon de données.
*
*/
//
define('STATS_ADD_OTHER_COLUMN',true);
define('STATS_NO_OTHER_COLUMN',false);
define('STATS_LIMIT_AT',11);
//////////////////////////////////////////////////////
//Fonctionnalités du contrôleur :

class Stats
{
	/**
	* Effectue la requête SQL demandée et renvoie le résultat dans la phrase Code.
	* @param SQL:String La requête
	* @param Code:String la phrase template
	* @return :String la phrase avec les valeurs.
	* @example
	* //Renverra Nombre d'articles : 448 vus 258 789 fois.
	* StatIt('SELECT COUNT(*) AS Somme, SUM(NbVues) AS Vues FROM Articles','Nombre d'articles : $Somme vus $Vues fois');
	*/
	public static function It($SQL,$Code)
	{
		$D=SQL::singleQuery($SQL);
		foreach($D as &$A)
		{
			if(is_numeric($A))
				$A = Formatting::makeNumber($A);
		}

		return str_replace(array_map("Stats::Dollar",array_keys($D)),array_values($D),$Code);
	}

	/**
	* Effectue la requête SQL, puis affiche dans une liste chacune des lignes mise en forme selon $Code.
	* @param SQL:String la requête.
	* @param Code:String le template à utiliser pour chaque ligne (voir l'exemple de StatIt)
	* @param Autres:String Valeur numérique qui sert pour le ROLLUP (dernière ligne, "autres")
	* @param Default:String template à utiliser pour le total autres. Si nul, toutes les lignes sont affichées.
	*/
	public static function Its($SQL,$Code,$Autres='',$Default='')
	{
		//Si $Default valent '', toutes les données sont affichée.
		//Sinon, on récupère les LIMIT_AT premiers enregistrements, puis on fait la somme des restants que l'on ajoute dans une ligne 'Autres'.
		$Ds=SQL::query($SQL) or die(mysql_error());
		$Items=array();
		$Rollup=0;
		while($D=mysql_fetch_assoc($Ds))
		{
			if($Default=='' || count($Items)<STATS_LIMIT_AT)
			{
				if($Autres!='')
					$D[$Autres]=Formatting::makeNumber($D[$Autres]);
				$Items[]=str_replace(array_map("Stats::Dollar",array_keys($D)),array_values($D),$Code) . '&nbsp;;';//Insérer dans la liste
			}
			else
				$Rollup +=$D[$Autres];//Faire la somme
		}
		if($Default!='')
			$Items[]=str_replace('$' . $Autres,Formatting::makeNumber($Rollup),$Default) . '.';//Insérer le dernier item dans la liste.
		return Formatting::makeList($Items);
	}

	/**
	* Permet d'afficher un graphique représentant les données renvoyées par SQL.
	* @param SQL:String La requête pour récupérer les données
	* @param Settings:array Un tableau d'options paramètrant le graphique
	* @param Autres:enum(STATS_ADD_OTHER_COLUMN,...) Faut-il ajouter une colonne "Autres" contenant le reste des enregistrements ?
	*/
	public static function GraphIt($SQL,array $Settings,$Autres=STATS_ADD_OTHER_COLUMN)
	{
		return '<p class="centre"><img class="nonflottant" src="' . self::GraphItUrl($SQL, $Settings, $Autres) . '" onmouseover="this.src=this.src.replace(\'cht=p3\',\'cht=p\');" " onmouseout="this.src=this.src.replace(\'cht=p\',\'cht=p3\');"/></p>';

	}

	public static function GraphItUrl($SQL,array $Settings,$Autres=STATS_ADD_OTHER_COLUMN)
	{
		//Si $Autres vaut ADD_OTHER_COLUMN, on ne prend que les LIMIT_AT premiers enregistrements et on ajoute une colonne "Autres".
		$Encodage=str_split('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789');
		//Valeurs par défaut
		$Default=array(
		'cht'=>'lc',
		'chtt'=>'Statistiques',
		'chs'=>'700x200',
		'chco'=>'88AAD6',
		);
		//Combiner les valeurs demandées avec les valeurs par défaut
		$Settings=array_merge($Default,$Settings);

		$Ds=mysql_query($SQL) or die(mysql_error());
		$Tab=array();
		$Abscisses=array();

		$Rollup=0;
		while($D=mysql_fetch_assoc($Ds))
		{
			if(!$Autres || count($Tab)<STATS_LIMIT_AT)
			{
				$Tab[]=$D['Ordonnee'];
				$Abscisses[]=$D['Abscisse'];//str_replace(str_split('éèêàù'),str_split('eeeau'),$D['Abscisse']);
			}
			else
				$Rollup +=$D['Ordonnee'];
		}

		if($Autres)
		{
			$Tab[]=$Rollup;
			$Abscisses[]='Autres...';
		}
		//Normaliser le tableau

		$Max=self::Normalize($Tab);

		//Bug Google API : les histogrammes horizontaux doivent voir les labels inversés.
		if($Settings['cht'] == 'bhs')
		{
			$Abscisses = array_reverse($Abscisses);
		}

		$Abscisses=mb_convert_encoding(implode('|',$Abscisses), 'UTF-8', 'ISO-8859-1');

		//Rentrer les données
		$Settings['chd']='s:';
		foreach($Tab as $Cell)
			$Settings['chd'] .=$Encodage[$Cell];

		//Générer l'URL à partir des Settings
		$URL='//chart.apis.google.com/chart?1';
		foreach($Settings as $K=>$V)
		{
			if($V!='')
				$URL .='&' . $K . '=' . str_replace('$ABSCISSES',$Abscisses,str_replace('$MAX',$Max,$V));
		}

		return $URL;
	}

	/**
	* Ajoute un $ devant la variable passée en paramètre.
	* @param Texte:String la variable à préfixer
	* @return :String la variable préfixée.
	*/
	private static function Dollar($Texte)
	{
		return '$' . $Texte;
	}


	/**
	* Normalise le tableau passé en paramètre pour avoir des valeurs entre 0 et $Max
	* @param Tab:array le tableau à normaliser, qui sera modifié par référence. Par défaut 61, le nombre de caractères disponibles pour l'encodage.
	* @param Max:int la valeur maximum.
	*/
	private static function Normalize(array &$Tab,$Max=61)
	{
		if(empty($Tab))
		{
			exit('Impossible de générer des statistiques, aucune donnée disponible.');
		}

		$MaxTab=max(1,max($Tab));
		foreach($Tab as &$Cell)
			$Cell=round($Max*$Cell/$MaxTab);

		return $MaxTab;
	}
}

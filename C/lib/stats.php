<?php
/**
* But : Faire des statistiques sur un �chantillon de donn�es.
*
*/
//
define('STATS_ADD_OTHER_COLUMN',true);
define('STATS_NO_OTHER_COLUMN',false);
define('STATS_LIMIT_AT',11);
//////////////////////////////////////////////////////
//Fonctionnalit�s du contr�leur :

class Stats
{
	/**
	* Effectue la requ�te SQL demand�e et renvoie le r�sultat dans la phrase Code.
	* @param SQL:String La requ�te
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
	* Effectue la requ�te SQL, puis affiche dans une liste chacune des lignes mise en forme selon $Code.
	* @param SQL:String la requ�te.
	* @param Code:String le template � utiliser pour chaque ligne (voir l'exemple de StatIt)
	* @param Autres:String Valeur num�rique qui sert pour le ROLLUP (derni�re ligne, "autres")
	* @param Default:String template � utiliser pour le total autres. Si nul, toutes les lignes sont affich�es.
	*/
	public static function Its($SQL,$Code,$Autres='',$Default='')
	{
		//Si $Default valent '', toutes les donn�es sont affich�e.
		//Sinon, on r�cup�re les LIMIT_AT premiers enregistrements, puis on fait la somme des restants que l'on ajoute dans une ligne 'Autres'.
		$Ds=SQL::query($SQL) or die(mysql_error());
		$Items=array();
		$Rollup=0;
		while($D=mysql_fetch_assoc($Ds))
		{
			if($Default=='' || count($Items)<STATS_LIMIT_AT)
			{
				if($Autres!='')
					$D[$Autres]=Formatting::makeNumber($D[$Autres]);
				$Items[]=str_replace(array_map("Stats::Dollar",array_keys($D)),array_values($D),$Code) . '&nbsp;;';//Ins�rer dans la liste
			}
			else
				$Rollup +=$D[$Autres];//Faire la somme
		}
		if($Default!='')
			$Items[]=str_replace('$' . $Autres,Formatting::makeNumber($Rollup),$Default) . '.';//Ins�rer le dernier item dans la liste.
		return Formatting::makeList($Items);
	}

	/**
	* Permet d'afficher un graphique repr�sentant les donn�es renvoy�es par SQL.
	* @param SQL:String La requ�te pour r�cup�rer les donn�es
	* @param Settings:array Un tableau d'options param�trant le graphique
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
		//Valeurs par d�faut
		$Default=array(
		'cht'=>'lc',
		'chtt'=>'Statistiques',
		'chs'=>'700x200',
		'chco'=>'88AAD6',
		);
		//Combiner les valeurs demand�es avec les valeurs par d�faut
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
				$Abscisses[]=$D['Abscisse'];//str_replace(str_split('�����'),str_split('eeeau'),$D['Abscisse']);
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

		//Bug Google API : les histogrammes horizontaux doivent voir les labels invers�s.
		if($Settings['cht'] == 'bhs')
		{
			$Abscisses = array_reverse($Abscisses);
		}

		$Abscisses=utf8_encode(implode('|',$Abscisses));

		//Rentrer les donn�es
		$Settings['chd']='s:';
		foreach($Tab as $Cell)
			$Settings['chd'] .=$Encodage[$Cell];

		//G�n�rer l'URL � partir des Settings
		$URL='http://chart.apis.google.com/chart?1';
		foreach($Settings as $K=>$V)
		{
			if($V!='')
				$URL .='&' . $K . '=' . str_replace('$ABSCISSES',$Abscisses,str_replace('$MAX',$Max,$V));
		}

		return $URL;
	}

	/**
	* Ajoute un $ devant la variable pass�e en param�tre.
	* @param Texte:String la variable � pr�fixer
	* @return :String la variable pr�fix�e.
	*/
	private static function Dollar($Texte)
	{
		return '$' . $Texte;
	}


	/**
	* Normalise le tableau pass� en param�tre pour avoir des valeurs entre 0 et $Max
	* @param Tab:array le tableau � normaliser, qui sera modifi� par r�f�rence. Par d�faut 61, le nombre de caract�res disponibles pour l'encodage.
	* @param Max:int la valeur maximum.
	*/
	private static function Normalize(array &$Tab,$Max=61)
	{
		if(empty($Tab))
		{
			exit('Impossible de g�n�rer des statistiques, aucune donn�e disponible.');
		}

		$MaxTab=max(1,max($Tab));
		foreach($Tab as &$Cell)
			$Cell=round($Max*$Cell/$MaxTab);

		return $MaxTab;
	}
}
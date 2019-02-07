<?php
/**
* But : offrir des facilités de formatage HTML
*
*/
//Formatting

//////////////////////////////////////////////////////
//Fonctionnalités du contrôleur :

class Formatting
{
	/**
	* Formater correctement un nombre : 123456 => 123 456
	* @param Nb:int le nombre
	* @return :String le nombre mis en forme.
	* @example
	* echo Formatting::makeNumber(250000);
	* //Renverra 250 000 avec un espace insécable.
	*/
	public static function makeNumber($Nb)
	{
		return str_replace('@','&nbsp;',number_format($Nb,0,',','@'));
	}

	/**
	* Créer une liste HTML à partir d'un tableau
	* @param List:Array La liste à convertir en tableau
	* @param Type:String Le type de la liste ; valeurs valide : ol, ul ; 'ul' par défaut.
	* @return :String une liste HTML
	*/
	public static function makeList(array $List, $Type='ul',$Class='',$Id='')
	{
		$R = '<' . $Type . ($Class!=''?' class="' . $Class . '"':'') . ($Id!=''?' id="' . $Id . '"':'') . '>' . "\n";
		foreach($List as $Item)
		{
			$R .= '	<li>' . $Item . "</li>\n";
		}
		$R .= '</' . $Type . ">\n";

		return $R;
	}

	/**
	* Gère la pagination de toutes les pages devant afficher plus de OMNI_MAX_PAGE articles par page, sous forme de Teaser.
	* @param Param:SqlParam Les paramètres pour récupérer l'article ; ils seront modifiés au niveau du LIMIT pour ne récupérer que la partie intéressante.
	* @param URL:String le masque de l'URL à utiliser. Exemple : /Omnilogistes/Neamar/ (<-- notez le slash à la fin !) qui deviendra /Omnilogistes/Neamar/Page-2 (Page-1 renvoie directement vers l'URL de base).
	* @param Page:int Lu directement depuis la variable $_GET['Page'].
	* @return :array La fonction travaille directement avec $C : $C['Articles'] contient un tableau de Teaser, et $C['Pager'] contient la structure de contrôle si elle est nécessaire. Elle renvoie aussi le tableau des articles sélectionnés.
	*/
	public static function makePage(SqlParam $Param,$URL=null)
	{
		global $C;

		//Contrôle de l'urldecode
		if(is_null($URL))
			Debug::fail('Le paramètre URL ne doit pas être null');

		//Lecture du numéro de page demandé.
		if(!isset($_GET['Page']))
			$Page = 0;
		else
			$Page = intval($_GET['Page']) - 1;

		if($Page==-1)
			Debug::fail('Désolé, la consultation des articles en avance sur le temps est actuellement interdite par la relativité restreinte ; merci de réessayer plus tard.');

		//Modification du $Param :
		$Param->Select = 'SQL_CALC_FOUND_ROWS ' . $Param->Select;
		$Param->Limit = ($Page * OMNI_MAX_PAGE) . ',' . OMNI_MAX_PAGE;

		//Récupérer les articles
		$Articles = Omni::get($Param);
		foreach($Articles as &$Article)
		{
			$C['Articles'][$Article->ID]['Teaser'] = $Article->outputTeaser();
		}

		//Faut-il afficher un module de contrôle pour la pagination ?
		$Nb = SQL::singleQuery('SELECT FOUND_ROWS( ) AS Total');

		if($Page * OMNI_MAX_PAGE>$Nb['Total'])
			Debug::redirect($URL);

		if($Nb['Total'] <= OMNI_MAX_PAGE)
			$C['Pager']='';
		else
		{
			$C['Pager']='<aside class="pager">Aller à la page : ';
			$Pages=array();
			$FirstPage = max(1, $Page + 1 - 5);
			$TotalPage = ceil($Nb['Total'] / OMNI_MAX_PAGE);
			$LastPage = min($TotalPage, $Page + 1 + 5);

			if($FirstPage > 1)
			{
				$Pages[] = self::makeSpecificPage(1, $URL, $Page + 1);
				if($FirstPage > 2)
					$Pages[] = '&hellip;';
			}

			for($i=$FirstPage; $i<=$LastPage; $i++)
			{
				$Pages[] .= self::makeSpecificPage($i, $URL, $Page + 1);
			}

			if($LastPage < $TotalPage)
			{
				if($LastPage < $TotalPage - 1)
					$Pages[] = '&hellip;';

				$Pages[] = self::makeSpecificPage($TotalPage, $URL, $Page + 1);
			}

			$C['Pager'] .= "\n" . implode(' &ndash; ',$Pages) . "\n";
			unset($Pages);
			$C['Pager'] .='<span class="nb-total">(' . $Nb['Total'] . ' articles)</span></aside>';
		}

		return $Articles;
	}

	protected static function makeSpecificPage($Number, $URL, $Selected)
	{
		return '<a href="' . ($Number==1?$URL:$URL . 'Page-' . $Number) . '" ' . ($Number==$Selected?'class="page_actuelle"':'') . '>' . $Number . '</a>';
	}
}
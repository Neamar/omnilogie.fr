<?php
/**
* But : offrir des facilit�s de formatage HTML
*
*/
//Formatting

//////////////////////////////////////////////////////
//Fonctionnalit�s du contr�leur :

class Formatting
{
	/**
	* Formater correctement un nombre : 123456 => 123 456
	* @param Nb:int le nombre
	* @return :String le nombre mis en forme.
	* @example
	* echo Formatting::makeNumber(250000);
	* //Renverra 250 000 avec un espace ins�cable.
	*/
	public static function makeNumber($Nb)
	{
		return str_replace('@','&nbsp;',number_format($Nb,0,',','@'));
	}

	/**
	* Cr�er une liste HTML � partir d'un tableau
	* @param List:Array La liste � convertir en tableau
	* @param Type:String Le type de la liste ; valeurs valide : ol, ul ; 'ul' par d�faut.
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
	* G�re la pagination de toutes les pages devant afficher plus de OMNI_MAX_PAGE articles par page, sous forme de Teaser.
	* @param Param:SqlParam Les param�tres pour r�cup�rer l'article ; ils seront modifi�s au niveau du LIMIT pour ne r�cup�rer que la partie int�ressante.
	* @param URL:String le masque de l'URL � utiliser. Exemple : /Omnilogistes/Neamar/ (<-- notez le slash � la fin !) qui deviendra /Omnilogistes/Neamar/Page-2 (Page-1 renvoie directement vers l'URL de base).
	* @param Page:int Lu directement depuis la variable $_GET['Page'].
	* @return :array La fonction travaille directement avec $C : $C['Articles'] contient un tableau de Teaser, et $C['Pager'] contient la structure de contr�le si elle est n�cessaire. Elle renvoie aussi le tableau des articles s�lectionn�s.
	*/
	public static function makePage(SqlParam $Param,$URL=null)
	{
		global $C;

		//Contr�le de l'urldecode
		if(is_null($URL))
			Debug::fail('Le param�tre URL ne doit pas �tre null');

		//Lecture du num�ro de page demand�.
		if(!isset($_GET['Page']))
			$Page = 0;
		else
			$Page = intval($_GET['Page']) - 1;

		if($Page==-1)
			Debug::fail('D�sol�, la consultation des articles en avance sur le temps est actuellement interdite par la relativit� restreinte ; merci de r�essayer plus tard.');

		//Modification du $Param :
		$Param->Select = 'SQL_CALC_FOUND_ROWS ' . $Param->Select;
		$Param->Limit = ($Page * OMNI_MAX_PAGE) . ',' . OMNI_MAX_PAGE;

		//R�cup�rer les articles
		$Articles = Omni::get($Param);
		foreach($Articles as &$Article)
		{
			$C['Articles'][$Article->ID]['Teaser'] = $Article->outputTeaser();
		}

		//Faut-il afficher un module de contr�le pour la pagination ?
		$Nb = SQL::singleQuery('SELECT FOUND_ROWS( ) AS Total');

		if($Page * OMNI_MAX_PAGE>$Nb['Total'])
			Debug::redirect($URL);

		if($Nb['Total'] <= OMNI_MAX_PAGE)
			$C['Pager']='';
		else
		{
			$C['Pager']='<aside class="pager">Aller � la page : ';
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
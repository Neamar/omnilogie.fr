<?php
/**
* But : offrir une interface centralis�e d'acc�s � la BDD, pour pouvoir changer facilement de m�thode d'acc�s ou de syst�me de BDD.
*/
//Sql

//////////////////////////////////////////////////////
//Fonctionnalit�s du contr�leur :

class Sql
{
	/**
	* Ouvrir une connexion � la base de donn�es.
	*/
	public static function connect()
	{
		include(PATH . '/ConnectBDD.php');
	}

	/**
	* Ferme la connexion. Normalement implicite et non appel� par les scripts.
	*/
	public static function disconnect()
	{
		mysql_close();
	}

	/**
	* Ex�cute une requ�te sur la base
	* @param Query:String la requ�te � effectuer
	* @return :SQLResource le r�sultat de la requ�te.
	*/
	public static function query($Query)
	{
		$R = mysql_query($Query) or Debug::sqlFail();
		return $R;
	}

	/**
	* Ex�cute une requ�te sur la base. En cas d'erreur, le script n'est pas interrompu et la fonction appelante peut traiter l'exception.
	* @param Query:String la requ�te � effectuer
	* @return :SQLResource le r�sultat de la requ�te.
	*/
	public static function queryNoFail($Query)
	{
		return mysql_query($Query) or error_log(mysql_error());
	}

	/**
	* Ex�cute une requ�te sur la base et ne renvoie que le premier r�sultat
	* @param Query:String la requ�te � effectuer
	* @param Type:String le type de l'objet de retour. Si null (par d�faut), on renvoie un tableau.
	* @return :(Object|array) le premier r�sultat de la requ�te. Si aucun r�sultat, la fonction renvoie null.
	*/
	public static function singleQuery($Query,$Type=null)
	{
		$R = self::query($Query);
		if(mysql_num_rows($R)==0)
			return null;
		if(is_null($Type))
			return mysql_fetch_array($R);
		else
			return mysql_fetch_object($R,$Type);
	}

	/**
	* Ins�re un tuple dans une table de la base de donn�es.
	* En cas d'erreurs (duplicate), l'erreur n'est pas trait�e et est renvoy�e � l'appelant pour gestion.
	* NOTE: Les cl�s du tableau Datas commen�ant par un "_" indiquent que la valeur associ�e ne doit pas �tre �chapp�e. Le "_" est ensuite supprim� lors de l'update sur la table. Voir le deuxi�me exemple.
	* @param Table:String la table dans laquelle ins�rer les donn�es.
	* @param Datas:array un tableau associatif sous la forme cl�=>valeur dans la table. Les valeurs doivent �tre �chapp�es ! Elle n'ont cependant pas � �tre quot�es, des guillemets seront ajout�s sauf si la cl� commence par un _ (cf. note).
	* @return :SQLResource le r�sultat de la requ�te.
	* @example
	*	$ToInsert = array('Reference'=>$ArticleID,'URL'=>'http://neamar.fr');
	*	SQL::insert('More',$ToInsert);
	*/
	public static function insert($Table,array $Valeurs)
	{
		//On ne peut pas simplement utiliser array_keys, car on peut avoir � modifier les cl�s (r�gle de l'underscore)
		$Keys=array();
		foreach($Valeurs as $K=>&$V)
		{
			if($K[0]=='_')
				$Keys[] = substr($K,1);
			else
			{
				$Keys[] = $K;
				$V = '"' . $V . '"';
			}
		}

		return self::queryNoFail('INSERT INTO ' . $Table . '(' . implode(',',$Keys) . ')
		VALUES (' . implode(',',$Valeurs) . ')');
	}

	/**
	* Met � jour un tuple dans une table de la base de donn�es selon l'identifiant sp�cifi�.
	* En cas d'erreurs, l'erreur n'est pas trait�e et est renvoy�e � l'appelant pour gestion.
	* NOTE: Les cl�s du tableau Datas commen�ant par un "_" indiquent que la valeur associ�e ne doit pas �tre �chapp�e. Le "_" est ensuite supprim� lors de l'update sur la table. Voir le deuxi�me exemple.
	* @param Table:String la table dans laquelle ins�rer les donn�es.
	* @param ID:int l'identifiant du tuple � mettre � jour. Forc�ment l'ID.
	* @param Datas:array un tableau associatif sous la forme cl�=>valeur dans la table. Les valeurs doivent �tre �chapp�es ! Elle n'ont cependant pas � �tre quot�es, des guillemets seront ajout�s sauf si la cl� commence par un _ (cf. note).
	* @param And:String des contraintes suppl�mentaires permettant de valider la mise � jour (exemple : "AND Auteur.ID=2" pour emp�cher la modification de n'importe quoi)
	* @param Limit:int nombre maximal d'enregistrements � modifier
	* @return :SQLResource le r�sultat de la requ�te.
	* @example
	* //Notez la r�alisation de la proposition si n�cessaire :
	*	if(is_numeric($_POST['proposition']))
	*		SQL::update('Propositions',$_POST['proposition'],array('OmniID'=>mysql_insert_id()),'AND ReservePar=' . AUTHOR_ID);
	* @example
	* //Explicitation du _
	*
	* //Incorrect : le now() sera updat� sous la forme "NOW()" (guillemets compris, ce qui sera invalid� car la cha�ne de caract�res "NOW()" n'est pas de type DATE.
	* SQL::update('Propositions',$_POST['proposition'],array('Date'=>'NOW()');
	*
	* //Correct : pour indiquer qu'il s'agit d'un appel � une fonction / expression, pr�c�dez votre cl� d'un _ :
	* SQL::update('Propositions',$_POST['proposition'],array('_Date'=>'NOW()');
	* @example
	* //Explicitation du $And
	* //Petit "hack" pour mettre � jour un tuple dont on ne conna�t pas l'ID :
	* SQL::update('Propositions',-1,array('Titre'=>'Lol'),'OR ID=(SELECT MAX(ID) FROM Propositions)'
	*/
	public static function update($Table,$ID, array $Valeurs,$And='',$Limit=1)
	{
		$Set=array();
		foreach($Valeurs as $K=>$V)
		{
			if($K[0]=='_')
				$Set[] = substr($K,1) . '=' . $V . '';
			else
				$Set[] = $K . '="' . $V . '"';
		}

		return self::queryNoFail('UPDATE ' . $Table . ' SET ' . implode(',',$Set) . '
		WHERE ID=' . intval($ID) . ' ' . $And . ' LIMIT ' . $Limit);
	}

	/**
	* Supprime un tuple bas� sur les contraintes sp�cifi�es.
	* @param Table:String la table dans laquelle supprimer les donn�es.
	* @param ID:int l'identifiant du tuple � d�truire. Forc�ment l'ID.
	* @param $And:String des contraintes suppl�mentaires permettant de valider la mise � jour (exemple : "AND Auteur.ID=2" pour emp�cher la modification de n'importe quoi)
	* @return :SQLResource le r�sultat de la requ�te.
	*/
	public static function delete($Table,$ID,$And)
	{
		SQL::delete('DELETE FROM ' . $Table . ' WHERE ID=' . intval($ID) . ' ' . $And . ' LIMIT 1');
	}
}

/**
* Classe g�n�rique de param�tre d'appel � la base de donn�e.
*/
class SqlParam
{
	public $Select;
	public $Where;
	public $Limit;
	public $Having;
	public $Group;
	public $Order;

	/**
	* Construit un objet SQLParam avec les param�tres demand�s.
	*/
	public function __construct(array &$Param=null)
	{
		if(is_null($Param))
			return;

		//Construire l'objet avec les propri�t�s par d�faut et les valeurs sp�cifi�es.
		static $Defaut = array
		(
			'Select'=>'*',
			'Where'=>null,
			'Limit'=>null,
			'Having'=>null,
			'Order'=>null,
		);

		$Param = array_merge($Defaut,$Param);

		foreach($Param as $Item=>$Value)
		{
			$this->{$Item} = $Value;
		}
	}

	/**
	* R�cup�re les �l�ments.
	*/
	public function getSelect()
	{
		return 'SELECT ' . $this->Select;
	}

	public function getWhere()
	{
		if(!is_null($this->Where))
			return 'WHERE ' . $this->Where;
	}

	public function getLimit()
	{
		if(!is_null($this->Limit))
			return 'LIMIT ' . $this->Limit;
	}

	public function getHaving()
	{
		if(!is_null($this->Having))
			return 'HAVING ' . $this->Having;
	}

	public function getGroup()
	{
		if(!is_null($this->Group))
			return 'GROUP BY ' . $this->Group;
	}

	public function getOrder()
	{
		if(!is_null($this->Order))
			return 'ORDER BY ' . $this->Order;
	}
}

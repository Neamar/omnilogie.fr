<?php
/**
* Contr�leur : vote
* But : Enregistrer le vote du visiteur
*
*/

//////////////////////////////////////////////////////
//Fonctionnalit�s du contr�leur :

if(isset($_POST['vote']) && $_POST['vote'] != 0 && !isset($_SESSION['hasVoted']))
{
	//Ajouter un vote � l'article
	SQL::update('OMNI_Omnilogismes', (int) $_POST['vote'], array('_NbVotes'=>'NbVotes+1'), 'AND Sortie LIKE "' . Top::$month . '"');

	if(mysql_affected_rows() == 1)
	{
		$_SESSION['hasVoted'] = true;
		$C['Message'] = 'Merci pour votre vote !';
		$C['MessageClass'] = 'info';
	}
}
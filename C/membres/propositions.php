<?php
/**
* Contr�leur : membres/propositions
* But : Ajouter la proposition en BDD si possible
*/

//////////////////////////////////////////////////////
//Fonctionnalit�s du contr�leur :

if(isset($_POST['proposition']))
{
	$ToInsert = array
	(
		'AjoutPar'=>AUTHOR_ID,
		'_Date'=>'NOW()',
		'Description'=>$_POST['proposition'],
		'Lien'=>$_POST['lien'],
	);

	if($_POST['proposition']=='')
		$C['Message'] = 'Proposition vide inint�ressante !';
	elseif(!SQL::insert('OMNI_Propositions',$ToInsert))
		Debug::fail('Impossible d\'enregistrer la modification');
	else
	{
		//R�ussi !
		Event::dispatch(Event::NOUVELLE_PROPOSITION);
		$C['Message'] = 'Proposition ajout�e !';
		$C['MessageClass'] = 'info';
	}
}

if(!empty($_GET['Reserve']))
{
	if(!SQL::update('OMNI_Propositions',intval($_GET['Reserve']),array('ReservePar'=>AUTHOR_ID),'AND ISNULL(ReservePar)') || mysql_affected_rows()==0)
		Debug::fail('Impossible de r�server ; vous n\'avez probablement pas les droits, ou l\'article est d�j� r�serv�.');

	$_SESSION['FutureMessage'] = 'Proposition r�serv�e !';
	$_SESSION['FutureMessageClass'] = 'info';
	Debug::redirect('/membres/Propositions');
}
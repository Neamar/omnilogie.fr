<?php
/**
* Mod�le : membres/Propositions
* But : ajouter une proposition, afficher la lsite des propositions, afficher la liste des propositions r�serv�es
* Donn�es � charger : liste des propositions
*
*/

$C['PageTitle']='Propositions d\'articles � r�diger';
$C['CanonicalURL']='/membres/Propositions';

function getProp($Where,$Prefixe)
{
	global $C;

	$Propositions = SQL::query('SELECT OMNI_Propositions.ID, OMNI_Propositions.Description, OMNI_Propositions.Lien, R.Auteur as ReservePar
	FROM OMNI_Propositions
	LEFT JOIN OMNI_Auteurs R ON (ReservePar = R.ID)
	WHERE ' . $Where . '
	ORDER BY OMNI_Propositions.ID DESC');
	$C[$Prefixe] = array();
	while($Prop = mysql_fetch_assoc($Propositions))
	{
		Typo::setTexte($Prop['Description'] . ($Prop['Lien']!=''?'(\l[' . $Prop['Lien'] . ']{Plus d\'infos})':''));
		$C[$Prefixe][$Prop['ID']] = array('Proposition'=>Typo::parseLinear(),'ReservePar'=>$Prop['ReservePar']);
	}
}

if(defined('NOOB_MODE') && !isset($C['Message']))
{
	$C['Message'] = 'Cette page liste des propositions d\'articles. Si vous �tes int�ress�, n\'h�sitez pas � en r�server une pour �crire l\'article !';
}


getProp('ISNULL(OmniID) AND ISNULL(ReservePar)','PropositionsLibres');
getProp('ISNULL(OmniID) AND !ISNULL(ReservePar)','PropositionsReservees');
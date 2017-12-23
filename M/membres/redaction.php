<?php
/**
* Modèle : membres/Redaction
* But : créer un nouvel article
* Données à charger : liste des propositions réservées
*
*/

$C['PageTitle']='Écriture d\'un nouvel article';
$C['CanonicalURL']='/membres/Redaction';

if(defined('NOOB_MODE') && !isset($C['Message']))
{
	$C['Message'] = 'Voilà la page la plus importante pour les membres : c\'est d\'ici que vous pouvez rédiger vos articles.';
}


//Valeurs par défaut du formulaire:
$C['Valeurs']=array(
'titre'=>'',
'article'=>'',
'sources'=>'',
'prop-id'=>'no');

//Si il y a une erreur lors de l'enregistrement, on va réafficher l'ancienne valeur.
//Il faut donc déséchapper $_POST.
if(isset($_POST['titre']))
{
	$_POST=array_map('stripslashes',$_POST);
	$C['Valeurs'] = array_merge($C['Valeurs'],$_POST);
}



$Propositions = SQL::query('SELECT ID, Description FROM OMNI_Propositions WHERE ReservePar=' . AUTHOR_ID . ' AND ISNULL(OmniID)');
$C['Propositions'] = array();
while($Prop = mysql_fetch_assoc($Propositions))
{
	Typo::setTexte($Prop['Description']);
	$C['Propositions'][$Prop['ID']] = preg_replace('#<[^>]+>#','',Typo::parseLinear());
}
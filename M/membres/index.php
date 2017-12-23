<?php
/**
* Modèle : membres/index
* But : afficher les infos utiles à un membre
* Données à charger : propositions, infos membres, liste des articles
*
*/

$C['PageTitle']='Espace membre de ' . AUTHOR;
$C['CanonicalURL']='/membres/';

//Message de bienvenue
if(defined('NOOB_MODE') && !isset($C['Message']))
{
	$C['Message'] = 'Bienvenue parmi les omnilogistes ! Votre compte est maintenant créé, vous n\'êtes plus qu\'à un clic de la création de votre premier article.<br />Avant de commencer, peut-être voudrez-vous lire <a href="/Ligne">la ligne éditoriale</a> du site ?<br />Sinon, votre prochaine étape sera <strong><a href="/membres/Redaction">la page de rédaction</a></strong>. Bonne visite :)';
}

//liste des propositions réservées
$Propositions = SQL::query('SELECT Description, Lien FROM OMNI_Propositions WHERE ReservePar=' . AUTHOR_ID . ' AND ISNULL(OmniID)');
$C['Propositions'] = array();
while($Prop = mysql_fetch_assoc($Propositions))
{
	Typo::setTexte($Prop['Description'] . ($Prop['Lien']!=''?' (\l[' . $Prop['Lien'] . ']{plus d\'infos})':''));
	$C['Propositions'][] = Typo::Parse();
}
if(count($C['Propositions'])==0)
	$C['Propositions'][] = 'Aucune proposition réservée. <a href="/membres/Propositions">Pourquoi ne pas vous y mettre ?</a>';

//Infos sur le membre
$C['MembreData'] = SQL::singleQuery('SELECT Auteur, Pass, Mail, MailPublic, Histoire, DernierMail, Adsense, COALESCE(GooglePlus, "") AS GooglePlus FROM OMNI_Auteurs WHERE ID=' . intval(AUTHOR_ID));

//Liste des articles avec nombre de vues
$Param = Omni::buildParam(OMNI_TRAILER_PARAM);
$Param->Select = $Param->Select . ', NbVues';
$Param->Where = 'Auteur = ' . AUTHOR_ID;
$Param->Order = 'NbVues DESC';

$C['StatsArticles']=array();

$Articles=Omni::get($Param);
foreach($Articles as &$Article)
	$C['StatsArticles'][] = $Article->outputTrailer() . '(' . $Article->NbVues . ' vues)';


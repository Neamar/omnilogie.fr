<?php
/**
* Contrôleur : admin/generic.php
* But : authentification, gérer les messages différés.
*/

//Accès demandés pour les pages :
$_Pages = array(
	'arbre'=>array('taggers'),
	'correction'=>array('censeurs'),
	'showCorrection'=>array('censeurs'),
	'index'=>array('any'),
	'showArticle'=>array('any'),
	'showDiff'=>array('reffeurs','censeurs'),
	'showRef'=>array('reffeurs','censeurs'),
	'showTag'=>array('taggers'),
	'propositions'=>array('propositions'),
	'flux'=>array('everybody'),
	'authors'=>array('censeurs'),
);

$Page = basename($_GET['P'],'.php');

//Page non repértoriée : il faut être un admin obligatoirement !
if(!isset($_Pages[$Page]))
	$_Pages[$Page]=array();

//Les admins ont tous les accès !
array_unshift($_Pages[$Page],'admins');

//Authentifier l'utilisateur.
if(!in_array('everybody',$_Pages[$Page]))
{
	Authenticate::login($_Pages[$Page]);
	//Une fois authentifié, l'admin est aussi un membre :
	include(PATH . '/C/membres/generic.php');
}

//Affichage des articles à paraître :
$C['Pods']['publiable']['Title']='Articles à paraître';
$C['Pods']['publiable']['Content']=Formatting::makeList(Omni::getTrailers(Admin::getProchains()));

//Affichage des dernières actions
$C['Pods']['lastactions']['Title']='Dernières actions';
$C['Pods']['lastactions']['Content']=Formatting::makeList(Event::getLast(15, 1,'%DATE% %LIEN% : %MODIF% par %AUTEUR% %DIFF%'));
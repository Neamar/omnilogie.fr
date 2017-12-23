<?php
/**
* Contr�leur : admin/generic.php
* But : authentification, g�rer les messages diff�r�s.
*/

//Acc�s demand�s pour les pages :
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

//Page non rep�rtori�e : il faut �tre un admin obligatoirement !
if(!isset($_Pages[$Page]))
	$_Pages[$Page]=array();

//Les admins ont tous les acc�s !
array_unshift($_Pages[$Page],'admins');

//Authentifier l'utilisateur.
if(!in_array('everybody',$_Pages[$Page]))
{
	Authenticate::login($_Pages[$Page]);
	//Une fois authentifi�, l'admin est aussi un membre :
	include(PATH . '/C/membres/generic.php');
}

//Affichage des articles � para�tre :
$C['Pods']['publiable']['Title']='Articles � para�tre';
$C['Pods']['publiable']['Content']=Formatting::makeList(Omni::getTrailers(Admin::getProchains()));

//Affichage des derni�res actions
$C['Pods']['lastactions']['Title']='Derni�res actions';
$C['Pods']['lastactions']['Content']=Formatting::makeList(Event::getLast(15, 1,'%DATE% %LIEN% : %MODIF% par %AUTEUR% %DIFF%'));
<?php
/**
* Contrôleur : membres/generic.php
* But : Vérifier que le membre est bien connecté, sinon le rediriger vers la page de connexion en faisant en sorte qu'il retombe sur la page initiale une fois loggé.
* Structure des informations de sesssions membres :
* ID, Pseudo.
*/

//////////////////////////////////////////////////////
//Fonctionnalités du contrôleur :
$PageNonProtegees=array('membres/connexion','membres/inscription','membres/apercu','membres/oubli');


//Personnes non connectées tentant d'accéder à des pages restreintes.
if(!in_array($_GET['P'],$PageNonProtegees) && !isset($_SESSION['Membre']['ID']))
{
	$_SESSION['Membre']['RedirectTo'] = $_SERVER['REQUEST_URI'];
	//Sauvegarder les données POST (si on a été déconnecté pendant l'envoi d'un article par exemple)
	if(count($_POST)>0)
	{
		$_SESSION['PostData'] = $_POST;
		External::mail('neamar@neamar.fr','Dump de déconnexion',serialize($_POST));
	}
	Debug::redirect('/membres/Connexion',302);
}
//Personnes connectées.
elseif(isset($_SESSION['Membre']['ID']))
{
	define('AUTHOR',$_SESSION['Membre']['Pseudo']);
	define('AUTHOR_ID',$_SESSION['Membre']['ID']);

	//Créer le menu avec les liens admins
	$C['Pods']['connected']['Title']='Liens pour ' . AUTHOR;
	$C['Pods']['connected']['Content']=Formatting::makeList(array(
		'<a href="/membres/">Espace membre</a>',
		'<a href="/membres/Redaction">Rédaction d\'un article</a>',
		'<a href="/membres/Propositions">Propositions d\'articles</a>',
		'<a href="/membres/Connexion">Déconnexion</a>',
		'<a href="/membres/?membre=' . $_SESSION['Membre']['Hash'] . '">Lien de connexion directe</a> (à glisser dans les favoris)',
		));

	//Articles éditables
	$Param = Omni::buildParam(OMNI_TRAILER_PARAM);
	$Param->Where = 'Auteur = ' . AUTHOR_ID . ' AND ISNULL(Sortie)';

	$C['Editables'] = Omni::getTrailers($Param);
	foreach($C['Editables'] as &$Trailer)
		$Trailer=str_replace('/O/','/membres/Edit/',$Trailer);

	if(count($C['Editables'])!=0)
	{
		$C['Pods']['modifiable']['Title']='Articles éditables';
		$C['Pods']['modifiable']['Content']=Formatting::makeList($C['Editables']);
	}

	//Stats sur les articles du membre
	$Stats = SQL::singleQuery('SELECT COUNT(*) AS Nb, SUM(NbVues) AS Somme FROM OMNI_Omnilogismes WHERE Auteur=' . AUTHOR_ID);
	$C['Pods']['author-stats']['Title']='Statistiques rapides';
	$C['Pods']['author-stats']['Content']=Formatting::makeList(array($Stats['Nb'] . ' article' . ($Stats['Nb']>1?'s':'') . ' écrit' . ($Stats['Nb']>1?'s':'') . ' par ' . AUTHOR . '&nbsp;;',Formatting::makeNumber($Stats['Somme']) . ' articles visionnés&nbsp;','<a href="/membres/Stats">Plus de stats !</a>'));

	if($Stats['Nb']==0)
		define('NOOB_MODE',true);

	//Rétablir les données POST si nécessaires (elles ont été enregistrées plus haut sur cette page):
	if(isset($_SESSION['PostData']))
	{
		$_POST=$_SESSION['PostData'];
		unset($_SESSION['PostData']);
	}

}

//Gestion des messages différés.
if(isset($_SESSION['FutureMessage']) && !isset($C['Message']))
{
	$C['Message']=$_SESSION['FutureMessage'];
	unset($_SESSION['FutureMessage']);
	if(isset($_SESSION['FutureMessageClass']))
	{
		$C['MessageClass']=$_SESSION['FutureMessageClass'];
		unset($_SESSION['FutureMessageClass']);
	}
}
<?php
/**
* Contr�leur : membres/generic.php
* But : V�rifier que le membre est bien connect�, sinon le rediriger vers la page de connexion en faisant en sorte qu'il retombe sur la page initiale une fois logg�.
* Structure des informations de sesssions membres :
* ID, Pseudo.
*/

//////////////////////////////////////////////////////
//Fonctionnalit�s du contr�leur :
$PageNonProtegees=array('membres/connexion','membres/inscription','membres/apercu','membres/oubli');


//Personnes non connect�es tentant d'acc�der � des pages restreintes.
if(!in_array($_GET['P'],$PageNonProtegees) && !isset($_SESSION['Membre']['ID']))
{
	$_SESSION['Membre']['RedirectTo'] = $_SERVER['REQUEST_URI'];
	//Sauvegarder les donn�es POST (si on a �t� d�connect� pendant l'envoi d'un article par exemple)
	if(count($_POST)>0)
	{
		$_SESSION['PostData'] = $_POST;
		External::mail('neamar@neamar.fr','Dump de d�connexion',serialize($_POST));
	}
	Debug::redirect('/membres/Connexion',302);
}
//Personnes connect�es.
elseif(isset($_SESSION['Membre']['ID']))
{
	define('AUTHOR',$_SESSION['Membre']['Pseudo']);
	define('AUTHOR_ID',$_SESSION['Membre']['ID']);

	//Cr�er le menu avec les liens admins
	$C['Pods']['connected']['Title']='Liens pour ' . AUTHOR;
	$C['Pods']['connected']['Content']=Formatting::makeList(array(
		'<a href="/membres/">Espace membre</a>',
		'<a href="/membres/Redaction">R�daction d\'un article</a>',
		'<a href="/membres/Propositions">Propositions d\'articles</a>',
		'<a href="/membres/Connexion">D�connexion</a>',
		'<a href="/membres/?membre=' . $_SESSION['Membre']['Hash'] . '">Lien de connexion directe</a> (� glisser dans les favoris)',
		));

	//Articles �ditables
	$Param = Omni::buildParam(OMNI_TRAILER_PARAM);
	$Param->Where = 'Auteur = ' . AUTHOR_ID . ' AND ISNULL(Sortie)';

	$C['Editables'] = Omni::getTrailers($Param);
	foreach($C['Editables'] as &$Trailer)
		$Trailer=str_replace('/O/','/membres/Edit/',$Trailer);

	if(count($C['Editables'])!=0)
	{
		$C['Pods']['modifiable']['Title']='Articles �ditables';
		$C['Pods']['modifiable']['Content']=Formatting::makeList($C['Editables']);
	}

	//Stats sur les articles du membre
	$Stats = SQL::singleQuery('SELECT COUNT(*) AS Nb, SUM(NbVues) AS Somme FROM OMNI_Omnilogismes WHERE Auteur=' . AUTHOR_ID);
	$C['Pods']['author-stats']['Title']='Statistiques rapides';
	$C['Pods']['author-stats']['Content']=Formatting::makeList(array($Stats['Nb'] . ' article' . ($Stats['Nb']>1?'s':'') . ' �crit' . ($Stats['Nb']>1?'s':'') . ' par ' . AUTHOR . '&nbsp;;',Formatting::makeNumber($Stats['Somme']) . ' articles visionn�s&nbsp;','<a href="/membres/Stats">Plus de stats !</a>'));

	if($Stats['Nb']==0)
		define('NOOB_MODE',true);

	//R�tablir les donn�es POST si n�cessaires (elles ont �t� enregistr�es plus haut sur cette page):
	if(isset($_SESSION['PostData']))
	{
		$_POST=$_SESSION['PostData'];
		unset($_SESSION['PostData']);
	}

}

//Gestion des messages diff�r�s.
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
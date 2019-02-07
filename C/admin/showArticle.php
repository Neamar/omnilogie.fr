<?php
/**
* Contrôleur : admin/admin.php
* But : enregistrer les modifications de statut apportées à un article.
* Charge un tableau $Articles pour le modèle.
* Modèle et contrôleur sont mélangés dans cette interface.
*/

//////////////////////////////////////////////////////
//Fonctionnalités du contrôleur :
$C['Sections']=array();

$Titre = Encoding::decodeFromGet('Titre');
$Where = 'Omnilogismes.Titre="' . $Titre . '"';

if(Member::is(AUTHOR,'admins'))
{
	$C['Sections']['admin']['Titre'] = 'Administration de l\'article';
	$C['Sections']['admin']['Description'] = 'Ce pod permet de modifier le statut des articles.';
	$C['Sections']['admin']['Articles'] = Admin::getControleur($Where,'statut', array('Admin','adminControleurCallback'));
	$C['Sections']['admin']['Vue'] = array('Admin','adminVueCallback');
	$C['Sections']['admin']['Max'] = -1;
}

if(Member::is(AUTHOR,'censeurs'))
{
	$C['Sections']['edit']['Titre'] = 'Correction de l\'article';
	$C['Sections']['edit']['Description'] = 'Ce pod affiche les articles qui ont peut-être besoin d\'être corrigés (jamais relus par un censeur).';
	$C['Sections']['edit']['Articles'] = Admin::getControleur($Where,'edit',null);
	$C['Sections']['edit']['Vue'] = array('Admin','editVueCallback');
	$C['Sections']['edit']['Max'] = -1;
	$C['Sections']['edit']['SubmitCaption'] = '';
}

if(Member::is(AUTHOR,'censeurs'))
{
	$C['Sections']['message']['Titre'] = 'Gestion du message';
	$C['Sections']['message']['Description'] = 'Ce pod permet de mettre un message important en en-tête des articles.';
	$C['Sections']['message']['Articles'] = Admin::getControleur($Where,'message',array('Admin','messageControleurCallback'));
	$C['Sections']['message']['Vue'] = array('Admin','messageVueCallback');
}

if(Member::is(AUTHOR,'bannières'))
{
	$C['Sections']['bannieres']['Titre'] = 'Gestion de la bannière';
	$C['Sections']['bannieres']['Description'] = 'Ce pod affiche les articles qui n\'ont pas de bannière.';
	$C['Sections']['bannieres']['Articles'] = Admin::getControleur($Where,'banniere', array('Admin','banniereControleurCallback'));
	$C['Sections']['bannieres']['Vue'] = array('Admin','banniereVueCallback');
	$C['Sections']['bannieres']['Max'] = 2;
	$C['Sections']['bannieres']['IsFileForm'] = 2;
}

if(Member::is(AUTHOR,'accrocheurs'))
{
	$C['Sections']['accroches']['Titre'] = 'Gestion de l\'accroche';
	$C['Sections']['accroches']['Description'] = 'Ce pod affiche les articles qui ont besoin d\'être accrochés.';
	$C['Sections']['accroches']['Articles'] = Admin::getControleur($Where,'accroche',array('Admin','accrocheControleurCallback'));
	$C['Sections']['accroches']['Vue'] = array('Admin','accrocheVueCallback');
}

if(Member::is(AUTHOR,'censeurs'))
{
	$C['Sections']['suite']['Titre'] = 'Suite de l\'article';
	$C['Sections']['suite']['Description'] = 'Ce pod permet de faire suivre cet article par un autre &ndash; indiquez ici le titre de l\'article suivant.';
	$C['Sections']['suite']['Articles'] = Admin::getControleur($Where,'suite',array('Admin','suiteControleurCallback'));
	$C['Sections']['suite']['Vue'] = array('Admin','suiteVueCallback');
}

if(Member::is(AUTHOR,'any'))
{
	$C['Sections']['anecdote']['Titre'] = 'Anecdote';
	$C['Sections']['anecdote']['Description'] = 'Ce pod permet d\'ajouter une anecdote à l\'article.';
	$C['Sections']['anecdote']['Articles'] = Admin::getControleur($Where,'anecdote',array('Admin','anecdoteControleurCallback'));
	$C['Sections']['anecdote']['Vue'] = array('Admin','anecdoteVueCallback');
}


if(count($C['Sections']['anecdote']['Articles'])==0)
	return Debug::status(404);


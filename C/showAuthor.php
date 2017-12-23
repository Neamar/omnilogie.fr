<?php
/**
* But : Contr�ler l'existence d'un auteur
* Permet de rediriger ou de faire �chouer le script si n�cessaire.
*/

//////////////////////////////////////////////////////
//Fonctionnalit�s du contr�leur :

//R�cup�rer le titre de la page

$Author = Encoding::decodeFromGet('Auteur');


//V�rifier que l'auteur existe

//1 : Pas d'espace :
if(strpos($_GET['Auteur'],' ')!==false)
	Debug::redirect(Link::author($Author),301);

//L'auteur existe-t-il ?
$AuthorID = SQL::singleQuery('SELECT ID FROM OMNI_Auteurs WHERE Auteur="' . mysql_real_escape_string($Author) . '"');
if(is_null($AuthorID))
{
	$C['CustomError'] = 'L\'auteur <tt>' . $Author . '</tt> est introuvable.';
	return Debug::status(404);
}

$AuthorID = intval($AuthorID['ID']);
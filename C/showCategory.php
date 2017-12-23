<?php
/**
* But : Contr�ler l'existence d'un article avant de charger le mod�le appropri�.
* Permet de rediriger ou de faire �chouer le script si n�cessaire.
*/

//////////////////////////////////////////////////////
//Fonctionnalit�s du contr�leur :

//R�cup�rer le titre de la page

$Category = Encoding::decodeFromGet('Categorie');


//V�rifier que la cat�gorie existe

//1 : Pas d'espace :
if(strpos($_GET['Categorie'],' ')!==false)
	Debug::redirect(Link::category($Category),301);

//La cat�gorie existe-t-elle ?
if(!Category::exists($Category))
{
	$C['CustomError'] = 'La cat�gorie <tt>' . $Category . '</tt> est introuvable.';
	return Debug::status(404);
}
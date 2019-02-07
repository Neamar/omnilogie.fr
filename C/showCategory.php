<?php
/**
* But : Contrôler l'existence d'un article avant de charger le modèle approprié.
* Permet de rediriger ou de faire échouer le script si nécessaire.
*/

//////////////////////////////////////////////////////
//Fonctionnalités du contrôleur :

//Récupérer le titre de la page

$Category = Encoding::decodeFromGet('Categorie');


//Vérifier que la catégorie existe

//1 : Pas d'espace :
if(strpos($_GET['Categorie'],' ')!==false)
	Debug::redirect(Link::category($Category),301);

//La catégorie existe-t-elle ?
if(!Category::exists($Category))
{
	$C['CustomError'] = 'La catégorie <tt>' . $Category . '</tt> est introuvable.';
	return Debug::status(404);
}
<?php
/**
* Mod�le : Erreur
* But : Afficher un message d'erreur
*/
//Articles
$Erreurs = array(
'403'=>'Vous n\'avez pas les droits pour acc�der � cette ressource.',
'404'=>'La page que vous cherchez n\'existe pas ou plus !',
'500'=>'Le serveur est actuellement en train de sucrer les fraises... revenez dans quelques minutes, elles seront d�licieuses.'
);

$C['CodeErreur'] = intval($_GET['Erreur']);
if(!isset($Erreurs[$C['CodeErreur']]) && !isset($C['CustomError']))
	$C['Erreur'] = 'Une erreur inconnue est survenue !';
elseif(isset($C['CustomError'])) //Erreur depuis le script
	$C['Erreur'] = $C['CustomError'];
else//Erreur d�s le .htaccess
{
	$C['Erreur'] = $Erreurs[$C['CodeErreur']];
	Debug::status($C['CodeErreur']);
}

$C['PageTitle'] = 'Erreur ' . $C['CodeErreur'] . ' : ' . preg_replace('`\<.+>`U','',$C['Erreur']);

$C['CanonicalURL'] = '';

$Liens = array(
'<a href="/">Article du jour</a>',
'<a href="/O/">Liste des articles</a>',
'<a href="/Liste/">Cat�gories</a>',
'<a href="/Omnilogistes/">Auteurs</a>',
);

if(isset($C['Liens']))
	$C['Liens'] = array_merge($C['Liens'],$Liens);
else
	$C['Liens'] = $Liens;

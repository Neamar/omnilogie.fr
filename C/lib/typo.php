<?php
/**
* But : charger le Typographe, classe de mise en forme.
* Note : ce fichier est dynamiquement charg� lors du premier appel � la classe Typo. Il faut cependant noter que la classe Typo est d�finie dans une des librairies Neamar, et que l'inclusion de ce fichier m�ne effectivement au chargement du Typo (voir le include) de fa�on indirecte.
*
*/
//Typo

//Chargement des d�pendances :
include(LIB_PATH . '/Typo.php');
include(LIB_PATH . '/regexp_callback.php');

//Personalisation de Typo selon nos besoins :
Typo::addOption(PARSE_MATH);
Typo::removeOption(ALLOW_SECTIONING);//Ne pas autoriser les balises de titres : c'est un article court, pas une bibliographie en trois volumes !
Typo::addOption(ALLOW_FOOTNOTE,FOOTNOTE_SCIENCE);//Pour que les notes de bas de page soient entour�es de parenth�ses, ce qui �vite de les confondre avec des puissances math�matiques.

Typo::addBalise('#\\\\ref\[(.+)\]{(.+)}#isU','<a href="/O/$1">$2</a>');
Typo::addBalise('#\\\\sagaref\[(.+)\]{(.+)}#isU','<a href="/Liste/$1">$2</a>');


Typo::$Escape_And_Prepare['#\\\\ref\[([^\[]+)\]{(.+)}#isU']=array
//Emp�cher de mettre en forme le texte dans les ref.
(
	'Protect'=>'OMNI-REF',
	'RegexpCode'=>1,
	'Modifications'=>array(' '=>CESURE),
 );


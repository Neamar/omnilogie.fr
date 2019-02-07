<?php
/**
* But : charger le Typographe, classe de mise en forme.
* Note : ce fichier est dynamiquement chargé lors du premier appel à la classe Typo. Il faut cependant noter que la classe Typo est définie dans une des librairies Neamar, et que l'inclusion de ce fichier mène effectivement au chargement du Typo (voir le include) de façon indirecte.
*
*/
//Typo

//Chargement des dépendances :
include(LIB_PATH . '/Typo.php');
include(LIB_PATH . '/regexp_callback.php');

//Personalisation de Typo selon nos besoins :
Typo::addOption(PARSE_MATH);
Typo::removeOption(ALLOW_SECTIONING);//Ne pas autoriser les balises de titres : c'est un article court, pas une bibliographie en trois volumes !
Typo::addOption(ALLOW_FOOTNOTE,FOOTNOTE_SCIENCE);//Pour que les notes de bas de page soient entourées de parenthèses, ce qui évite de les confondre avec des puissances mathématiques.

Typo::addBalise('#\\\\ref\[(.+)\]{(.+)}#isU','<a href="/O/$1">$2</a>');
Typo::addBalise('#\\\\sagaref\[(.+)\]{(.+)}#isU','<a href="/Liste/$1">$2</a>');


Typo::$Escape_And_Prepare['#\\\\ref\[([^\[]+)\]{(.+)}#isU']=array
//Empêcher de mettre en forme le texte dans les ref.
(
	'Protect'=>'OMNI-REF',
	'RegexpCode'=>1,
	'Modifications'=>array(' '=>CESURE),
 );


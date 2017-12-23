<?php
/**
* Modèle : App
* But : Afficher la liste des applications
*/
//Articles
$C['PageTitle'] = 'Applications Omnilogie';

$C['CanonicalURL'] = '/App';

$C['Apps'] = array(
	'Application Android' => array(
		'dev' => 'Hartok et Neamar',
		'url' => 'https://play.google.com/store/apps/details?id=fr.omnilogie.app',
		'desc' => "L'application Omnilogie pour téléphone Android permet de consulter le site en version mobile.",
		'img' => '/images/app/android.png',
	),
	'Application Blackberry' => array(
		'dev' => 'Hartok et Neamar',
		'url' => 'http://appworld.blackberry.com/webstore/content/89411/?lang=en',
		'desc' => "L'application Omnilogie pour Blackberry Playbook est disponible à partir de la version 2.0.",
		'img' => '/images/app/blackberry.png',
	),
	'Application Windows Phone' => array(
		'dev' => 'Tanguy',
		'url' => 'javascript:alert(\'Bientôt disponible !\');',
		'desc' => "L'application Omnilogie pour Windows Phone est en cours de développement et sera bientôt disponible.",
		'img' => '/images/app/windows.png',
	),
	'Application iOS' => array(
		'dev' => 'Arnaud Ober',
		'url' => 'https://itunes.apple.com/fr/app/omnilogie/id894054389',
		'desc' => "L'application iOS est compatible iPhone et iPad",
		'img' => '/images/app/ios.png',
	)
);
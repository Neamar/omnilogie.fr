<?php
/**
* Classe Util, contenant des fonctions utiles à l'ensemble des pages mais qui ne méritent pas une librairie à part.
* ATTENTION : CE FICHIER N'EST PAS UN DÉPOTOIR. AVANT D'Y AJOUTER UNE FONCTION, RÉFLÉCHIR AUX IMPLICATIONS EN TERMES DE COÛT MÉMOIRE, ET SI LA FONCTION À AJOUTER NE SERAIT PAS MIEUX SUR UN CONTRÔLEUR EXISTANT.
*
*/
//Util

class Util
{
	/**
	* Met à jour les URLs associées avec un article en BDD.
	* Minimise les requêtes en conservant les anciennes URLs qui n'ont pas été modifiées, ce qui complexifie légèrement le code et justifie cette fonction appelée depuis /admin/Edit/ et /membres/Edit/
	* @param Article un objet Omni dont on récupérera les URLs.
	* @param Liens les liens à ajouter, probablement récupérés depuis $_POST.
	*/
	public static function commitUrls(Omni $Article, array $Liens)
	{
		SQL::queryNoFail('DELETE FROM OMNI_More WHERE Reference=' . $Article->ID);

		//La liste des nouveaux liens :
		$NLiens = $Liens;
		foreach($NLiens as &$Lien)
		{
			$Lien = trim(str_replace(array("\n","\r"),'',$Lien));
			if($Lien!='')
				SQL::insert('OMNI_More',array('Reference'=>$Article->ID,'URL'=>trim($Lien)));
		}

		/*
		//Les anciens liens en BDD :
		$OLiens = array_map("stripslashes",array_keys($Article->getURLs()));

		//Liens ajoutés :
		$Added = array_diff($NLiens,$OLiens);

		//Liens enlevés :
		$Subs = array_diff($OLiens, $NLiens);

var_dump($NLiens,$OLiens,$Added,$Subs);
		foreach($Added as $AddedURL)
		{
			$AddedURL = trim($AddedURL);
			if($AddedURL!='')
				SQL::insert('OMNI_More',array('Reference'=>$Article->ID,'URL'=>$AddedURL));
		}
		foreach($Subs as $SubURL)
		{
			SQL::queryNoFail('DELETE FROM OMNI_More WHERE Reference=' . $Article->ID . ' AND URL="' . addslashes($SubURL) . '" LIMIT 1');
			echo 'DELETE FROM OMNI_More WHERE Reference=' . $Article->ID . ' AND URL="' . addslashes($SubURL) . '" LIMIT 1';
			echo mysql_error() . "\n";
		}
		*/

	}

}
<?php
/**
* Classe Util, contenant des fonctions utiles � l'ensemble des pages mais qui ne m�ritent pas une librairie � part.
* ATTENTION : CE FICHIER N'EST PAS UN D�POTOIR. AVANT D'Y AJOUTER UNE FONCTION, R�FL�CHIR AUX IMPLICATIONS EN TERMES DE CO�T M�MOIRE, ET SI LA FONCTION � AJOUTER NE SERAIT PAS MIEUX SUR UN CONTR�LEUR EXISTANT.
*
*/
//Util

class Util
{
	/**
	* Met � jour les URLs associ�es avec un article en BDD.
	* Minimise les requ�tes en conservant les anciennes URLs qui n'ont pas �t� modifi�es, ce qui complexifie l�g�rement le code et justifie cette fonction appel�e depuis /admin/Edit/ et /membres/Edit/
	* @param Article un objet Omni dont on r�cup�rera les URLs.
	* @param Liens les liens � ajouter, probablement r�cup�r�s depuis $_POST.
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

		//Liens ajout�s :
		$Added = array_diff($NLiens,$OLiens);

		//Liens enlev�s :
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
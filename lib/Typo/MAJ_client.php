<?php
define('MASTER','http://neamar.fr/lib/Typo/');

error_reporting(E_ALL);
$Mute=false;

//Ne pas effectuer la M�j sur le serveur maitre
if($_SERVER['SERVER_NAME']=='neamar.fr')
	exit();

function browseFiles($Dir,&$Tree)
{
	$Exclus=array('.','..');
	$dir_handle = opendir($Dir);
	while ($file = readdir($dir_handle))
	{
		if(!in_array($file,$Exclus))
		{
			if(is_dir($file))
				browseFiles($Dir . '/' . $file,$Tree);
			else
			{
				//Remplacer les . par des @ car les . ne passent pas le POST.
				$Key=preg_replace('#\.([a-z]+)#','@$1',str_replace('./',"",$Dir . '/' . $file));
				$Tree[$Key]=filemtime($Dir . '/' . $file);
			}
		}
	}
}

//T�l�charge $URL avec les donn�es $POST demand�es.
function download($URL,$POST=array())
{
	$POST_str="";//La chaine de caract�res du POST mis � plat
	foreach($POST as $k=>$v)
		$POST_str .= urlencode($k) . '=' . urlencode($v) . '&';
	rtrim($POST_str,'&');//Supprimer la derni�re Esperluette

	//Intialiser une connexion avec CURL pour r�cup�rer les donn�es demand�es
	$Connexion = curl_init();
	curl_setopt($Connexion, CURLOPT_URL,MASTER . $URL);
	curl_setopt($Connexion, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($Connexion,CURLOPT_POST,count($POST));
	curl_setopt($Connexion,CURLOPT_POSTFIELDS,$POST_str);
	$retour = curl_exec($Connexion);
	curl_close($Connexion);//Nettoyer
	unset($Connexion);
	return $retour;
}

//Affiche un message
function Out($texte)
{
	global $Mute;
	if(!$Mute)
		echo '<p>' . preg_replace('#{(.+)}#','<strong>$1</strong>',$texte) . "</p>\n";
}

Out("Lancement de la mise � jour du {Typographe}.");
//Liste des fichiers actuellement sur le serveur
$Tree=array();
browseFiles('.',$Tree);
asort($Tree);


///PREMI�RE �TAPE : fichiers modifi�s.
//Pr�parer une requ�te POST :
$Files=unserialize(download('MAJ_server.php?CheckNewVersion',$Tree));
//La requ�te renvoie la liste des fichiers plus r�cents que ceux actuellement sur le serveur
if(count($Files)!=0)
{//Il y a eu des modifs, il va falloir t�l�charger tout �a.
	Out('Le script va effectuer la mise � jour de ' . count($Files) .  ' fichier(s) (' . implode(', ',$Files) . ')');
	///SECONDE �TAPE : repartir sur une arborescence saine
	$Dirs=unserialize(download('MAJ_server.php?GetDirs'));
	Out("Liste des dossiers r�cup�r�e");
	//R�cup�rer tous les nouveaux dossiers et les cr�er si n�cessaire.
	foreach($Dirs as $Dir)
	{
		if(!is_dir($Dir))
		{
			Out("Cr�ation d'un nouveau r�p�rtoire : {" . $Dir . '}');
			mkdir($Dir);
		}
	}

	///TROISI�ME PARTIE : charger chacune des pages n�cessaires.
	foreach($Files as $File)
	{
		Out('T�l�chargement du fichier {' . $File . '}');
		file_put_contents($File,download('MAJ_server.php?GetCode',array('File'=>$File)));
		Out("Fichier {" . $File . '} enregistr�.');
	}
	Out("Fin de la mise � jour.");
}
else
	Out('La version du Typographe install�e sur le serveur {' . $_SERVER['SERVER_NAME'] . '} est � jour.');
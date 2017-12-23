<?php
define('MASTER','http://neamar.fr/lib/Typo/');

error_reporting(E_ALL);
$Mute=false;

//Ne pas effectuer la Màj sur le serveur maitre
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

//Télécharge $URL avec les données $POST demandées.
function download($URL,$POST=array())
{
	$POST_str="";//La chaine de caractères du POST mis à plat
	foreach($POST as $k=>$v)
		$POST_str .= urlencode($k) . '=' . urlencode($v) . '&';
	rtrim($POST_str,'&');//Supprimer la dernière Esperluette

	//Intialiser une connexion avec CURL pour récupérer les données demandées
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

Out("Lancement de la mise à jour du {Typographe}.");
//Liste des fichiers actuellement sur le serveur
$Tree=array();
browseFiles('.',$Tree);
asort($Tree);


///PREMIÈRE ÉTAPE : fichiers modifiés.
//Préparer une requête POST :
$Files=unserialize(download('MAJ_server.php?CheckNewVersion',$Tree));
//La requête renvoie la liste des fichiers plus récents que ceux actuellement sur le serveur
if(count($Files)!=0)
{//Il y a eu des modifs, il va falloir télécharger tout ça.
	Out('Le script va effectuer la mise à jour de ' . count($Files) .  ' fichier(s) (' . implode(', ',$Files) . ')');
	///SECONDE ÉTAPE : repartir sur une arborescence saine
	$Dirs=unserialize(download('MAJ_server.php?GetDirs'));
	Out("Liste des dossiers récupérée");
	//Récupérer tous les nouveaux dossiers et les créer si nécessaire.
	foreach($Dirs as $Dir)
	{
		if(!is_dir($Dir))
		{
			Out("Création d'un nouveau répértoire : {" . $Dir . '}');
			mkdir($Dir);
		}
	}

	///TROISIÈME PARTIE : charger chacune des pages nécessaires.
	foreach($Files as $File)
	{
		Out('Téléchargement du fichier {' . $File . '}');
		file_put_contents($File,download('MAJ_server.php?GetCode',array('File'=>$File)));
		Out("Fichier {" . $File . '} enregistré.');
	}
	Out("Fin de la mise à jour.");
}
else
	Out('La version du Typographe installée sur le serveur {' . $_SERVER['SERVER_NAME'] . '} est à jour.');
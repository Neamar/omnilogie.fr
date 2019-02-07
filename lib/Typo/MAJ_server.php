<?php
clearstatcache();
$DistantTree=$_POST;

//Liste des fichiers
function browseFiles($Dir,&$Tree)
{
	$Exclus=array('.','..','.kw','Aide','Stats.txt','Aide/Stats.txt','index.php','MAJ_server.php');

	$dir_handle = opendir($Dir);
	while ($file = readdir($dir_handle))
	{
		if(!in_array($file,$Exclus))
		{
			if(is_dir($file))
				browseFiles($Dir . '/' . $file,$Tree);
			else
				$Tree[str_replace('./','',$Dir . '/' . $file)]=filemtime($Dir . '/' . $file);
		}
	}
}

$Tree=array();
browseFiles('.',$Tree);
asort($Tree);

if(isset($_GET['CheckNewVersion']))
{//Voir si de nouveaux fichiers sont disponibles
	$ListeNouveau=array();
	foreach($Tree as $File=>$Time)
	{
		$DistantFile=preg_replace('#\.([a-z]+)#','@$1',$File);
		if(!isset($DistantTree[$DistantFile]) || $DistantTree[$DistantFile]<$Time)
		{//Si le fichier n'existe pas encore, ou s'il a Ã©tÃ© modifiÃ© rÃ©cemment.
			$ListeNouveau[]=$File;
		}
	}
	echo serialize($ListeNouveau);
}
elseif(isset($_GET['GetDirs']))
{//Voir si de nouveau rÃ©pÃ©ertoires ont Ã©tÃ© crÃ©es
	//Liste des rÃ©pÃ©rtoires
	function browseDir($Dir,&$Tree)
	{
		$dir_handle = opendir($Dir);
		while ($file = readdir($dir_handle))
		{
			if(is_dir($Dir . '/' . $file) && $file!='.' && $file!='..')
		{
			$file=$Dir . '/' . $file;
			$Tree[]=str_replace('./','',$file);
			browseDir($file,$Tree);
		}
		}
		closedir($dir_handle);
	}
	$Dirs=array();
	browseDir('.',$Dirs);
	asort($Dirs);
	echo serialize($Dirs);
}
elseif(isset($_GET['GetCode']) && isset($_POST['File']) && isset($Tree[$_POST['File']]))
{//Renvoyer un code source
	echo file_get_contents($_POST['File']);
}
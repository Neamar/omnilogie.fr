<?php
/**
* Fichier d'�v�nement g�n�rique
*
* @standalone
* @access admins
* Mettre � jour le menu listant les �v�nements disponibles pours les administrateurs
* Mettre � jour ce menu
*/

$EventsFiles = glob(PATH . '/E/*/*.php');

$Standalone = array();
foreach($EventsFiles as $EventFile)
{
	$File = file_get_contents($EventFile);
	//Le fichier a-t-il le flag standalone ?
	if(strpos($File,'@standalone')!==false)
	{
		//Trouver l'�venement associ�
		preg_match('`/(?P<event>[^/]+)/(?P<file>[^/]+)\.php$`',$EventFile,$EventType);
		preg_match('`@access (.+)\s`',$File,$Access);
		preg_match("`\* (.+)\n\*/`",$File,$Description);
		if(!isset($Standalone[$EventType['event']]))
			$Standalone[$EventType['event']]=array();

		$Standalone[$EventType['event']][str_replace(PATH,'',$EventFile)] = array(
			'File'=>$EventFile,
			'Description'=>$Description[1],
			'Access'=>(isset($Access[1])?explode(',',$Access[1]):array('any')),
		);
	}
}

Cache::set('Datas','Events',serialize($Standalone));
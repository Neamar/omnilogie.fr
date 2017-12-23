<?php
/**
* But : Rediriger vers un article depuis l'URL courte du type /A0
*/
$ID = intval(base_convert(preg_replace('`[^A-Z0-9]`','',$_GET['Titre']),35,10));

if($ID==0)
	Debug::fail('Erreur de contrôle.');

$Param = Omni::buildParam();
$Param->Where = 'ID = ' . $ID;

$Article = Omni::get($Param);
if(count($Article)==0)
{
	Debug::status(404);
	$_GET['P'] = 'erreur';
}
else
{
	$Article = $Article[0];
	Debug::redirect(Link::omni($Article->Titre));
}
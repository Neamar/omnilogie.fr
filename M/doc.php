<?php
include_once(PATH . '/../../lib/PhpDoc/PhpDoc.php');

$C['PageTitle'] = 'Documentation des classes';
$C['CanonicalURL'] = '/Doc';
$C['SubTitle'] = 'Aide à la programmation d\'Omnilogie';
$C['Author'] = 'Neamar';
$C['PubDateReadable'] = '27 avril 2010';
$C['PubDate'] = date('Y-m-d',time());
$C['Menus'] = array();
$C['File']= array();

$Handle=opendir(PATH . '/C/lib/');

while (($File = readdir($Handle))!==false) {
	if($File!='.' && $File!='..')
		$C['File'][$File] = PATH . '/C/lib/' . $File;
}

closedir($Handle);
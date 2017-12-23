<?php
/**
* Modèle : admin/logs
* But : charger les logs
* Prérequis : aucun.
*/

$C['PageTitle']='Logs d\'Omnilogie';
$C['CanonicalURL']='/admin/Logs';

$C['LogsFile'] = array();

$Files = array_reverse(glob(PATH . '/L/*.log'));
foreach($Files as $File)
{
	$File = str_replace(PATH . '/L/','',$File);
	$C['LogsFile'][$File] = str_replace('.log','',$File);
}

if(!isset($_POST['file']))
	$_POST['file'] = date('Y-m-d');
$_POST['file'] = preg_replace('#[^0-9-]#','',$_POST['file']);

$C['Date'] = $_POST['file'];
$C['Logs'] = file(PATH . '/L/' . $_POST['file'] . '.log',FILE_IGNORE_NEW_LINES);

$Couleurs = array(
	'Envoi de mail'=>'<span style="color:blue;">Envoi de mail</span>',
	'Dispatch'=>'<span style="color:green;">Dispatch</span>',
	'==Statut 40'=>'<span style="color:red;">==Statut 40</span>',
);

$S = array_keys($Couleurs);
$R = array_values($Couleurs);
foreach($C['Logs'] as &$Log)
{
	$Log = str_replace($S,$R,$Log);

	$Log = explode('	',$Log);
}
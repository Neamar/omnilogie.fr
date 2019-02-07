<?php
/**
* Modèle : flux
* But : Générer le flux XML du site
* Données à charger :
* - Derniers articles
* Spécial : la vue est directement appelée par le modèle, afin de ne pas charger le template
*/

//Récupérer les articles
$Logs = SQL::query('SELECT OMNI_Modifs.ID,Titre, Modification, OMNI_Auteurs.Auteur, UNIX_TIMESTAMP(Date) AS Timestamp
FROM OMNI_Modifs
LEFT JOIN OMNI_Omnilogismes ON (OMNI_Omnilogismes.ID=OMNI_Modifs.Reference)
LEFT JOIN OMNI_Auteurs ON (OMNI_Auteurs.ID=OMNI_Modifs.Auteur)
ORDER BY OMNI_Modifs.ID DESC
LIMIT 10');

$C['Modifs']=array();
while($Log=mysql_fetch_assoc($Logs))
{
	$C['Modifs'][] = array(
		'title'=>'« ' . substr($Log['Titre'],0,20) . ' » : ' . $Log['Modification'],
		'author'=>$Log['Auteur'],
		'pubDate'=> date('r',$Log['Timestamp']),
		'link'=>Link::omni($Log['Titre']),
		'guid'=>'omni-modifs-' . md5($Log['ID']),
		'description'=>$Log['Titre'] . ' : ' .  $Log['Modification'] . ' par ' . $Log['Auteur'],
	);
}

include(PATH . '/V/admin/flux.php');
exit();
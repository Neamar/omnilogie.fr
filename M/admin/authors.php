<?php
/**
* ModÃ¨le : admin/datas
* But : afficher tout plein de données inutiles :D
*/
$C['PageTitle']='Liste des membres';
$C['CanonicalURL']='/admin/Omnilogistes';


$mails=SQL::query('SELECT OMNI_Auteurs.Auteur,Mail,COUNT(DISTINCT OMNI_Omnilogismes.ID) AS Nb
FROM OMNI_Auteurs
LEFT JOIN OMNI_Omnilogismes ON (OMNI_Omnilogismes.Auteur=OMNI_Auteurs.ID)
GROUP BY OMNI_Auteurs.ID');
$Membres=array();
while($mail=mysql_fetch_assoc($mails))
	$Membres[]='<a href="mailto:' . $mail['Mail'] . '">' . $mail['Auteur'] . '</a> (' . $mail['Nb'] . ') ;<br /><small>(&rarr; <a href="passerPour-' . $mail['Auteur'] . '" class="lienDiscret">se faire passer pour</a>)</small>';

$C['Membres'] = Formatting::makeList($Membres,'ol','','omnilogistes_participants');
<?php
/**
* Fichier d'�v�nement
* Event::CHANGEMENT_GENERIQUE
*
* @standalone
* Mettre � jour le pod "auteurs actifs r�cemment"
*/

$Datas = SQL::query(
'SELECT DISTINCT OMNI_Auteurs.Auteur
FROM OMNI_Modifs
LEFT JOIN OMNI_Auteurs ON (OMNI_Auteurs.ID=OMNI_Modifs.Auteur)
WHERE Date > SUBDATE(NOW(),2)
ORDER BY Date DESC');

$Contenu=array();
while($Data=mysql_fetch_assoc($Datas))
	$Contenu[] = str_replace('rel="author"', '', Anchor::author($Data['Auteur']));

Cache::set('Pods','activeAuthor',Formatting::makeList($Contenu));
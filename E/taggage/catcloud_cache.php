<?php
/**
* Fichier d'évènement
* Event::TAGGAGE
*
* @standalone
* @access taggers
* Recalcule le tableau contenant la taille des différentes catégories dans le pod "nuage de catégories"
* Mettre à jour le pod "nuage de catégories"
*/

$Datas = SQL::query(
'SELECT Categorie, COUNT(*) AS Nb
FROM OMNI_Liens
GROUP BY Categorie');

$Tableau=array();
while($Data=mysql_fetch_assoc($Datas))
{
	$Taille = min(4.5, max(.7, 2*$Data['Nb']/CAT_CLOUD_BIG_SIZE));
	$Tableau[$Data['Categorie']] = '<span style="font-size:' . $Taille . 'em' . '">' . Anchor::category($Data['Categorie']) . '</span> ';
}

Cache::set('Datas','catCloud',serialize($Tableau));
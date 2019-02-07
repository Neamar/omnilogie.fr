<?php
/**
* Modèle : arbre
* But : ajouter une catégorie
*/

$C['PageTitle']='Gestion de l\'arbre';
$C['CanonicalURL']='/admin/Arbre';

$Arborescence=mysql_query('SELECT Cat.Borne_D, Cat.Categorie, COALESCE(GROUP_CONCAT(Heritage.Categorie ORDER BY Heritage.Borne_G),"@") AS Heritage
FROM OMNI_Categories Cat
LEFT JOIN OMNI_Categories Heritage ON (Cat.BORNE_G>Heritage.Borne_G AND Cat.Borne_D<Heritage.Borne_D AND Heritage.Borne_G<>1)

GROUP BY Cat.ID

ORDER BY Cat.Categorie');

$C['ListeAjout'] = array();

while($Feuille=mysql_fetch_assoc($Arborescence))
{
	$Path = str_replace(',','=',$Feuille['Heritage']) . '=' . $Feuille['Categorie'];
	if(isset($Path[70]))
		$Path = str_replace(substr($Path,20,40),' (&hellip;) ',$Path);
	$C['ListeAjout'][$Feuille['Borne_D']] = str_replace('=','&rarr;',$Path);
}

asort($C['ListeAjout']);

/*
$Arborescence=mysql_query('SELECT Cat.ID, Cat.Categorie, COALESCE(GROUP_CONCAT(Heritage.Categorie ORDER BY Heritage.Borne_G),"@") AS Heritage
FROM OMNI_Categories Cat
LEFT JOIN OMNI_Categories Heritage ON (Cat.BORNE_G>Heritage.Borne_G AND Cat.Borne_D<Heritage.Borne_D AND Heritage.Borne_G<>1)
WHERE Cat.Categorie NOT IN (SELECT DISTINCT Categorie FROM OMNI_Liens)
GROUP BY Cat.ID

ORDER BY Cat.Categorie');

$C['ListeSuppression'] = array();

while($Feuille=mysql_fetch_assoc($Arborescence))
{
	$Path = str_replace(',','=',$Feuille['Heritage']) . '=' . $Feuille['Categorie'];
	if(isset($Path[70]))
		$Path = str_replace(substr($Path,20,40),' (&hellip;) ',$Path);
	$C['ListeSuppression'][$Feuille['ID']] = str_replace('=','&rarr;',$Path);
}

asort($C['ListeSuppression']);*/

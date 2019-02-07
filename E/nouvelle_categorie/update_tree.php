<?php
/**
* Fichier d'évènement
* Event::NOUVELLE_CATEGORIE
*
* @standalone
* @access taggers
* Rafraîchir l'arbre de données des catégories
*/

//MAJ du fichier avec l'arborescence.
$Arborescence=mysql_query('SELECT Cat.ID, Cat.Categorie, GROUP_CONCAT(Heritage.Categorie ORDER BY Heritage.Borne_G) AS Heritage
FROM OMNI_Categories Cat
LEFT JOIN OMNI_Categories Heritage ON (Cat.BORNE_G>Heritage.Borne_G AND Cat.Borne_D<Heritage.Borne_D AND Heritage.Borne_G<>1)

GROUP BY Cat.ID

ORDER BY Cat.Categorie');

$Liste=array();
while($Feuille=mysql_fetch_assoc($Arborescence))
{
	if(!isset($Liste[$Feuille['Categorie']]))
		$Liste[$Feuille['Categorie']]=array();

	if($Feuille['Heritage']!='')
		$Liste[$Feuille['Categorie']][]=explode(',',$Feuille['Heritage']);
}
file_put_contents(DATA_PATH . 'categories',serialize($Liste));

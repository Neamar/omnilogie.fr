<?php
/**
* Layout :
* - Article
* Menu : répartition par statut
*/
//Afficher les articles

?>
<h3 id="h3_toc"><span>Liste des articles <?php Template::put('PageActuelle');?></span></h3>
<p>Vous trouverez dans ces pages la liste des articles parus, classés par date de sortie.<br />
Si vous recherchez un article en particulier, nous vous conseillons le formulaire de recherche, disponible à droite de cet encadré !</p>

<?php
//Module de pagination (si nécessaire)
Template::put('Pager');
//Articles
foreach($C['Articles'] as $Article)
{
	echo $Article['Teaser'];
}
//Module de pagination (si nécessaire)
Template::put('Pager');
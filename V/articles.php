<?php
/**
* Layout :
* - Article
* Menu : r�partition par statut
*/
//Afficher les articles

?>
<h3 id="h3_toc"><span>Liste des articles <?php Template::put('PageActuelle');?></span></h3>
<p>Vous trouverez dans ces pages la liste des articles parus, class�s par date de sortie.<br />
Si vous recherchez un article en particulier, nous vous conseillons le formulaire de recherche, disponible � droite de cet encadr� !</p>

<?php
//Module de pagination (si n�cessaire)
Template::put('Pager');
//Articles
foreach($C['Articles'] as $Article)
{
	echo $Article['Teaser'];
}
//Module de pagination (si n�cessaire)
Template::put('Pager');
<?php
/**
* Layout :
* - Arbre entourant
* - Articles dans la catégorie
*/

//Afficher la catégorie
?>
<h3><span>La page des catégories</span></h3>
<h1><?php Template::put('Category') ?></h1>
<?php Template::put('AroundTree') ?>

</section>

<section id="article-from">
<h3><span>Articles dans <?php Template::put('Category') ?> <?php Template::put('PageActuelle'); ?></span></h3>
<?php
if(isset($C['Articles']))
{
	//Module de pagination (si nécessaire)
	Template::put('Pager');
	//Articles dans la catégorie
	foreach($C['Articles'] as $Article)
	{
		echo $Article['Teaser'];
	}
	//Module de pagination (si nécessaire)
	Template::put('Pager');
}
else
{?><p>Aucun article dans cette catégorie.</p><?php }?>

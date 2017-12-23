<?php
/**
* Layout :
* - Arbre entourant
* - Articles dans la cat�gorie
*/

//Afficher la cat�gorie
?>
<h3><span>La page des cat�gories</span></h3>
<h1><?php Template::put('Category') ?></h1>
<?php Template::put('AroundTree') ?>

</section>

<section id="article-from">
<h3><span>Articles dans <?php Template::put('Category') ?> <?php Template::put('PageActuelle'); ?></span></h3>
<?php
if(isset($C['Articles']))
{
	//Module de pagination (si n�cessaire)
	Template::put('Pager');
	//Articles dans la cat�gorie
	foreach($C['Articles'] as $Article)
	{
		echo $Article['Teaser'];
	}
	//Module de pagination (si n�cessaire)
	Template::put('Pager');
}
else
{?><p>Aucun article dans cette cat�gorie.</p><?php }?>

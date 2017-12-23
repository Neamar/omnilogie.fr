<?php
/**
* Layout :
* - Arbre entourant
* - Articles dans la cat�gorie
*/

//Afficher la cat�gorie
?>
<h3 id="author-page"><span>La page des auteurs</span></h3>
<h1><?php Template::put('Author') ?></h1>
<article role="main" class="presentation">
	<aside class="roles"><?php Template::put('AuthorRole') ?></aside>
	<aside class="mail"><?php Template::put('Mail') ?></aside>
	<?php Template::put('Histoire') ?>
	<?php if(strlen($C['GooglePlus']) > 0) {?>
	<p class="message">Sur Google+&nbsp;: <a rel="me" href="<?php Template::put('GooglePlus') ?>?rel=author"><?php Template::put('Author') ?></a></p>
	<?php } ?>
</article>

<?php if(!isset($C['FirstAction'])){?>
<p><?php Template::put('Author') ?> est inscrit sur Omnilogie, mais il n'a jamais particip�... ce qui est bien dommage ! Si vous le connaissez, pourquoi ne pas le remotiver ?</p>
<?php } else {?>

</section>

<section id="last-activity">
<h3><span>Derni�res activit�s</span></h3>
<p class="activity"><?php Template::put('Author') ?> est actif depuis le <?php Template::put('FirstAction') ?> sur <a href="/">Omnilogie</a>, et sa derni�re action remonte au <?php Template::put('LastAction') ?> <?php Template::put('LastActionInDays') ?>.</p>

<?php Template::put('Actions'); ?>

</section>

<section id="articles-from">
<h3><span>Articles r�dig�s par <?php Template::put('Author') ?> <?php Template::put('PageActuelle') ?></span></h3>
<?php
if(isset($C['Articles']))
{
	//Module de pagination (si n�cessaire)
	Template::put('Pager');
	//Articles de l'auteur
	foreach($C['Articles'] as $Article)
	{
		echo $Article['Teaser'];
	}
	//Module de pagination (si n�cessaire)
	Template::put('Pager');
}
else
{ ?>
<p><?php Template::put('Author') ?> n'a r�dig� aucun article, c'est un membre sp�cial !</p>
<?php }
}
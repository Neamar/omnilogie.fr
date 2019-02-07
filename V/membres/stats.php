<?php
/**
* Vue : membres/stats
* Layout : voir des stats !
*/
?>

<h3><span>Statistiques sur le membre</span></h3>

<?php if(!defined('NOOB_MODE')) { ?>
<p>Liste de vos articles, avec le nombre de visionnages.<br />
Un visionnage correspond à un affichage de l'article en entier.</p>

<p><?php Template::put('StatsAuteur'); ?></p>
<p><?php Template::put('StatsAuteur2'); ?></p>
<p><?php Template::put('HeureModif'); ?></p>
<p><?php Template::put('JourModif'); ?></p>
<p><?php Template::put('TypeModif'); ?></p>

</section>
<hr />

<section>
<h3><span>Statistiques sur les articles</span></h3>
<p>Total : <?php Template::put('StatsTotal'); ?> articles visionnés.<br />Liste décroissante triée selon le nombre de vues.</p>

<?php echo Formatting::makeList($C['StatsArticles']) ?>

<h3><span>Statistiques sur les articles, par jour</span></h3>
<p>Nombre de vues journaliers de l'article depuis sa parution.</p>
<?php echo Formatting::makeList($C['StatsArticlesPerDay']) ?>

<?php }/*if(!defined('NOOB_MODE')*/ ?>
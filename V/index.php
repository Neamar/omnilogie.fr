<?php
/**
* Modèle : index.php
* But : Afficher la page d'accueil
* Layout :
* - Article du jour
* - Articles précédents *4
*/

//Article du jour
/*
<h3 id="article-of-the-day"><span>En grève ?</span></h3>
<header class="message">Par pénurie d'article, Omnilogie est au chômage technique.<br />
Nous faisons donc une pause jusqu'au 24 décembre avant de repartir, en espérant voir le stock revenu à un niveau correct.<br /><br />

Cela fait maintenant presque un mois que l'article de minuit est écrit dans l'après-midi. Cette situation est trop difficile à conjuguer avec les impératifs de la vie, et est la raison de cette interruption que nous espérons momentanée.<br /><br />
À la reprise, il est possible que nous passions à un rythme plus facile à soutenir (par exemple, un article toutes les 48h) ou que nous modifions la formule afin qu'Omnilogie ne soit pas une corvée, mais un plaisir pour tous.<br /><br />

Rédacteurs, n'hésitez pas à écrire pendant cette trêve hivernale !</p>
*/
?>

<h3 id="article-of-the-day"><span>Au hasard...</span></h3>

<?php Template::put('Header') ?>

<article role="main" class="omnilogisme">
	<?php Template::put('Contenu') ?>
</article>

<span id="learn-more"><a href="<?php Template::put('LienDuJour'); ?>">En savoir plus&hellip;</a></span>
</section>

<hr />

<section>
<h3><span>Publicité</span></h3>

<div style="text-align:center;">
<script type="text/javascript"><!--
google_ad_client = "pub-4506683949348156";
google_ad_slot = "5567196542";
google_ad_width = 468;
google_ad_height = 60;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
</div>

</section>

<hr />

<section id="previous-article">
<h3><span>Les articles précédents</span></h3>
<?php
//Articles précédents :
foreach($C['Articles'] as $Article)
{
?>

<article class="omni_start">
<?php echo $Article['Header'] ?>

<div class="omnilogisme">
	<?php echo $Article['Contenu'] ?>
	<p class="read-more"><a href="<?php echo $Article['ReadMore']; ?>">Lire la suite&hellip;</a></p>
</div>
</article>
<?php
}

if(count($C['UnAn']) > 0)
{
?>
</section>

<hr />

<section>
<h3><span>Il y a un an sur Omnilogie...</span></h3>
<?php
echo $C['UnAn']['Header'] ?>

<div class="omnilogisme">
	<?php echo $C['UnAn']['Contenu'] ?>
	<p class="read-more"><a href="<?php echo $C['UnAn']['ReadMore']; ?>">Lire la suite&hellip;</a></p>
</div>
<?
}

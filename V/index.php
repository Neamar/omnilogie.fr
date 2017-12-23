<?php
/**
* Mod�le : index.php
* But : Afficher la page d'accueil
* Layout :
* - Article du jour
* - Articles pr�c�dents *4
*/

//Article du jour
/*
<h3 id="article-of-the-day"><span>En gr�ve ?</span></h3>
<header class="message">Par p�nurie d'article, Omnilogie est au ch�mage technique.<br />
Nous faisons donc une pause jusqu'au 24 d�cembre avant de repartir, en esp�rant voir le stock revenu � un niveau correct.<br /><br />

Cela fait maintenant presque un mois que l'article de minuit est �crit dans l'apr�s-midi. Cette situation est trop difficile � conjuguer avec les imp�ratifs de la vie, et est la raison de cette interruption que nous esp�rons momentan�e.<br /><br />
� la reprise, il est possible que nous passions � un rythme plus facile � soutenir (par exemple, un article toutes les 48h) ou que nous modifions la formule afin qu'Omnilogie ne soit pas une corv�e, mais un plaisir pour tous.<br /><br />

R�dacteurs, n'h�sitez pas � �crire pendant cette tr�ve hivernale !</p>
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
<h3><span>Publicit�</span></h3>

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
<h3><span>Les articles pr�c�dents</span></h3>
<?php
//Articles pr�c�dents :
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

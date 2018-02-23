<?php
/**
* Layout :
* - Article
* - Sources
* - Anecdote
* - Articles similaires
* Menu : saga de l'article
*/

//Afficher l'article
?>
<h3><span>La page des articles</span></h3>

<article>
<?php Template::put('Header') ?>

<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<ins class="adsbygoogle desktop-ad"
     style="text-align:center;"
     data-ad-layout="in-article"
     data-ad-format="fluid"
     data-ad-client="ca-pub-4506683949348156"
     data-ad-slot="2991235901"></ins>
<script>
     (adsbygoogle = window.adsbygoogle || []).push({});
</script>

<div role="main" class="omnilogisme">
	<?php Template::put('Precedent') ?>
	<?php Template::put('Contenu') ?>
	<?php Template::put('Suivant') ?>
</div>

<?php
if($C['Categories']!='')
{?>
<aside role="note" class="categories">
	<?php Template::put('Categories') ?>
</aside>
<?php }?>

<?php
if(count($C['URLs'])!=0)
{?>
<aside role="note" class="more-links">
<p>Sources, r�f�rences et liens pour en savoir plus :</p>
<ul>
<?php
foreach($C['URLs'] as $URL=>$Title)
	if(preg_match('$^https?://$', $URL)){?>


	<li style="list-style-image:url('https://www.google.com/s2/favicons?domain=<?php echo Link::getHost($URL); ?>');"><a href="<?php echo $URL; ?>" style="margin-left:5px;"><?php echo $Title; ?></a></li>
<?php } else {?>
	<li style="list-style-image:url('https://www.google.com/s2/favicons')"><?php echo $Title; ?></li>
<?php } ?>
</ul>
</aside>
<?php
}
?>



<?php
if(isset($C['DidYouKnow']))
{?>
<aside role="complementary" id="did-you-know">
	<h3><span>Le petit plus pour briller en soci�t�</span></h3>
	<?php Template::put('DidYouKnow') ?>
	<?php if($C['DidYouKnowSource']!=''){?><p class="source-anecdote"><a href="<?php echo Template::put('DidYouKnowSource') ?>">(source)</a></p><?php }?>
</aside>
<?php }?>
</article>
</section>


<section id="similar">
<?php
if(!is_null($C['Similar']))
{?>
<h3><span>Ces articles vous plairont s�rement :</span></h3>
<aside role="note" class="similar">
<?php echo Formatting::makeList($C['Similar']) ?>
</aside>
<?php } ?>

<hr class="coupure-articles-entourants" />

<?php
if(isset($C['Veille'])) {?>
<p class="article-veille">Article pr�c�dent : <?php Template::put('Veille'); ?></p>
<?php }
if(isset($C['Lendemain'])) {?>
<p class="article-lendemain">Article suivant : <?php Template::put('Lendemain'); ?></p>
<?php }
?>


<!-- Essai commentaires -->
</section>

<hr />

<section id="comments">
<h3><span>Envie de vous exprimer ?</span></h3>
<div class="fb-comments" data-href="omnilogie.fr<?php echo str_replace('?', '%3F', $C['CanonicalURL']) ?>" data-num-posts="50" data-width="690" og:title="<?php echo $C['Title'] ?>"></div>
</section>

<script src="https://connect.facebook.net/en_US/all.js#xfbml=1" async></script>

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
<h3><span>Statistiques</span></h3>
<p>Cette page regroupe de nombreuses statistiques à l'utilité plus que contestable.</p>
<ol>
<?php
foreach($C['Stats'] as $Titre=>$Section)
{?>

	<li><a href="#<?php echo preg_replace('#[^a-zA-Z]#','_',$Titre) ?>"><?php echo $Titre; ?></a>
	<ol>
		<?php
		foreach($Section as $SousTitre=>$SousSection)
		{ ?>
			<li><a href="#<?php echo preg_replace('#[^a-zA-Z]#','_',$SousTitre) ?>"><?php echo $SousTitre; ?></a>
		<?php } ?>
	</ol>
	</li>
<?php } ?>
</ol>

<?php
foreach($C['Stats'] as $Titre=>$Section)
{?>
	</section>
	<section>
	<h3 id="<?php echo preg_replace('#[^a-zA-Z]#','_',$Titre) ?>"><span><?php echo $Titre; ?></span></h3>
	<?php
	foreach($Section as $SousTitre=>$SousSection)
	{ ?>
		<h4 id="<?php echo preg_replace('#[^a-zA-Z]#','_',$SousTitre) ?>"><?php echo $SousTitre ?></h4>
		<?php
		foreach($SousSection as $Partie)
			echo $Partie;
	}
}?>
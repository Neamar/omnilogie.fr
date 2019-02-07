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
<h3 id="h3_authors"><span>Omnilogistes participants</span></h3>

<h4 id="code_couleur">Code couleur des auteurs</h4>
<p>Omnilogie se sert d'un code couleur pour identifier les membres disposant de pouvoirs supérieurs à la normale ; voici l'explication des couleurs :</p>

<ul id="liste_code_couleur">
<?php foreach($C['Roles'] as $Role=>$Explication) { ?>
<li><strong class="<?php echo $Role; ?>"><?php echo $Role; ?></strong> : <?php echo $Explication ?></li>
<?php } ?>
</ul>




<h1>Omnilogistes participants</h1>
<ol id="omnilogistes_participants">
<?php foreach($C['AuthorList'] as $Author) { ?>
<li><?php echo $Author['Auteur']; ?> (<?php echo $Author['Total'] ?>) :
	<ul>
	<?php foreach($Author['Details'] as $Status) { ?>
		<li><?php echo $Status; ?></li>
	<?php } ?>
	</ul>
	</li>
<?php } ?>
</ol>
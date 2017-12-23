<?php
/**
* Layout :
* - Vote
*/
//Voter pour l'article du mois

?>
<h3><span>Votez pour l'article du mois !</span></h3>

<p>Jusqu'à samedi minuit, Omnilogie vous propose de sélectionner le meilleur article du mois de <strong><?php Template::put('DateTop');?></strong>.<br />
L'article sélectionné ira rejoindre "les tops", et se verra bénéficier d'une mise en avant particulière sur le site et sur l'application Android.</p>

<p>Les nouveaux venus sur le site pourront ainsi découvrir la fine fleur de nos articles, et les anciens reliront avec plaisir des omnilogismes qui n'ont pas pris une ride !</p>

<?php
if(isset($_SESSION['hasVoted']))
{ ?>
	<p class="message">Votre vote a bien été pris en compte, merci !</p>
<?php } else { ?>
<form method="post" action="">
<select id="vote" name="vote">
	<option value="0">Faites votre choix...</option>
<?php
$count = 1;
foreach($C['Vote'] as $ID => $Titre)
{
	echo '	<option value="' . $ID . '">' . str_pad($count++, 2, "0", STR_PAD_LEFT) . ' ' . $Titre . '</option>' . "\n";
}
?>
</select>
<input type="submit" value="Voter" />
</form>
<?php } ?>
<hr />
</section>

<section>
<style type="text/css">
body { counter-reset:date;}
.omni_teaser hgroup h1:before { counter-increment:date; content: counter(date) "/<?php echo Top::$monthAbridged; ?>" }
</style>

<h3 id="h3_toc"><span>Articles de <?php Template::put('DateTop');?></span></h3>
<p>Voici pour rappel la liste des articles parus en  <?php Template::put('DateTop');?>, classés par date de parution.</p>

<?php
//Articles
foreach($C['Articles'] as $Article)
{
	echo $Article['Teaser'];
}
?>
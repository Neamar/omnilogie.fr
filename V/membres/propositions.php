<?php
/**
* Vue : membres/Propositions
* Layout : Ajout
* Propositions libres
* Propositions r�serv�es
*
*/
?>

<h3 id="h3_propositions"><span>Propositions d'articles</span></h3>
<ol>
<li><a href="#h3_propositions">Ajout de proposition</a></li>
<li><a href="#h3_propositions_libres">Propositions disponibles</a></li>
<li><a href="#h3_propositions_reservees">Consulter les propositions r�serv�es</a></li>
</ol>

<form method="post" action="">
<fieldset>
<legend>Ajout d'une proposition</legend>
<label for="proposition">Votre id�e :</label><br />
<textarea name="proposition" id="proposition" required></textarea><br />

<label for="lien">Un lien pour en savoir plus :</label>
<input type="url" name="lien" id="lien" /><br />

<input type="submit" value="Enregistrer la proposition" onclick="this.value='Enregistrement en cours...';"/>
</fieldset>

</section>

<section>
<h3 id="h3_propositions_libres"><span>Propositions disponibles</span></h3>
<ul id="ul_propositions_libres">
<?php
foreach($C['PropositionsLibres'] as $ID=>$Prop)
{?>
<li>
	<p><a href="/membres/Propositions-<?php echo $ID; ?>">R�server cette id�e</a> ?</p>
	<div class="proposition"><?php echo $Prop['Proposition']; ?></div></li>
<?php } ?>
</ul>
</section>

<section>
<h3 id="h3_propositions_reservees"><span>Propositions r�serv�es</span></h3>
<ul id="ul_propositions_reservees">
<?php
foreach($C['PropositionsReservees'] as $ID=>$Prop)
{?>
<li>
	<p>Id�e r�serv�e par <?php echo Anchor::author($Prop['ReservePar']); ?></p>
	<div class="proposition"><?php echo $Prop['Proposition']; ?></div></li>
<?php } ?>
</ul>

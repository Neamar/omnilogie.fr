<?php
/**
* Vue : admin/Edit
* Layout : Trailer des articles � corriger
*/
?>
<h3><span>Liste d'articles non parus</span></h3>
<p>Amis censeurs, n'h�sitez pas � consulter <a href="/images/guide_censeur_omnilogie.pdf">le guide du censeur Omnilogique</a> !</p>

<hr />
<h2>Articles � corriger</h2>
<?php echo Formatting::makeList($C['ACorriger']) ?>

<hr />

<h2><span>Articles � relire</span></h2>
<?php echo Formatting::makeList($C['Indetermines']) ?>


<hr />

<h2><span>Articles en parution imminente</span></h2>
<?php echo Formatting::makeList($C['Acceptes']) ?>


<hr />

<h2><span>Articles corrig�s par un censeur, pas encore valid�s</span></h2>
<?php echo Formatting::makeList($C['Corriges']) ?>
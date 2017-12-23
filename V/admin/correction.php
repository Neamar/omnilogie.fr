<?php
/**
* Vue : admin/Edit
* Layout : Trailer des articles à corriger
*/
?>
<h3><span>Liste d'articles non parus</span></h3>
<p>Amis censeurs, n'hésitez pas à consulter <a href="/images/guide_censeur_omnilogie.pdf">le guide du censeur Omnilogique</a> !</p>

<hr />
<h2>Articles à corriger</h2>
<?php echo Formatting::makeList($C['ACorriger']) ?>

<hr />

<h2><span>Articles à relire</span></h2>
<?php echo Formatting::makeList($C['Indetermines']) ?>


<hr />

<h2><span>Articles en parution imminente</span></h2>
<?php echo Formatting::makeList($C['Acceptes']) ?>


<hr />

<h2><span>Articles corrigés par un censeur, pas encore validés</span></h2>
<?php echo Formatting::makeList($C['Corriges']) ?>
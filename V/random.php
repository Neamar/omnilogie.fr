<?php
/**
* Layout :
* - Article
*/
//Afficher les articles

?>
<h3 id="h3_toc"><span>Articles au hasard</span></h3>
<p>Vous trouverez ici 20 articles sélectionnés au hasard.<br />
Ils ne vous plaisent pas ? <a href="/Random">Cliquez pour recharger !</a></p>

<?php
foreach($C['Articles'] as $Article)
{
	echo $Article['Teaser'];
}
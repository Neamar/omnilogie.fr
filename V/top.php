<?php
/**
* Layout :
* - Article
* Menu : répartition par statut
*/
//Afficher les articles
?>
<h3 id="h3_toc"><span>Top des articles</span></h3>
<p>Cette page affiche le meilleur article de chaque mois, tel qu'il a été voté par les internautes.<br />
N'hésitez pas à <a href="/Vote">donner votre opinion pour élire le meilleur article du mois dernier</a> !</p>

<?php
//Articles
foreach($C['Articles'] as $Article)
{
	echo $Article->outputTeaser();
}
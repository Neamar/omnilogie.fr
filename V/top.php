<?php
/**
* Layout :
* - Article
* Menu : r�partition par statut
*/
//Afficher les articles
?>
<h3 id="h3_toc"><span>Top des articles</span></h3>
<p>Cette page affiche le meilleur article de chaque mois, tel qu'il a �t� vot� par les internautes.<br />
N'h�sitez pas � <a href="/Vote">donner votre opinion pour �lire le meilleur article du mois dernier</a> !</p>

<?php
//Articles
foreach($C['Articles'] as $Article)
{
	echo $Article->outputTeaser();
}
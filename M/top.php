<?php
/**
* Modèle : Articles
* But : Afficher la liste des articles
* Données à charger : tous les articles, sous forme de teaser.
* Liste des statuts en specialPods
*/
$C['PageTitle'] = 'Meilleurs articles d\'Omnilogie.fr';

$C['CanonicalURL'] = '/Top';


$query = "
SELECT Omnilogismes.ID, Titre, Accroche, NbVotes, Auteurs.Auteur
FROM OMNI_Omnilogismes Omnilogismes
LEFT JOIN OMNI_Auteurs Auteurs ON (Auteurs.ID = Omnilogismes.Auteur)
JOIN (
	SELECT MONTH(Sortie) AS Mois, YEAR(Sortie) AS Annee, MAX(NbVotes) AS Max
	FROM OMNI_Omnilogismes
	GROUP BY Mois, Annee
	HAVING Max !=0
	) Votes ON (Votes.Mois = MONTH(Omnilogismes.Sortie) AND Votes.Annee = YEAR(Omnilogismes.Sortie) AND Votes.Max = Omnilogismes.NbVotes)
ORDER BY Sortie DESC";

$r = Sql::query($query);
$C['Articles'] = array();
while($C['Articles'][] = mysql_fetch_object($r, "Omni")) {}
array_pop($C['Articles']);
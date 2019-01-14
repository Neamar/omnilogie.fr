<?php
/**
* Modèle : Vote
* But : Voter pour l'article favori du dernier mois
* Données à charger : les articles du dernier mois, sous forme de teaser.
*/

$C['PageTitle'] = 'Élisez le meilleur article du mois de ' . Top::$monthReadable;

$C['CanonicalURL'] = '/Vote';
/*

//Récupérer tous les articles parus.
//Utiliser le système de pagination
$Param = Omni::buildParam(OMNI_SMALL_PARAM);

$Param->Where = '
Sortie LIKE "' . Top::$month . '"
AND TITRE NOT LIKE "Top article du mois%"';

$Param->Order = 'Omnilogismes.Sortie ASC';

$articles = Formatting::makePage($Param,'/O/');

$C['Vote'] = array();
foreach($articles as $article)
{
	$C['Vote'][$article->ID] = $article->Titre;
}

$C['DateTop'] = Top::$monthReadable;

//Gérer le menu
$Param->Order = 'Omnilogismes.NbVotes DESC';
$Param->Limit = 5;

$VotesMois = 'SELECT LEFT(Titre, 8) AS Abscisse, NbVotes AS Ordonnee
FROM OMNI_Omnilogismes
WHERE Sortie LIKE "' . Top::$month . '"
ORDER BY NbVotes DESC
LIMIT 5';

$articlesGraphique = Stats::GraphIt($VotesMois, array('cht'=>'bhs','chs'=>'240x150', 'chtt'=>'Articles les plus votés','chxt'=>'y','chxl'=>'0:|$ABSCISSES','chbh'=>'a'),false);

prependPod("pod-votes", "Meilleurs votes actuels", Formatting::makeList(Omni::getTrailers($Param)) . $articlesGraphique);
*/

<?php
/**
* Fichier d'évènement
* Event::SPECIAL
*
* @standalone
* @access admins
*
* PUBLIE un nouvel article avec les informations sur le concours du mois.
* ATTENTION : l'article est directement PUBLIÉ !
*/
//Identifiant de l'auteur faisant paraître les top
$authorTopId = 240;

/*
 * Récupérer les données utiles :
 */
//Récupérer l'article du mois
$Param = Omni::buildParam(OMNI_SMALL_PARAM);
$Param->Where = 'Sortie LIKE "' . Top::$month . '" AND NbVotes = (SELECT MAX(NbVotes) FROM OMNI_Omnilogismes WHERE Sortie LIKE "' . Top::$month . '")';
$Param->Limit = 1;
$articleMois = Omni::getSingle($Param);

$Param = Omni::buildParam(OMNI_TRAILER_PARAM);
$Param->Where = 'Sortie LIKE "' . Top::$month . '"';
$Param->Order = "Omnilogismes.NbVotes DESC";
$Param->Limit = "1, 4";
$articlesMois = Omni::get($Param);

//Le graphique des votes
$VotesMois = 'SELECT LEFT(Titre, 50) AS Abscisse, NbVotes AS Ordonnee
FROM OMNI_Omnilogismes
WHERE Sortie LIKE "' . Top::$month . '"
ORDER BY NbVotes DESC
LIMIT 5';

$articlesGraphique = Stats::GraphItUrl($VotesMois, array('cht'=>'bhs','chs'=>'675x200', 'chtt'=>'Articles les plus votés','chxt'=>'y,x','chxl'=>'0:|$ABSCISSES|1:|0|$MAX','chbh'=>'a'),false);

//Les données du "concours" :
$mois = Top::$monthReadable;

/*
 * Mettre à jour le concours pour se faire sur le prochain mois
 */
Top::goNextMonth();

/*
 * Contenu de l'article
 */
$template = "
Voici venu le temps de donner les résultats ! Après une semaine de vote, \b{l'article du mois de " . $mois . "} a été sélectionné.

Sans plus attendre, le vainqueur est...

\begin{center}
\image[" . $articleMois->Titre . ']{' . $articleMois->getBannerUrl(Omni::THUMB_BANNER) . '}' . '
\b{' . $articleMois->Titre . '} par \l[' . Link::author($articleMois->Auteur) . ']{' . $articleMois->Auteur . '}.
\ref[' . str_replace('/O/', '', Link::omni($articleMois->Titre)) . ']{' . (empty($articleMois->Accroche)?$articleMois->Titre:$articleMois->Accroche) . '}
\end{center}

Et les articles qui suivent dans le top 5 sont :

';

foreach($articlesMois as $article)
{
	$template .= '\item \b{' . $article->Titre . '}. \ref[' . str_replace('/O/', '', Link::omni($article->Titre)) . ']{' . (empty($article->Accroche)?$article->Titre:$article->Accroche) . "}\n";
}

$template .= "\n
\image[Répartition des votes pour le concours de " . $mois . "]{" . $articlesGraphique . "}";

//Lien vers le concours du prochain mois :
$template .= "\n\n
\l[/Vote]{Les votes sont maintenant ouverts pour élire l'article du mois de " . Top::$monthReadable . "}. Les résultats seront annoncés dimanche prochain.";
/*
 * Enregistrement et publication de l'article
 */
$newOmniData = array(
	'auteur' => $authorTopId,
	'_Sortie' => 'FROM_UNIXTIME(' . $Date . ')',
	'Statut' => 'ACCEPTE',
	'Titre' => 'Top article du mois de ' . $mois,
	'Omnilogisme' => Input::sanitize(addslashes($template)),
	'Accroche' => 'Découvrez les résultats du vote pour le meilleur article de ' . $mois,
	'Message' => 'Cet article spécial récapitule les résultats du vote élisant le meilleur article du mois de ' . $mois . '.'
);

Sql::insert('OMNI_Omnilogismes', $newOmniData);

$id = mysql_insert_id();
$NouvelArticle = new Omni();
$NouvelArticle->ID = $id;
$NouvelArticle->Titre = $newOmniData['Titre'];

Sql::insert('OMNI_Modifs', array('Auteur'=>$authorTopId	,'Reference'=>$id, '_Date'=>'NOW()', 'Modification'=>Event::NOUVEAU));
Sql::insert('OMNI_Modifs', array('Auteur'=>50			,'Reference'=>$id, '_Date'=>'NOW()', 'Modification'=>Event::ACCEPTE,));
//Sql::insert('OMNI_Modifs', array('Auteur'=>50			,'Reference'=>$id, '_Date'=>'NOW()', 'Modification'=>Event::PARUTION));
//$NouvelArticle->registerModif(Event::ACCEPTE, false, 50);
$NouvelArticle->registerModif(Event::PARUTION, false, 50);

// Ajouter la bannière
$i = imagecreatetruecolor(690, 95);

function imageadd($id, $on, $x, $y)
{
	$src = imagecreatefrompng(PATH . '/images/Banner/Originaux/' . $id . '.png');
	imagecopyresampled($on, $src, $x, $y, 0, 0, 345, 47, 690, 95);
}

imageadd($articlesMois[0]->ID, $i, 0, 0);
imageadd($articlesMois[1]->ID, $i, 345, 0);
imageadd($articlesMois[2]->ID, $i, 0, 47);
imageadd($articlesMois[3]->ID, $i, 345, 47);
imageadd($articleMois->ID, $i, 690 / 4, 95 / 4);
imagepng($i, PATH . '/images/Banner/Originaux/' . $NouvelArticle->ID . '.png');
Admin::banniereEffet($NouvelArticle, $authorTopId);
<?php
/**
* Modèle : Stats
* But : Afficher des statistiques inutiles
*/
//Article

$C['PageTitle'] = 'Statistiques';
$C['CanonicalURL'] = '/Stats';

define('ADD_OTHER_COLUMN',true);
define('NO_OTHER_COLUMN',false);
define('LIMIT_AT',11);

$PlusProlifiques = 'SELECT OMNI_Auteurs.Auteur AS Abscisse,COUNT(*) AS Ordonnee
FROM OMNI_Omnilogismes
LEFT JOIN OMNI_Auteurs ON (OMNI_Auteurs.ID = OMNI_Omnilogismes.Auteur)
WHERE Statut="ACCEPTE"
GROUP BY OMNI_Auteurs.Auteur
ORDER BY Ordonnee DESC';

$PlusLus = 'SELECT OMNI_Auteurs.Auteur AS Abscisse,SUM(NbVues) AS Ordonnee
FROM OMNI_Omnilogismes
LEFT JOIN OMNI_Auteurs ON(OMNI_Auteurs.ID=OMNI_Omnilogismes.Auteur)
GROUP BY OMNI_Auteurs.ID
ORDER BY Ordonnee DESC';

$PlusLusMoyenne = 'SELECT OMNI_Auteurs.Auteur AS Abscisse,FLOOR(SUM(NbVues)/COUNT(*)) AS Ordonnee
FROM OMNI_Omnilogismes
LEFT JOIN OMNI_Auteurs ON(OMNI_Auteurs.ID=OMNI_Omnilogismes.Auteur)
WHERE !ISNULL(OMNI_Auteurs.Auteur)
GROUP BY OMNI_Auteurs.ID
ORDER BY Ordonnee DESC
LIMIT 12';

$PlusAction = 'SELECT Modification AS Abscisse,COUNT(*) AS Ordonnee
FROM OMNI_Modifs
GROUP BY OMNI_Modifs.Modification
ORDER BY COUNT(*) DESC
LIMIT 12';

$PlusActionOneLiner = 'SELECT
	COUNT(*) AS Somme,
	COUNT(DISTINCT Reference) AS CountDistinct,
	FORMAT(COUNT(*)/COUNT(DISTINCT Reference),1) AS Avg,
	TO_DAYS(CURDATE()) - TO_DAYS("2009-03-05 20:22:41") AS DateSpan,
	FORMAT(COUNT(*)/(TO_DAYS(CURDATE()) - TO_DAYS("2009-03-05 20:22:41")),1) AS ActionJour,
	COUNT(Sauvegarde) AS Versions
FROM OMNI_Modifs';

$ModifsJour = 'SELECT DATE(Date) As Abscisse,COUNT(*) AS Ordonnee
FROM OMNI_Modifs
GROUP BY DATE(Date)
ORDER BY Date';

$ModifsHeure = 'SELECT CONCAT(HOUR(Date),"h") As Abscisse,COUNT(*) AS Ordonnee
FROM OMNI_Modifs
GROUP BY HOUR(Date)
ORDER BY HOUR(Date)';

$ActionDelai = 'SELECT FORMAT(AVG(TO_DAYS(Parution.Date)-TO_DAYS(Creation.Date)),1) AS Moyenne,MAX(TO_DAYS(Parution.Date)-TO_DAYS(Creation.Date)) AS Maximum
FROM OMNI_Modifs AS Parution
LEFT JOIN OMNI_Modifs AS Creation ON (Creation.Modification LIKE "Création%" AND Creation.Reference=Parution.Reference)
WHERE Parution.Modification LIKE "Parution%"';

$TailleFile = "SELECT DATE(Calendrier.Sortie) AS Abscisse, COUNT(File.Parution) AS Ordonnee
FROM OMNI_Omnilogismes Calendrier
LEFT JOIN
(
	SELECT Titre, DATE(Acceptation.Date) AS Acceptation, DATE(Parution.Date) AS Parution
	FROM OMNI_Omnilogismes Omnilogismes
	JOIN
	(
		SELECT Reference, Date FROM OMNI_Modifs WHERE Modification='Statut changé vers ACCEPTE' GROUP BY Reference
	) Acceptation ON Acceptation.Reference = Omnilogismes.ID
	JOIN
	(
		SELECT Reference, Date FROM OMNI_Modifs WHERE Modification='Parution officielle de l''omnilogisme' GROUP BY Reference
	) Parution ON Parution.Reference = Omnilogismes.ID
) File ON(Acceptation < Calendrier.Sortie AND Parution > Calendrier.Sortie)
WHERE !ISNULL(Calendrier.Sortie)
GROUP BY Calendrier.Sortie";

$PlusVus='SELECT Titre AS Abscisse, NbVues AS Ordonnee, OMNI_Auteurs.Auteur
FROM OMNI_Omnilogismes
LEFT JOIN OMNI_Auteurs ON (OMNI_Auteurs.ID=OMNI_Omnilogismes.Auteur)
ORDER BY NbVues DESC
LIMIT 12';

$TotalVue = 'SELECT SUM(NbVues) AS Somme FROM OMNI_Omnilogismes';

$LongueurInfo = 'SELECT FLOOR(AVG(LENGTH(Omnilogisme))) AS Moyenne, FLOOR(AVG(LENGTH(Omnilogisme))/5.13) AS AvgWord,
MIN(LENGTH(Omnilogisme)) AS Minimum,FLOOR(MIN(LENGTH(Omnilogisme)/5.13)) AS MinWord,
MAX(LENGTH(Omnilogisme)) AS Maximum, FLOOR(MAX(LENGTH(Omnilogisme)/5.13)) AS MaxWord
FROM OMNI_Omnilogismes
WHERE Statut="ACCEPTE"';

$LongueurTemps = 'SELECT Sortie AS Abscisse,LENGTH(Omnilogisme) AS Ordonnee
FROM OMNI_Omnilogismes
WHERE !ISNULL(Sortie)
ORDER BY Sortie';

$NbVuesTemps = 'SELECT Sortie AS Abscisse, NbVues AS Ordonnee
FROM OMNI_Omnilogismes
WHERE !ISNULL(Sortie)
ORDER BY Sortie';

$ListeStatut = 'SELECT IF(ISNULL(Sortie) AND Statut="ACCEPTE","A PARAITRE",Statut) AS Abscisse,LCASE(Statut) AS PetitStatut,COUNT(*) AS Ordonnee
FROM OMNI_Omnilogismes
GROUP BY Abscisse
ORDER BY Ordonnee DESC
LIMIT 12';

$NbArticles = 'SELECT COUNT(*) AS Somme FROM OMNI_Omnilogismes';


$C['Stats'] = array(
'Membres'=>array(
	'Membres les plus prolifiques'=>array(
		Stats::Its($PlusProlifiques,'<a href="/Omnilogistes/$Abscisse">$Abscisse</a> : $Ordonnee articles','Ordonnee','Autres auteurs : $Ordonnee articles'),
		Stats::GraphIt($PlusProlifiques,array('cht'=>'p3','chtt'=>'Nb Omnilogismes','chco'=>'FF0000,00FF00,88AAD6','chl'=>'$ABSCISSES'))),
	'Auteurs les plus lus'=>array(
		Stats::Its($PlusLus,'<a href="/Omnilogistes/$Abscisse">$Abscisse</a></span> ($Ordonnee vues)','Ordonnee','Autres articles : $Ordonnee vues'),
		Stats::GraphIt($PlusLus,array('cht'=>'p3','chtt'=>'Auteurs lus','chco'=>'FF0000,00FF00,88AAD6','chl'=>'$ABSCISSES'))),
	'Pondération lectures / nombre d\'articles'=>array(
		Stats::Its($PlusLusMoyenne,'<a href="/Omnilogistes/$Abscisse" class="lienDiscret auteur">$Abscisse</a></span> ($Ordonnee vues)','Ordonnee'),
		Stats::GraphIt($PlusLusMoyenne,array('cht'=>'p3','chtt'=>'Moyenne vues par articles','chco'=>'FF0000,00FF00,88AAD6','chl'=>'$ABSCISSES'),NO_OTHER_COLUMN)),
	),
'Actions'=>array(
	'Top actions réalisées'=>array(
		Stats::Its($PlusAction,'$Abscisse : $Ordonnee','Ordonnee','Autres : $Ordonnee'),
		Stats::It($PlusActionOneLiner,'<p>Total : $Somme modifications sur $CountDistinct articles (moyenne : $Avg modifications par article) réparties sur $DateSpan jours ($ActionJour modifs / jour).<br />$Versions articles versionnés actuellements enregistrés (suppression automatique après 30 jours).</p>'),	Stats::GraphIt($PlusAction,array('cht'=>'p3','chs'=>'800x200','chtt'=>'Actions','chco'=>'FF0000,00FF00,88AAD6','chl'=>'$ABSCISSES'))),
	'Qui fait quoi quand ?'=>array(
		Stats::GraphIt($ModifsJour,array('chtt'=>'Modifications par jour','chxt'=>'y','chxr'=>'0,0,$MAX'),NO_OTHER_COLUMN),
		Stats::GraphIt($ModifsHeure,array('cht'=>'bvs','chtt'=>'Modifications par heure','chxt'=>'x,y','chxl'=>'0:|$ABSCISSES|1:|0|$MAX','chbh'=>'a'),NO_OTHER_COLUMN)),
	'Temps'=>array(
		Stats::It($ActionDelai,'<p>Délai moyen entre la création d\'un article et sa parution : $Moyenne jours<br />Maximum : $Maximum jours.</p>')),
	'Longueur de la file de parution'=>array(
		Stats::GraphIt($TailleFile,array('chtt'=>'Nombre d\'articles en file d\'attente','chxt'=>'y','chxr'=>'0,0,$MAX'),NO_OTHER_COLUMN)),
	),
'Omnilogismes'=>array(
	'Articles les plus vus'=>array(
		Stats::Its($PlusVus,'$Abscisse <span class="petitTexte">par <a href="/Omnilogistes/$Auteur" class="lienDiscret auteur">$Auteur</a></span> ($Ordonnee vues)','Ordonnee'),
		Stats::GraphIt($PlusVus,array('cht'=>'p3','chtt'=>'Visionnages des articles','chco'=>'FF0000,00FF00,88AAD6','chl'=>'$ABSCISSES'),NO_OTHER_COLUMN),
		Stats::It($TotalVue,'<p>Total : $Somme articles visionnés.</p>')),
	'Nombre de vues'=>array(
		Stats::GraphIt($NbVuesTemps,array('chtt'=>'Nombre de vues des articles','chxt'=>'y','chxr'=>'0,0,$MAX'),NO_OTHER_COLUMN)),
	'Longueur'=>array(
		Stats::It($LongueurInfo,'<p>Longueur moyenne : $Moyenne caractères ($AvgWord mots)<br />Maximum : $Maximum caractères ($MaxWord mots)<br />Minimum : $Minimum caractères ($MinWord mots).</p>'),
		Stats::GraphIt($LongueurTemps,array('chtt'=>'Taille des articles au fil du temps','chxt'=>'y','chxr'=>'0,0,$MAX'),NO_OTHER_COLUMN)),
	'Articles et statuts'=>array(
		Stats::Its($ListeStatut,'Dans le statut "<span class="$PetitStatut">$Abscisse</span>" : $Ordonnee'),
		Stats::It($NbArticles,'<p>Total : $Somme articles.</p>'),
		Stats::GraphIt($ListeStatut,array('cht'=>'p3','chtt'=>'Articles et statuts','chco'=>'FF0000,00FF00,88AAD6','chl'=>'$ABSCISSES'),NO_OTHER_COLUMN))
	));
?>

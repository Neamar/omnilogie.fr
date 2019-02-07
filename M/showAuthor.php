<?php
/**
* Modèle : showAuthor
* But : Afficher la page d'un auteur
* Données à charger :
* - Statut de l'auteur
* - Bio de l'auteur
* - Articles rédigés par l'auteur
* - Nuage de tags de l'auteur
* Pré requis du contrôleur associé : la variable $Author avec l'entrée propre demandée.
*/
$C['PageActuelle'] = (isset($_GET['Page'])?' : page ' . intval($_GET['Page']):'');
$C['PageTitle'] = 'Articles de ' . $Author . ' sur Omnilogie.fr' . $C['PageActuelle'];
$C['CanonicalURL'] = Link::author($Author) . (isset($_GET['Page'])?'Page-' . $_GET['Page']:'');
$C['Author'] = $Author;






$C['AuthorRole']='';
foreach(Member::getRolesFor($Author) as $Role)
{
	$C['AuthorRole'] .='<div class="message">' . $Author . ' fait partie de l\'équipe des <strong class="' . $Role . '">' . $Role . '</strong>.<br />' . Member::$Roles[$Role] . '</div>';
}

unset($Liste,$Categorie,$AdminType,$Membres,$Membre,$Roles,$Role,$AuthorRoles);














//Informations sur l'auteur :
//mail,
//histoire,
//activité...
$AuthorData = SQL::singleQuery('SELECT Mail, MailPublic, Histoire, GooglePlus, DATE_FORMAT(DPremiereAction, "%d/%m/%Y") AS PremiereAction, DATE_FORMAT(DDerniereAction, "%d/%m/%Y") AS DerniereAction, DATEDIFF(CURDATE(),DDerniereAction) AS NbJoursInactif
FROM OMNI_Auteurs
LEFT JOIN
	(
	SELECT Auteur, MIN(Date) AS DPremiereAction, MAX(Date) AS DDerniereAction
	FROM OMNI_Modifs
	GROUP BY OMNI_Modifs.Auteur
	) Modifs ON OMNI_Auteurs.ID = Modifs.Auteur
WHERE OMNI_Auteurs.Auteur="' . mysql_real_escape_string($Author) . '"');

//Afficher le mail si public
if($AuthorData['MailPublic']=='oui')
	$C['Mail']='<p>Adresse mail de ' . $Author . ' : <span class="email">' . strrev($AuthorData['Mail']) . '</span></p>';
else
	$C['Mail']='';

$C['GooglePlus'] = $AuthorData['GooglePlus'];

//Afficher l'histoire si définie
if($AuthorData['Histoire']!='')
{
	Typo::setTexte($AuthorData['Histoire']);
	$C['Histoire'] = Typo::Parse();
}
else
	$C['Histoire'] = '';

//Première et dernière action :
$C['FirstAction'] = $AuthorData['PremiereAction'];
$C['LastAction'] = $AuthorData['DerniereAction'];

if($AuthorData['NbJoursInactif']==0)
	$C['LastActionInDays']='(aujourd\'hui !)';
elseif($AuthorData['NbJoursInactif']==1)
	$C['LastActionInDays'] = '(hier)';
elseif($AuthorData['NbJoursInactif']< 30)
	$C['LastActionInDays'] = '(il y a ' . $AuthorData['NbJoursInactif'] . ' jours)';
else
	$C['LastActionInDays'] = '';









//Dernières actions de l'auteur
//Cinq dernières actions effectuées sur le site, ne pas afficher l'auteur (c'est forcément $Author !)
$C['Actions'] = Formatting::makeList(Event::getLast(5,'Modifs.Auteur = ' . $AuthorID,'%DATE% %LIEN% : %MODIF%'));











//Récupérer les articles de l'auteur.
//Utiliser le système de pagination
$Param = Omni::buildParam(OMNI_SMALL_PARAM);

$Param->Where = '
!ISNULL(Sortie)
AND Statut="ACCEPTE"
AND Auteurs.ID = ' . $AuthorID;

$Param->Order = 'Omnilogismes.Sortie DESC';

Formatting::makePage($Param,Link::author($Author));








//Nuage de tags de l'auteur
//En fonction des tags associés aux articles qu'il a écrit
$Datas = SQL::query(
'SELECT Categorie, COUNT(*) AS Nb
FROM OMNI_Liens
LEFT JOIN OMNI_Omnilogismes ON News = OMNI_Omnilogismes.ID
WHERE OMNI_Omnilogismes.Auteur = ' . $AuthorID . '
GROUP BY Categorie
ORDER BY RAND()/COUNT(*)
LIMIT ' . CAT_CLOUD);

$Categories=array();
$Max=2;
while($Data=mysql_fetch_assoc($Datas))
{
	$Max = max($Data['Nb'],$Max);
	$Categories[] = array('Size'=>$Data['Nb'],'Link'=>Anchor::category($Data['Categorie']));;
}

if(count($Categories)!=0)
{
	$Content='';

	foreach($Categories as $Category)
		$Content .= '<span style="font-size:' . max(.7,2*$Category['Size']/$Max) . 'em">' . $Category['Link'] . '</span> ';

	prependPod('catCloud','Nuage de l\'auteur',$Content);
}
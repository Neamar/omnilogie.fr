<?php
/**
* Contrôleur : admin/admin.php
* But : enregistrer les modifications de statut apportées à un article.
* Charge un tableau $Articles pour le modèle.
* Modèle et contrôleur sont mélangés dans cette interface.
*/


//////////////////////////////////////////////////////
//Fonctionnalités du contrôleur :
$C['Sections']=array();

if(Member::is(AUTHOR,'admins'))
{
	$Ordre = Admin::getProchains();//SqlParam pour récupérer les derniers auteurs.
	/*$Ordre->Order = str_replace(',Omnilogismes.ID',',',$Ordre->Order);
	$Auteurs = SQL::query('SELECT DISTINCT Auteur FROM OMNI_Omnilogismes WHERE ISNULL(Sortie) && Statut="ACCEPTE"');
	while($Dernier = mysql_fetch_assoc($Auteurs))
		$Ordre->Order .='(Omnilogismes.Auteur=' . $Dernier['Auteur']  . '),';
	$Ordre->Order .= 'Omnilogismes.ID';*/

	$C['Sections']['admin']['Titre'] = 'Administration des articles';
	$C['Sections']['admin']['Description'] = 'Ce pod permet de modifier le statut des articles.';
	$C['Sections']['admin']['Articles'] = Admin::getControleur('Omnilogismes.Statut="INDETERMINE" OR Omnilogismes.Statut="EST_CORRIGE"','statut', array('Admin','adminControleurCallback'),$Ordre->Order);
	$C['Sections']['admin']['Vue'] = array('Admin','adminVueCallback');
	$C['Sections']['admin']['Max'] = -1;
}

if(Member::is(AUTHOR,'censeurs'))
{
	$C['Sections']['edit']['Titre'] = 'Correction des articles';
	$C['Sections']['edit']['Description'] = 'Ce pod affiche les articles qui ont peut-être besoin d\'être corrigés (jamais relus par un censeur).';
	$C['Sections']['edit']['Articles'] = Admin::getControleur('Omnilogismes.Statut="INDETERMINE" OR (Omnilogismes.Statut="ACCEPTE" AND ISNULL(Omnilogismes.Sortie))','edit',null);
	$C['Sections']['edit']['Vue'] = array('Admin','editVueCallback');
	$C['Sections']['edit']['Max'] = -1;
	$C['Sections']['edit']['SubmitCaption'] = '';
}

if(Member::is(AUTHOR,'bannières'))
{
	function whereBanner($Where=''){ return 'Omnilogismes.ID NOT IN(' . implode(',',Admin::getBanner()) . ') AND (Omnilogismes.Statut="INDETERMINE" OR Omnilogismes.Statut="ACCEPTE" OR Omnilogismes.Statut="EST_CORRIGE")'; }

	$C['Sections']['bannieres']['Titre'] = 'Gestion des bannières';
	$C['Sections']['bannieres']['Description'] = 'Ce pod affiche les articles qui n\'ont pas de bannière.';
	$C['Sections']['bannieres']['Articles'] = Admin::getControleur(whereBanner(),'banniere', array('Admin','banniereControleurCallback'),'Omnilogismes.ID',"whereBanner");
	$C['Sections']['bannieres']['Vue'] = array('Admin','banniereVueCallback');
	$C['Sections']['bannieres']['Max'] = Member::is(AUTHOR,'admin')?2:10;
	$C['Sections']['bannieres']['IsFileForm'] = 2;
}

if(Member::is(AUTHOR,'accrocheurs'))
{
	$C['Sections']['accroches']['Titre'] = 'Gestion des accroches';
	$C['Sections']['accroches']['Description'] = 'Ce pod affiche les articles qui ont besoin d\'être accrochés.';
	$C['Sections']['accroches']['Articles'] = Admin::getControleur('ISNULL(Omnilogismes.Accroche) AND (Omnilogismes.Statut="INDETERMINE" OR Omnilogismes.Statut="ACCEPTE" OR Omnilogismes.Statut="EST_CORRIGE")','accroche',array('Admin','accrocheControleurCallback'));
	$C['Sections']['accroches']['Vue'] = array('Admin','accrocheVueCallback');
}

if(Member::is(AUTHOR,'reffeurs') && !Member::is(AUTHOR,'admins'))
{
	$C['Sections']['ReffeursInfos']['Titre'] = 'Reffeurs';
	$C['Sections']['ReffeursInfos']['Description'] = 'Infos référencement des articles';
	$C['Sections']['ReffeursInfos']['HTML'] = '<p>Les reffeurs n\'ont pas de panneau d\'administration dédié. </p>';

	$C['Sections']['edit']['Titre'] = 'Référencement des articles';
	$C['Sections']['edit']['Description'] = 'Ce pod affiche les articles qui ont peut-être besoin d\'être référencés. Pour référencer un article non listé ici, consultez la page d\'un article après votre connexion, puis cliquez sur «&nbsp;Référencer l\'article&nbsp;».';
	$C['Sections']['edit']['Articles'] = Admin::getControleur('(Omnilogismes.Statut="INDETERMINE" OR Omnilogismes.Statut="ACCEPTE" OR Omnilogismes.Statut="EST_CORRIGE") AND Omnilogismes.ID > 750 AND Omnilogismes.ID NOT IN (SELECT DISTINCT Reference FROM OMNI_Modifs WHERE Modification="Modification des références")','reffeurs',null);
	$C['Sections']['edit']['Vue'] = array('Admin','refVueCallback');
	$C['Sections']['edit']['Max'] = -1;
	$C['Sections']['edit']['SubmitCaption'] = '';
}
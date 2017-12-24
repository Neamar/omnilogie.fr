<?php

/**
* Mod�le : menu
* But : pr�parer � l'affichage des menus dans la vue.
*/

/**
* Ajoute un pod au d�but de la liste
* @param ID:String la cl� du pod. Attention, si une cl� de ce nom existe d�j� elle sera �cras�e.
* @param Title:String le titre du pod.
* @param Content:String le contenu
*/
function prependPod($ID,$Title,$Content)
{
	global $C;
	$C['Pods'] = array($ID=>'') + $C['Pods'];

	$C['Pods'][$ID]['Title']=$Title;
	$C['Pods'][$ID]['Content']=$Content;
}









//Premier menu : les cinq derniers articles parus.
if(Cache::exists('Pods', 'lastArticles')) {
	$C['Pods']['lastArticles']['Title'] = 'Derniers articles parus';
	$C['Pods']['lastArticles']['Content'] = Cache::get('Pods','lastArticles');
}


//Second menu : nuage de cat�gories
if(Cache::exists('Datas', 'catCloud')) {
	$Datas = unserialize(Cache::get('Datas','catCloud'));

	//M�langer
	shuffle($Datas);

	$Contenu='';
	for($i=0;$i<CAT_CLOUD;$i++)
		$Contenu .= $Datas[$i];

	$C['Pods']['catCloud']['Title'] = 'Nuage de cat�gories';
	$C['Pods']['catCloud']['Content'] = $Contenu;
}





//Troisi�me menu : auteurs actifs r�cemment
if(Cache::exists('Pods', 'activeAuthor')) {
	$C['Pods']['activeAuthor']['Title'] = 'Auteurs actifs r�cemment';
	$C['Pods']['activeAuthor']['Content'] = Cache::get('Pods','activeAuthor');
}


//Menu : un article au hasard
//Cf. http://blog.neamar.fr/component/content/article/4-web-pour-webmaster/113-rand-element-hasard-mysql-rapide
$RandomParams=Omni::buildParam();
$RandomParams->Where = '!ISNULL(Sortie)';
$RandomParams->Order = 'RAND()';
$RandomParams->Limit = '1';
$RandomArticle=Omni::getSingle($RandomParams);
error_log("BANNER: " . PATH . '/images/Banner/Thumbs/' . $RandomArticle->ID . '.png');

if(is_file(PATH . '/images/Banner/Thumbs/' . $RandomArticle->ID . '.png'))
	$RandomImage = '<img src="/images/Banner/Thumbs/' . $RandomArticle->ID . '.png" alt="' . $RandomArticle->Titre . '" class="randomImage"/>';
else
	$RandomImage = '';

$C['Pods']['randomArticle']['Title'] = 'Un article au hasard';
$C['Pods']['randomArticle']['Content'] = $RandomImage . $RandomArticle->outputTrailer() . '<p class="more"><a href="/Random">Un autre article au hasard</a></p>';


/*
//Menu : twitter
if(Cache::modified('Pods','twitter') + 3600 < time())
	Event::callGeneric('twitter_cache.php');


$C['Pods']['twitter']['Title'] = 'Omnilogie en direct';
$C['Pods']['twitter']['Content'] = Cache::get('Pods','twitter');
*/

$C['Footers']['about']['Title'] = '� propos';
$C['Footers']['about']['Content'] = file_get_contents(DATA_PATH . '/about');


$ALire = array(
	'/TOC" accesskey="5'=>'Liste des articles',
	'/Timeline'=>'Frise chronologique',
	'/Omnilogistes/'=>'Liste des auteurs',
);

$A = '';
foreach($ALire as $URL=>$Caption)
	$A .='<li><a href="' . $URL . '">' . $Caption . '</a></li>' . "\n";
$C['Footers']['alire']['Title']='� lire...';
$C['Footers']['alire']['Content'] = '<ol>' . $A . '</ol>';

// Topbar des utilisateurs connect�s : nombres d'articles � para�tre
if(isset($_SESSION['Membre']['ID']))
{
	$Nb = Sql::singleQuery('SELECT COUNT(*) AS ToBePublished FROM OMNI_Omnilogismes WHERE Statut="ACCEPTE" AND ISNULL(Sortie)');
	$C['Snippet']['toBePublished'] = $Nb['ToBePublished'];
}


$C['Pods']['concours']['Title'] = "Concours de la semaine";
$C['Pods']['concours']['Content'] = '<p><a href="/Vote">Votez pour choisir le meilleur article de notre s�lection</a> !</p>';


unset($DerniersParams, $Datas, $Data, $Contenu, $RandomParams, $RandomArticle, $RandomImage, $Chemin, $Twitter, $XML, $XMLTwitter, $Entry, $Partners,$P,$Nb);

<?php

/**
* Modèle : menu
* But : préparer à l'affichage des menus dans la vue.
*/

/**
* Ajoute un pod au début de la liste
* @param ID:String la clé du pod. Attention, si une clé de ce nom existe déjà elle sera écrasée.
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


//Second menu : nuage de catégories
if(Cache::exists('Datas', 'catCloud')) {
	$Datas = unserialize(Cache::get('Datas','catCloud'));

	//Mélanger
	shuffle($Datas);

	$Contenu='';
	for($i=0;$i<CAT_CLOUD;$i++)
		$Contenu .= $Datas[$i];

	$C['Pods']['catCloud']['Title'] = 'Nuage de catégories';
	$C['Pods']['catCloud']['Content'] = $Contenu;
}





//Troisième menu : auteurs actifs récemment
if(Cache::exists('Pods', 'activeAuthor')) {
	$C['Pods']['activeAuthor']['Title'] = 'Auteurs actifs récemment';
	$C['Pods']['activeAuthor']['Content'] = Cache::get('Pods','activeAuthor');
}


//Menu : un article au hasard
//Cf. http://blog.neamar.fr/component/content/article/4-web-pour-webmaster/113-rand-element-hasard-mysql-rapide
$RandomParams=Omni::buildParam();
$RandomParams->Where = '!ISNULL(Sortie)';
$RandomParams->Order = 'RAND()';
$RandomParams->Limit = '1';
$RandomArticle=Omni::getSingle($RandomParams);

if(is_file(PATH . '/images/Banner/Thumbs/' . $RandomArticle->ID . '.png'))
	$RandomImage = '<a href="' . Link::omni($RandomArticle->Titre). '"><img src="/images/Banner/Thumbs/' . $RandomArticle->ID . '.png" alt="' . $RandomArticle->Titre . '" class="randomImage"/></a>';
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

$C['Footers']['about']['Title'] = 'À propos';
$C['Footers']['about']['Content'] = file_get_contents(DATA_PATH . '/about');


$ALire = array(
	'/TOC" accesskey="5'=>'Liste des articles',
	'/Timeline'=>'Frise chronologique',
	'/Omnilogistes/'=>'Liste des auteurs',
);

$A = '';
foreach($ALire as $URL=>$Caption)
	$A .='<li><a href="' . $URL . '">' . $Caption . '</a></li>' . "\n";
$C['Footers']['alire']['Title']='À lire...';
$C['Footers']['alire']['Content'] = '<ol>' . $A . '</ol>';

// Topbar des utilisateurs connectés : nombres d'articles à paraître
if(isset($_SESSION['Membre']['ID']))
{
	$Nb = Sql::singleQuery('SELECT COUNT(*) AS ToBePublished FROM OMNI_Omnilogismes WHERE Statut="ACCEPTE" AND ISNULL(Sortie)');
	$C['Snippet']['toBePublished'] = $Nb['ToBePublished'];
}


unset($DerniersParams, $Datas, $Data, $Contenu, $RandomParams, $RandomArticle, $RandomImage, $Chemin, $Twitter, $XML, $XMLTwitter, $Entry, $Partners,$P,$Nb);

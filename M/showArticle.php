<?php
/**
* Modèle : Article
* But : Afficher un article sur sa page dédiée /O/
* Données à charger : Article, sources, articles similaires, sagas dont l'article fait partie dans les menus, anecdote
* Modèles particulièrement court, les données étant récupérées directement dans le contrôleur pour valider l'article.
*/

//Article
Typo::setTexte($Article->Titre);
$C['PageTitle'] = Typo::parseLinear() . " | Un article d'Omnilogie.fr";
$C['CanonicalURL'] = Link::omni($Article->Titre);
$C['Title'] = $Article->Titre;
$C['ShortURL'] = 'https://omnilogie.fr' . Link::omniShort($Article->ID);

$C['Header'] = $Article->outputHeader();

$C['OpenGraph'] = array(
	'og:title' => $C['Title'],
	'og:url' => 'https://omnilogie.fr' . $C['CanonicalURL'],
	'og:image' => 'https://omnilogie.fr' . $Article->getBannerUrl(),
	'og:description' => $Article->getAccroche(),
	'og:type' => 'article',
	'og:site_name' => 'Omnilogie',
	'og:locale' => 'fr_FR',
);

/*
//Twitter & facebook
$C['Header'] = str_replace(
	'</time></p>',
	'</time><span class="facebook-like"><fb:like layout="button_count" show_faces="false" width="450"></fb:like></span><a href="http://twitter.com/share" class="twitter-share-button" data-url="' . $C['ShortURL'] . '" data-text="' . $C['Title'] . '" data-count="horizontal" data-via="Omnilogie" data-lang="fr">Tweet</a></p>',
	$C['Header']
);
*/



if($Article->Adsense != '' && $Article->ID > 1000) //Ne pas afficher de publicités sur les anciens articles.
{
	$C['Adsense'] = $Article->Adsense;
}

$C['URLs'] = $Article->getURLs();

$C['Contenu'] = $Article->outputFull();

if($Article->TitreSuivant!='')
	$C['Suivant'] = '<p class="read-next-article"><a href="' . Link::omni($Article->TitreSuivant) . '" title="' . $Article->AccrocheSuivant . '">Cet article vous a plu ? Courez lire la suite !</a></p>';
else
	$C['Suivant'] = '';

if($Article->TitrePrecedent!='')
	$C['Precedent'] = '<p class="read-previous-article"><a href="' . Link::omni($Article->TitrePrecedent) . '" title="' . $Article->AccrochePrecedent . '">Avant de lire cet article, assurez-vous d\'avoir lu l\'épisode précédent !</a></p>';
else
	$C['Precedent'] = '';


$ArbreCategories = Category::getTree($Article->getCategories());
if(isset($ArbreCategories['Sagas']))
{
	//appeler la fonction pour construire les sagas.
	Category::buildSaga($ArbreCategories['Sagas']);
	unset($C['Pods']['lastArticles']);
}

//à placer après la construction des sagas car l'arbre est modifié par outputTree.
$C['Categories'] = Category::outputTree($ArbreCategories);
$C['Similar'] = $Article->outputSimilar();

if($Article->Anecdote!='')
{
	Typo::setTexte($Article->Anecdote);
	$C['DidYouKnow'] = ParseMath(Typo::Parse());
	$C['DidYouKnowSource'] = $Article->SourceAnecdote;
}


//Déterminer les liens complémentaires.
//Infos complémentaires
$Complementary = array(
	'Lien court : ' . Anchor::omniShort($Article->ID),
	'<a href="/Contact#' . Link::omni($Article->Titre) . '">Signaler une erreur ou une faute</a>',
);

if(isset($_SESSION['Membre']['Pseudo']))
{
	//Lien de modification si c'est notre article
	if(isset($_SESSION['Membre']['Articles'][$Article->ID]))
	{
		if($Article->Date=='')
			$Complementary[1] = '<big><a href="' . Link::omni($Article->Titre,'/membres/Edit/') . '">Modifier l\'article</a></big>';
		else
			$Complementary[1] = '<a href="/Contact">Impossible de modifier un article paru directement, merci de nous contacter !</a>';
	}

	//Liens d'administration
	if(Member::is($_SESSION['Membre']['Pseudo'],'any'))
	{
		$Complementary[] = '<a href="' . Link::omni($Article->Titre,'/admin/') . '">Administrer</a> (actuel : <span class="' . Link::status($Article->Statut) . '">[' . $Article->Statut . '])</span>';
		if(Member::is($_SESSION['Membre']['Pseudo'],'censeurs'))
		{
			$Complementary[] = '<a href="' . Link::omni($Article->Titre,'/admin/Edit/') . '">Modifier l\'article</a>';
		}
		if(Member::is($_SESSION['Membre']['Pseudo'],'reffeurs'))
		{
			$Complementary[] = '<a href="' . Link::omni($Article->Titre,'/admin/Ref/') . '">Référencer l\'article</a>';
		}
		if(Member::is($_SESSION['Membre']['Pseudo'],'taggers'))
			$Complementary[] = '<a href="' . Link::omni($Article->Titre,'/admin/Tag/') . '">Classifier l\'article</a>';
	}
}

//Articles de la veille / du lendemain, uniquement si l'article est paru
if(!is_null($Article->Timestamp))
{
	$Param = Omni::buildParam(Omni::TRAILER_PARAM);
	$Param->Where = 'Omnilogismes.Sortie < FROM_UNIXTIME(' . $Article->Timestamp . ')';
	$Param->Limit = 1;
	$Param->Order = 'Omnilogismes.Sortie DESC';
	$Precedent = Omni::getTrailers($Param);
	if(count($Precedent)==1)
	{
		$C['Veille'] = $Precedent[0];
	}

	$Param->Where = 'Omnilogismes.Sortie > FROM_UNIXTIME(' . $Article->Timestamp . ')';
	$Param->Limit = 1;
	$Param->Order = 'Omnilogismes.Sortie';
	$Suivant = Omni::getTrailers($Param);
	if(count($Suivant)==1)
	{
		$C['Lendemain'] = $Suivant[0];
	}
	else
	{
		if($DateParutionProchainArticle - time() < 24 * 3600)
		{
			$C['Lendemain'] = 'ce soir, à minuit...';
		}
		else
		{
			$C['Lendemain'] = $C['Snippet']['nextArticle'];
		}
	}

	unset($Suivant, $Precedent);
}

// Meta description
if($Article->Accroche != '') {
	$C['head'][] = '<meta name="description" content="' . str_replace('"', '&quot;', $Article->getTitre() . '&nbsp;: ' . lcfirst($Article->getAccroche())) . '"/>';
}

prependPod('complementary', 'Informations complémentaires', Formatting::makeList($Complementary));

unset($Article);

//Administration des commentaires
$C['head'][] = '<meta property="fb:app_id" content="194500927293463"/>';
$C['head'][] = '<meta name="twitter:card" content="summary" />';
$C['head'][] = '<meta name="twitter:site" content="@Omnilogie" />';

if($Article->Statut != 'INDETERMINE' && $Article->Statut != 'ACCEPTE' && $Article->Statut != 'A_CORRIGER') {
	// Les articles refusés ne doivent pas être indexés
	$C['head'][] = '<meta name="robots" content="noindex">';
}

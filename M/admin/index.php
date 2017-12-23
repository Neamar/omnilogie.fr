<?php
/**
* Modèle : admin
* But : charger les différents pods d'administration
* Prérequis : $Articles, chargé par le contrôleur.
*/

$C['PageTitle']='Page principale d\'administration';
$C['CanonicalURL']='/admin/';

/*
* MODIFICATION DES PODS.
* Cette page ajoute plusieurs pods, et en supprime certains pour gagner de la place.
*/
//Virer certains pods pour gagner de la place :
unset($C['Pods']['author-stats'],$C['Pods']['modifiable'],$C['Pods']['randomArticle'],$C['Pods']['catCloud'],$C['Pods']['activeAuthor'],$C['Pods']['twitter']);

//Mettre à jour les pods avec les modifs
$C['Pods']['publiable']['Content']= Formatting::makeList(Omni::getTrailers(Admin::getProchains()));
$C['Pods']['lastactions']['Content']= Formatting::makeList(Event::getLast(15, 1,'%DATE% %LIEN% : %MODIF% par %AUTEUR% %DIFF%'));

//Articles en brouillon
$Param = Omni::buildParam(Omni::TRAILER_PARAM);
$Param->Where = 'Statut = "BROUILLON"';
$C['Pods']['brouillons']['Title']='Brouillons';
$C['Pods']['brouillons']['Content']=Formatting::makeList(Omni::getTrailers($Param));




$Standalone = unserialize(Cache::get('Datas','Events'));

$HTML = '
<form method="post" action="">
<select name="custom-event">';
foreach($Standalone as $Event=>$Files)
{
	$HTML .='<optgroup label="Événement &lt;' . ucfirst($Event) . '&gt;">';
	foreach($Files as $File=>$Infos)
	{
		//Le membre a-t-il le droit de faire cette action ?
		$Autorise=false;
		foreach($Infos['Access'] as $Role)
		{
			if(Member::is(AUTHOR,$Role))
			{
				$Autorise=true;
				break;
			}
		}

		//Faut-il déclencher un évenement ?
		if(isset($_POST['custom-event']) && $_POST['custom-event']==$File && $Autorise)
		{
			include(PATH . $File);
			$C['Message'] = 'Événément simulé avec succès. Rechargez la page pour le constater.';
		}

		$HTML .= '<option value="' . $File . '"' . ($Autorise?'':'disabled="disabled"') . '>' . $Infos['Description'] . '</option>';
	}

	$HTML .= '</optgroup>';
}
$HTML .='</select><br />
<input type="submit" value="Simuler l\'événenement" />
</form>';

$C['Sections']['Events']['Titre'] = 'Déclencheur manuel d\'événements';
$C['Sections']['Events']['Description'] = 'Simuler le déclenchement d\'un événement';
$C['Sections']['Events']['HTML'] = $HTML;

$Autre = array();

if(Member::is(AUTHOR,'admin'))
	$Autre[] = '<a href="https://developers.facebook.com/tools/comments">Administration des commentaires</a>';

if(Member::is(AUTHOR,'propositions'))
	$Autre[] = '<a href="/admin/Propositions">Administration des propositions</a>';

if(Member::is(AUTHOR,'censeurs'))
	$Autre[] = '<a href="/admin/Omnilogistes">Informations sur les membres</a>';

if(Member::is(AUTHOR,'admins'))
	$Autre[] = '<a href="/admin/Logs">Infos techniques et connexions</a>';

if(Member::is(AUTHOR,'censeurs'))
	$Autre[] = '<a href="/admin/Edit/">Accueil des censeurs</a>';

if(count($Autre)!=0)
{
	$C['Sections']['Autre']['Titre'] = 'Autres pages utiles';
	$C['Sections']['Autre']['Description'] = 'S\'enfoncer encore plus loin dans l\'administration...';
	$C['Sections']['Autre']['HTML'] = Formatting::makeList($Autre);
}

//Table des matières et paramètres par défauts
$C['TOC']=array();
foreach($C['Sections'] as $ID=>&$Section)
{
	if(!isset($Section['Max']))
		$Section['Max']=10;
	if(!isset($Section['Action']))
		$Section['Action']='';
	if(!isset($Section['Articles']))
		$Section['Articles']=array();
	if(!isset($Section['IsFileForm']))
		$Section['IsFileForm']=false;
	if(!isset($Section['SubmitCaption']))
		$Section['SubmitCaption']='Enregistrer les modifications';

	$C['TOC'][] = '<a href="#' . $ID . '" title="' . $Section['Description'] . '">' . $Section['Titre'] . '</a>';
}
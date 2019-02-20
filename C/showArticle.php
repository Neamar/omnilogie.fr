<?php
/**
* But : Contrôler l'existence d'un article avant de charger le modèle approprié.
* Permet de rediriger ou de faire échouer le script si nécessaire.
* Charge dans $Article les données relatives à l'article.
*/

//////////////////////////////////////////////////////
//Fonctionnalités du contrôleur :

//Récupérer le titre de la page

$TitreOmni = Encoding::decodeFromGet('Titre');
$TitreOmniSql = mysql_real_escape_string($TitreOmni);

//Vérifier que l'article existe :

//1 : Pas d'espace, pas de /o/ minuscule :
if(strpos($_GET['Titre'],' ')!==false || strpos($_SERVER['REQUEST_URI'],'/o/')!==false)
	Debug::redirect(Link::omni($TitreOmni),301);

//L'article existe-t-il ?
$Param = Omni::buildParam(OMNI_HUGE_PARAM);

$Param->Where = 'Omnilogismes.Titre="' . $TitreOmniSql . '" COLLATE utf8_unicode_ci';

$Article = Omni::get($Param);

if(count($Article)==0)
{
	//L'article tel quel n'existe pas. Regarder dans la table des redirections si on a un "match" :
	$Param->Select = 'Omnilogismes.Titre';
	$Param->Where = 'Omnilogismes.ID = (SELECT ID FROM OMNI_Redirection WHERE Histoire="' . $TitreOmniSql . '")';
	$Article=Omni::get($Param);
	if(count($Article)!=0)
		Debug::redirect(Link::omni($Article[0]->Titre));

	//Sinon, l'article n'existe vraiment pas :(
	//Encore un essai pour corriger : trouver un titre qui sonne pareil (algorithme SOUNDEX)
	$Param->Where = 'SOUNDEX(Titre) = SOUNDEX("' . $TitreOmniSql . '")';
	$Article=Omni::get($Param);
	if(count($Article)!=0)
		Debug::redirect(Link::omni($Article[0]->Titre));

	//Dernière chance : un petit coup de levenshtein
	$Articles = SQL::query('SELECT Titre FROM OMNI_Omnilogismes');
	$DistanceMin = 100;
	$TitreMin = null;
	$regexp = '`^' . preg_quote($TitreOmni) . '`i';
	$C['Liens']=array();
	while($Article=mysql_fetch_assoc($Articles))
	{
		$D = levenshtein($Article['Titre'],$TitreOmni);
		if($D<$DistanceMin)
		{
			$DistanceMin = $D;
			$TitreMin = $Article['Titre'];
		}
		if($D<=7 || preg_match($regexp,$Article['Titre']))
			$C['Liens'][$Article['Titre']] = 'Article similaire : <a href="' . Link::omni($Article['Titre']) . '">' . $Article['Titre'] . '</a>';
	}

	if($DistanceMin<5)
		Debug::redirect(Link::omni($TitreMin));
	if(count($C['Liens'])==1)
		Debug::redirect(Link::omni(key($C['Liens'])));

	//Bon et sinon c'est officiel, on jette l'éponge.
	$C['CustomError'] = 'L\'article <tt>' . $TitreOmni . '</tt> est introuvable.';
	return Debug::status(404);
}

//Sinon, l'article existe. L'enregistrer, puis passer au modèle.
$Article = $Article[0];




/*
//Mettre la page en cache en l'identifiant à partir de son ID.
if(false && Cache::page('omni-' . $Article->ID))
{

	//Si la mise en cache est active, on tient quand même à jour le compteur :
	SQL::update('OMNI_Omnilogismes',$Article->ID,array('_NbVues'=>'NbVues+1'));
}*/

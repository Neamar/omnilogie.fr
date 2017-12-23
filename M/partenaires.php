<?php
/**
* Modèle : partenaires
* La liste des "partenaires" du site.
*/

$C['PageTitle']='Partenaires du site';
$C['CanonicalURL']='/Partenaires';

$Sites=array(
"http://www.webrankinfo.com/annuaire/cat-171-gestion-des-connaissances.htm"=>"WebRank Info, aide au référencement.",
"http://lachal.neamar.fr/index.php"=>"Des mots exhumés des catacombes du dictionnaire français : le petit Lachal non illustré",
"http://neamar.fr/Res/Reflets/"=>"Subtilités de Reflets d'acide : la base de plusieurs omnilogismes.",
'http://villes-de-france.fr'=>'Découvrez Villes-de-france.fr : le guide des <a href="http://villes-de-france.fr">villes de france</a>.',
'http://www.fluxenet.fr'=>'<a href="http://www.fluxenet.fr" title="R&eacute;f&eacute;rencement gratuit de flux RSS">Annuaire RSS</a>',
'http://www.webizz.net/'=>'Annuaire de flux RSS',
'http://www.webjunior.net/'=>'<a href="http://www.webjunior.net/" target="_blank"><img src="http://www.webjunior.net/images/logo.gif" border="0" ALT="Annuaire de sites pour enfants et ados"></a>',
'http://www.top-du-top.com/index.php?ref=omnilogie.fr'=>'Annuaire Top Du Top',
'http://www.gralon.net/annuaire/news-et-media/magazines/magazine-culturel.htm'=>'Gralon : le roi des annuaires',
'http://dir.blogflux.com/'=>'Blog Directory by Blog Flux',
'http://www.etoile-blog.com'=>'Annuaire.',
'http://www.annuaireblog.org'=>'Blog',
'http://www.nnuaire.com/'=>'@nnuaire',
'http://www.monsurf.com'=>'annuaire gratuit',
'http://www.meceoo.com/e-learning-c58-p1.html'=>'MeceooAnnuaire - E-learning',
'http://www.annubel.com/quizz-et-tests-c349-p1.html'=>'Annuaire Gratuit - Quizz et Tests',
'http://www.clikeo.fr/annuaire/'=>'Annuaire gratuit',
'http://www.tagbox.fr/'=>'Annuaire',
'http://www.ahalia.com/culture-divers.php'=>'Culture divers',
);
ksort($Sites);

$C['Partenaires']=array();

foreach($Sites as $URL=>$Desc)
{
	$URLPart=parse_url($URL);
	$C['Partenaires'][] =  '<a href="' . $URL . '"><img src="https://www.google.com/s2/favicons?domain=' . $URLPart['host'] . '" style="border:none;" class="nonflottant" alt="" /><small>' . $URLPart['host'] . '</small></a> : ' . $Desc;
}

<?php
/**
* Contrôleur : images.php
* But : Afficher une image dédiée à l'article
*
* Si $_GET['Titre'] est défini, affiche l'image pour cet article.
* Sinon, affiche le dernier article.
*/
//////////////////////////////////////////////////////
//Fonctionnalités du contrôleur :

if(isset($_GET['Titre']))
	$TitreOmni = Encoding::decodeFromGet('Titre');
//Vérifier que l'article existe :

//L'article existe-t-il ?
$Param = Omni::buildParam(Omni::SMALL_PARAM);

if(isset($TitreOmni))
	$Param->Where = 'Omnilogismes.Titre="' . $TitreOmni . '" OR Omnilogismes.Titre="' . $TitreOmni . '?"';
else
{
	header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
	header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date dans le passé
	$Param->Where = 'Omnilogismes.Sortie = CURDATE()';
}

$Article = Omni::get($Param);

if(count($Article)==0)
	return Debug::status(404);

//Sinon, l'article existe.
$Article = $Article[0];

Typo::setTexte($Article->Omnilogisme);

$doc = new DOMDocument('1.0','ISO-8859-1');
$Replace=array(
'<math>'=>'',
'</math>'=>'',
'	<li>'=>'<li>',
'<li>'=>'<li>    * ',
);

$doc->loadHTML('<body>' . str_replace(array_keys($Replace),array_values($Replace),Typo::parse()) . '</body>');
$Article->Omnilogisme = trim($doc->getElementsByTagName('body')->item(0)->textContent);
$Banniere = PATH . '/images/Banner/Thumbs/' . $Article->ID . '.png';
if(!is_file($Banniere))
	$Banniere = PATH . '/images/Banner/Thumbs/Default.png';









//L'image en elle-même
header("Content-type: image/png");

function makeTextBlock($text, $fontfile, $fontsize, $width,$left=0)
{
	$words = explode(' ', $text);
	$lines = array($words[0]);
	$currentLine = 0;
	for($i = 1; $i < count($words); $i++)
	{
		$lineSize = imagettfbbox($fontsize, $left, $fontfile, $lines[$currentLine] . ' ' . $words[$i]);
		if($lineSize[2] - $lineSize[0] < $width)
		{
			$lines[$currentLine] .= ' ' . $words[$i];
		}
		else
		{
			$currentLine++;
			$lines[$currentLine] = $words[$i];
		}
	}

	return implode("\n", $lines);
}

$File=PATH . '/images/GD/OmniCache/' . $Article->ID . '.png';
if(!is_file($File))
{
	$Width=500;
	$Height=200;
	$TitreFont=PATH . '/images/GD/Ayita.ttf';
	$TexteFont=PATH . '/images/GD/Serif.ttf';//Grandesign Neue Serif.ttf

	//Créer l'image.
	$Draft = imagecreatetruecolor($Width,$Height);
	imagesavealpha($Draft, true);

	//Allouer les couleurs nécessaires :
	$backColor = imagecolorallocatealpha($Draft, 0, 0, 0, 127);
    imagefill($Draft, 0, 0, $backColor);
	$white = imagecolorallocatealpha($Draft, 255, 255, 255,1);
	$gray=imagecolorallocate($Draft,128,128,128);
	$black = imagecolorallocate($Draft, 0, 0, 0);
	$grayMore=imagecolorallocate($Draft,97,97,95);
	$blue=imagecolorallocate($Draft,0,0, 128);

	imagefilledrectangle($Draft,1,1,$Width-2,$Height-1,$white);//Un fond en alpha de 0.5

	//Le titre
	imagettftext($Draft, 17, 0, 10, 21, $black, $TitreFont, $Article->Titre);
	imageline($Draft,0,23,$Width,23,$gray);


	//L'accroche
	imageline($Draft,0,93,$Width,93,$gray);//Dessiner la ligne tout de suite pour l'écraser en partie avec l'image après
	imagecopy($Draft,imagecreatefrompng($Banniere),0,23,0,0,293,71);

	$Lignes=makeTextBlock($Article->Accroche,$TexteFont,11,$Width- 300);
	$LignesUtiles = array_chunk(explode("\n", $Lignes),4);//Couper l'accorche à 4 lignes maximum.
	$Lignes = implode("\n",$LignesUtiles[0]);

	$Taille=imagettfbbox(12,0,$TitreFont,$Lignes);
	imagettftext($Draft, 11, 0, 298, 72 - .5*($Taille[1]-$Taille[7]), $grayMore,$TexteFont, $Lignes);



	//L'article
	$Lignes=makeTextBlock('    ' . str_replace("\n\n","\n",$Article->Omnilogisme),$TexteFont,10,$Width-10);
	imagettftext($Draft, 10, 0, 5, 110, $black,$TexteFont, $Lignes);
	imageline($Draft,0,$Height - 16,$Width,$Height - 16,$gray);

	//Pour en savoir plus :
	imagefilledrectangle($Draft,0,$Height-15,$Width,$Height,$white); // Redessiner
	imagettftext($Draft, 10, 0, 5, $Height-4,$blue,$TexteFont, 'Rendez-vous sur http://omnilogie.fr' . Link::omniShort($Article->ID) . ' pour lire la suite...');


	//Un rectangle autour pour distinguer les contours de l'image
	imagerectangle($Draft,0,0,$Width-1,$Height-1,$gray);

	imagepng($Draft,$File);
	imagedestroy($Draft);
}

echo file_get_contents($File);
exit();
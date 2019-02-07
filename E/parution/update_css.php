<?php
/**
* Fichier d'évènement
* Event::PARUTION
*
* @standalone
* @access bannières
*
* Mettre à jour la version minifiée du CSS
*/
//echo filemtime(PATH . '/CSS/omni.min.css') . ' | ' . filemtime(PATH . '/CSS/omni.css');

$derniereMin = filemtime(PATH . '/CSS/omni.min.css');
if(filemtime(PATH . '/CSS/omni.css') + 3600 > $derniereMin || filemtime(PATH . '/CSS/Typo.css') > $derniereMin)
{

	$buffer = file_get_contents(PATH . '/CSS/Typo.css') . "\n\n" . file_get_contents(PATH . '/CSS/omni.css');

//Enlever l'aération du code, les commentaires.
	$buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);
	$buffer = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $buffer);
	$buffer = str_replace(array('{ ',', ',': ','; ','} ',';}','}'), array('{',',',':',';','}','}',"}\n"), $buffer);

//Optimiser les couleurs
	function dec2hex($v)
	{
		return str_pad(dechex($v),2,' ',STR_PAD_LEFT);
	}
	function rgbToHex($C)
	{
		return '#' . dec2hex($C[1]) . dec2hex($C[2]) . dec2hex($C[3]);
	}
	$buffer = preg_replace_callback('`rgb\(([0-9]+),([0-9]+),([0-9]+)\)`','rgbToHex',$buffer);//rgb(,,) = #...
	$buffer = preg_replace('`#([0-9A-F])\1([0-9A-F])\2([0-9A-F])\3`i','#$1$2$3',$buffer);//#111111 = #111
	file_put_contents(PATH . '/CSS/omni.min.css',$buffer);
}

<?php
/**
* Construire le cache s'il n'existe pas,
* Renvoyer directement le cache
*/

if(!Cache::$pageExists)
{
	ob_start();
	include 'V/' . Cache::$pageCachee . '.php';
	$_C = array(
		'PageTitle'=>$C['PageTitle'],
		'CanonicalURL'=>$C['CanonicalURL']
	);
	Cache::set('Datas','Cache-' . Cache::$pageId,serialize($_C));
	Cache::set('Page',Cache::$pageId,ob_get_flush());
}
else
{
	echo Cache::get('Page',Cache::$pageId);
}
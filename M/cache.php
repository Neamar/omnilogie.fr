<?php
/**
* Mod�le : Cache
* But : Mettre en page une cache
*/
//Cache

//Si le cache existe d�j�, on charge le titre et l'adresse canonique... et c'est tout
if(Cache::$pageExists)
{
	$C = array_merge($C,unserialize(Cache::get('Datas','Cache-' . Cache::$pageId)));
	return;
}
else
{
	//Sinon, on inclut le mod�le qui aurait d� �tre charg� sans le cache
	include 'M/' . Cache::$pageCachee . '.php';
}


<?php
/**
* Modèle : Cache
* But : Mettre en page une cache
*/
//Cache

//Si le cache existe déjà, on charge le titre et l'adresse canonique... et c'est tout
if(Cache::$pageExists)
{
	$C = array_merge($C,unserialize(Cache::get('Datas','Cache-' . Cache::$pageId)));
	return;
}
else
{
	//Sinon, on inclut le modèle qui aurait dû être chargé sans le cache
	include 'M/' . Cache::$pageCachee . '.php';
}


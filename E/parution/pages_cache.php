<?php
/**
* Fichier d'évènement
* Event::PARUTION
*
* @standalone
* Supprime toutes les pages en cache pour la journée.
*/

if(Cache::exists('Page','index'))
	Cache::remove('Page','index');

if(Cache::exists('Page','timeline'))
	Cache::remove('Page','timeline');

if(Cache::exists('Page','stats'))
	Cache::remove('Page','stats');

if(Cache::exists('Page','toc'))
	Cache::remove('Page','toc');
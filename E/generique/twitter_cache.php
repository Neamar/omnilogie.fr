<?php
/**
* Fichier d'évènement générique
*
* @standalone
* Mettre à jour le pod "Twitter"
*/
//return;

$TweetsRaw = External::fetch('http://search.twitter.com/search.json?q=Omnilogie');

$Tweets = json_decode($TweetsRaw, true);
$Tweet = $Tweets['results'][0];


$D = strtotime($Tweet['created_at']);
$T = array
(
	'ID'=>$Tweet['id_str'],
	'User'=>$Tweet['from_user'],
	'ScreenName'=>$Tweet['from_user'],
	'Tweet'=>utf8_decode($Tweet['text']),
	'Date'=>$D,
	'Ecart'=>floor((time() - $D)/3600)
);

Cache::set('Pods','twitter','<p><a href="http://twitter.com/' . $T['ScreenName'] . '">' . $T['User'] . '</a> ' . $T['Tweet'] . '<br />

<small>Posté <a href="http://twitter.com/' . $T['ScreenName'] . '/status/' . $T['ID'] . '">il y a ' . $T['Ecart'] . ' heure' .($T['Ecart']>1?'s':'') . '</a>.</small></p>');
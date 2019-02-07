<?php
/**
* Fichier d'évènement
* Event::PARUTION
*
* @standalone
* @access admins
*
* Télécharge 8 sources "pour aller plus loin" et met leur titre.
*/

function getTitle($URL)
{
	$olderror = error_reporting(0);

	$Titre = $URL;
	if(strpos($URL,'http://')===0)
	{

		if(!preg_match('#/wiki/(.+)$#',$URL,$Wikilike))
		{
			$DL = curl_init();
			curl_setopt($DL, CURLOPT_URL,$URL);
			curl_setopt($DL, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($DL, CURLOPT_USERAGENT, 'Mozilla/5.0 Omnilogie.fr getter for page title, contact neamar@neamar.fr for details');
			$HTML = curl_exec($DL);
			curl_close($DL);
			unset($DL);

			ob_start();
			$dom=new DOMDocument();
			$dom->loadHTML($HTML);
			$Titre=utf8_decode($dom->getElementsByTagName("title")->item(0)->textContent);
			unset($dom,$HTML);
			ob_end_clean();
		}
		else
		{//URL Wikipédia facilement compréhensible
			$Titre = str_replace('_',' ',urldecode($Wikilike[1]));
		}
	}

	error_reporting($olderror);

	return $Titre;
}

$URLs=SQL::query('SELECT OMNI_More.ID,URL,Reference
FROM OMNI_More
LEFT JOIN OMNI_Omnilogismes ON (OMNI_Omnilogismes.ID=OMNI_More.Reference)
WHERE ISNULL(OMNI_More.Titre)
AND !ISNULL(OMNI_Omnilogismes.Sortie)
LIMIT 8
');

while($URL=mysql_fetch_assoc($URLs))
{
	$URL['Titre']=getTitle($URL['URL']);
	SQL::update('OMNI_More',$URL['ID'], array('Titre'=> addslashes($URL['Titre'])));
}


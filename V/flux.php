<?php
/**
* ×××××××××××××××××××××××××××××××××
* CE FICHIER EST EN UTF-8
* Layout :

*/
//Flux

/**
* Raccourci pour encoder en utf8 les données
* @param $Data:String les données à afficher
*/
function utf($data)
{
	echo utf8_encode($data);
}

header('Content-Type: application/rss+xml; charset=utf-8');
//Afficher le flux
echo '<?xml version="1.0" encoding="UTF-8" ?>';
?>
<rss version="2.0">
<channel>
	<title>Omnilogismes du jour : questions et réponses de culture générale</title>
	<link>http://omnilogie.fr</link>
	<image>
		<url>http://omnilogie.fr/images/Question.png</url>
		<title>Omnilogismes du jour : questions et réponses de culture générale</title>
		<link>http://omnilogie.fr</link>
	</image>
	<description>Chaque jour, un article de culture générale sur tout et n'importe quoi. Une infusion de savoir quotidienne !</description>
	<managingEditor>omni@neamar.fr (Administrateurs Omnilogie)</managingEditor>
	<language>fr-FR</language>

<?php foreach($C['Articles'] as $Article) { ?>
	<item>
		<title><![CDATA[<?php utf($Article->Titre) ?>]]></title>
		<pubDate><?php echo date('r',$Article->Timestamp) ?></pubDate>
		<author>omni@neamar.fr (<?php utf($Article->Auteur) ?>)</author>
		<guid><?php echo URL . Link::omniShort($Article->ID) ?></guid>
		<link>http://omnilogie.fr<?php echo str_replace('%2FO%2F','/O/',urlencode(Link::omni($Article->Titre))) ?></link>
		<description>
			<![CDATA[<?php utf(str_replace($C['Cherche'],$C['Remplace'],$Article->getBanner())); ?>

			<?php utf(str_replace($C['Cherche'],$C['Remplace'],$Article->outputFull())); ?>
			]]>
		</description>
	</item>
<?php } ?>
</channel>
</rss>
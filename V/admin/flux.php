<?php
/**
* ÃÃÃÃÃÃÃÃÃÃÃÃÃÃÃÃÃÃÃÃÃÃÃÃÃÃÃÃÃÃÃÃÃ
* CE FICHIER EST EN UTF-8
* Layout :

*/
//Flux

/**
* Raccourci pour encoder en utf8 les donnÃ©es
* @param $Data:String les donnÃ©es Ã  afficher
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
	<title>Flux d'administration d'Omnilogie</title>
	<link>http://omnilogie.fr</link>
	<image>
		<url>http://omnilogie.fr/images/Question.png</url>
		<title>Admin</title>
		<link>http://omnilogie.fr</link>
	</image>
	<description>Modifications du site en quasi temps rÃ©el.</description>
	<managingEditor>omni@neamar.fr (Administrateurs Omnilogie)</managingEditor>
	<language>fr-FR</language>

<?php foreach($C['Modifs'] as $Modif) { ?>
	<item>
		<title><?php utf($Modif['title']) ?></title>
		<pubDate><?php echo $Modif['pubDate'] ?></pubDate>
		<author>omni@neamar.fr (<?php utf($Modif['author']) ?>)</author>
		<guid isPermaLink="false"><?php echo $Modif['guid'] ?></guid>
		<link><?php utf($Modif['link']) ?></link>
		<description>
			<?php utf($Modif['description']) ?>
		</description>
	</item>
<?php } ?>
</channel>
</rss>';
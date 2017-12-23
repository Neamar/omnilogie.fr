<?php
/**
* Layout :
* - Liste des applications
* Menu : r�partition par statut
*/
//Afficher les articles
?>
<h3><span>Omnilogie sur smartphone </span></h3>
<p>En plus de son site internet, vous pouvez acc�der � toute la culture d'Omnilogie depuis votre smartphone !</p>

<ul>
<?php
foreach($C['Apps'] as $t => $_)
{
	echo '<li><a href="#' . preg_replace('`[^a-z]`', '', $t) . '">' . $t . '</a></li>' . "\n";
}
?>
</ul>

<?php
foreach($C['Apps'] as $t => $app)
{
	echo '<hr /></section>

	<section>
	<h3 id="' . preg_replace('`[^a-z]`', '', $t) . '"><span>' . $t . '</span></h3>
	<p style="text-align:center;"><img src="' . $app['img'] . '" alt="' . $t . '" /></p>
	<p>' . $app['desc'] . '<br />
	<small>Application d�velopp�e par : ' . $app['dev'] . '</small>.</p>
	<p class="message">&rarr; <a href="' . $app['url'] . '">T�l�charger l\'application</a></p>' . "\n";
}
?>
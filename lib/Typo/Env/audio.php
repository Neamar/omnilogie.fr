<?php
$envSources = explode("\n", $envContent);
$envContent = '
<audio controls preload="auto" autobuffer height="35" width="200">
';

foreach($envSources as $source)
{
	$envContent .= '<source src="' . str_replace(array('&nbsp;: ','. '),array(':','.'), $source) . '" />' . "\n";
}

$envContent .= 'Votre navigateur ne supporte pas les fichiers audio. Il est temps de quitter les annÃ©es 90 et de se procurer un vÃ©ritable logiciel !
</audio>';
?>
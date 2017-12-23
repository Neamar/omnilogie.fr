<h3><span>Documentation</span></h3>

<?php
//Puis afficher la doc :

echo '<ol>';
foreach($C['File'] as $Nom=>$File)
	echo '<li><a href="#fichier-' . $Nom . '">' . $Nom . '</a></li>';
echo '</ol>';
echo '</section>';

foreach($C['File'] as $Nom=>$File)
{
	echo '<section><h3 id="fichier-' . $Nom . '"><span>' . $Nom . '</span></h3>';
	PhpDoc::makeDocFor($File);
	echo '</section>';
}
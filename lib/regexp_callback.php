<?php

function ParseMath($buffer)
{//Fonction appelée à la fin de l'éxecution du script si on a demandé à utiliser les balises mathématiques.
	return preg_replace('#\<math\>(.+)\<\/math\>#isU', '\\($1\\)',$buffer);
}

<?php

function ParseMath($buffer)
{//Fonction appel�e � la fin de l'�xecution du script si on a demand� � utiliser les balises math�matiques.
	return preg_replace('#\<math\>(.+)\<\/math\>#isU', '\\($1\\)',$buffer);
}

<?php

function ParseMath($buffer)
{//Fonction appelée à la fin de l'éxecution du script si on a demandé à utiliser les balises mathématiques.
	return preg_replace_callback('#\<math\>(.+)\<\/math\>#isU', 'RegexpCallback',$buffer);
}

function RegexpCallback($Partie)
{
	$Partie=str_replace(array('&lt;','&gt;'),array('<','>'),$Partie);


//On va tenter d'afficher le TeX en HTML s'il n'y a que des symboles conventionnels et simples (pas de \frac) :
	$TexHtml=array(
	'\alpha'				=>'&alpha;',
	'\beta'					=>'&beta;',
	'\gamma'				=>'&gamma;',
	'\delta'				=>'&delta;',
	'\Delta'				=>'&Delta;',
	'\epsilon'				=>'&epsilon;',
	'\zeta'					=>'&zeta;',
	'\eta'					=>'&eta;',
	'\theta'				=>'&theta;',
	'\iota'					=>'&iota;',
	'\kappa'				=>'&kappa;',
	'\lambda'				=>'&lambda;',
	'\mu'					=>'&mu;',
	'\omicron'				=>'&omicron;',
	'\pi'					=>'&pi;',
	'\Pi'					=>'&Pi;',
	'\rho'					=>'&rho;',
	'\sigma'				=>'&sigma;',
	'\tau'					=>'&tau;',
	'\phi'					=>'&phi;',
	'\chi'					=>'&chi;',
	'\psi'					=>'&psi;',
	'\omega'				=>'&omega;',
	'\Omega'				=>'&Omega;',
	'\infty'				=>'&infin;',
	'\,'					=>'&nbsp;',
	'\;'					=>'&nbsp;',
	'\ldots'				=>'&hellip;',
	'\dots'					=>'&hellip;',
	'\leq'					=>'&le;',//Inférieur ou égal
	'\le'					=>'&le;',//Inférieur ou égal
	'\geq'					=>'&ge;',//Supérieur ou égal
	'\ge'					=>'&ge;',//Supérieur ou égal
	'\%'					=>'%',
	'<'						=>'&lt;',
	'>'						=>'&gt;',
	'\times'				=>'&times;',//signe de multiplication
	'\div'					=>'&#247;',//Signe de la division
	'\pm'					=>'&plusmn;',//Plus ou moins
	'\neg'					=>'&#172;',//Contraposée
	'\neq'					=>'&#8800;',//Différent
	'\blacktriangle'		=>'&#9650;',
	'\vartriangle'			=>'&#9651;',
	'\uparrow'				=>'&#8593;',
	'\in'					=>'&#8712;',
	'\sin'					=>'sin',
	'\cos'					=>'cos',
	'\tan'					=>'tan',
    '\sharp'				=>'&#9839;',

	'\mathbb{N}'			=>'&#x2115;',
	'\mathbb{Z}'			=>'&#x2124;',
	'\mathbb{Q}'			=>'&#x211A;',
	'\mathbb{R}'			=>'&#x211D;',
	'\mathbb{C}'			=>'&#x2102;',
	);

	$TryHTML=str_replace(array_keys($TexHtml),array_values($TexHtml),$Partie[1]);

	if(!preg_match('#\\\\[a-z]#',$TryHTML) && $TryHTML{strlen($TryHTML)-1}!='~')
	{//On peut afficher comme du HTML, alors on en profite :)
		$TryHTML=preg_replace('#_{(.+)}#U','<sub>$1</sub>',$TryHTML);
		$TryHTML=preg_replace('#_(.)#U','<sub>$1</sub>',$TryHTML);

		$TryHTML=preg_replace('#\^{(.+)}#U','<sup>$1</sup>',$TryHTML);
		$TryHTML=preg_replace('#\^(.)#U','<sup>$1</sup>',$TryHTML);
		return '<span class="TexTexte">' . $TryHTML . '</span>';
	}
	else//sinon, afficher l'image.
	{
		if($Partie[1]{strlen($Partie[1])-1}=='~')
			$Partie[1]=substr($Partie[1],0,-1);
		return '<img src="http://neamar.fr/Latex/TEX.php?m=' . rawurlencode(str_replace(' ','\,',$Partie[1])) . '" alt="' . htmlentities($Partie[1]) . '" class="TexPic" />';
	}
}

?>

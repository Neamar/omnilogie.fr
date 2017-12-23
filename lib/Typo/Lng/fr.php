<?php
self::$Balise=array(
//Balise � un argument.
	'#\\\\(emph|i){(.+)}#isU'=>'<em>$2</em>',													//Mise en emphase (italique le plus souvent)
	'#\\\\(emph|i){(.+)}#sUi'=>'<em>$2</em>',													//On est oblig� de tricher pour permettre l'imbrication de cette balise.
	'#\\\\(emph|i){(.+)}#Uis'=>'<em>$2</em>',													//...
	'#\\\\emph\[([1-6])\]{(.+)}#sUi'=>'<em class="emph$1">$2</em>',				//Emphases sp�ciales.
	'#\\\\textit{(.+)}#Uis'=>'<em class="italique">$1</em>',								//Vraie mise en italique (ne s'annule pas quand imbriqu�e)
	'#\\\\(textbf|b){(.+)}#isU'=>'<strong>$2</strong>',										//Mise en gras (bold face)

	'#\\\\textss{(.+)}#isU'=>'<span class="ss">$1</span>',								//Texte sans serif
	'#\\\\(textms|texttt){(.+)}#isU'=>'<tt>$2</tt>',												//Texte monospace

	'#\\\\quote{(.+)}#isU'=>'<q>$1</q>',															//Citation
	'#\\\\up{(.+)}#isU'=>'<sup>$1</sup>',															//Texte en exposant
	'#\\\\down{(.+)}#isU'=>'<sub>$1</sub>',														//Texte en indice
	'#\\\\small{(.+)}#isU'=>'<small>$1</small>',												//Petit texte
	'#\\\\big{(.+)}#isU'=>'<big>$1</big>',															//Gros texte
	'#\\\\quickref{(.+)}#isU'=>'<span class="quickref">$1</span>',					//R�f�rence rapide.
	'#\\\\verse{(.+)}#isU'=>'<span class="ver">$1</span>',								//Pour �crire des vers.
	'#\\\\(century|c){\s?-?([XVICM]+)}#isU'=>'<span class="century">$2</span><sup>e</sup>',//Pour �crire un si�cle en chiffres romains confortables
	'#\\\\roman{([XVICM]+)}#isU'=>'<span class="roman">$1</span>',			//Pour �crire un si�cle en chiffres romains confortables
	'#\\\\verbatim{(.+)}#isU'=>'$1',																		//Pour �crire des balises sans les parser. Cf. $Escape_And_Prepare.
	'#\\\\date{-?([0-9]+)}#'=>'$1',																			//Balise purement s�mantique
	'#\\\\taxonomy{(.+)}#'=>'<em>$1</em>',																			//Nom taxonomique

//Balise � deux arguments
	'#\\\\acronym\[(.+)\]{(.+)}#isU'=>'<acronym title="$1">$2</acronym>',		//Acronyme
	'#\\\\abrev\[(.+)\]{(.+)}#isU'=>'<abbr title="$1">$2</abbr>',						//Abr�viation
	'#\\\\color\[(.+)\]{(.+)}#isU'=>'<span style="color:$1;">$2</span>',			//Texte en couleur
	'#\\\\color\[(.+)\]{(.+)}#siU'=>'<span style="color:$1;">$2</span>',			//Texte en couleur (2)
	'#\\\\color\[(.+)\]{(.+)}#sUi'=>'<span style="color:$1;">$2</span>',			//Texte en couleur (2)
	'#\\\\css\[(.+)\]{(.+)}#isU'=>'<span style="$1;">$2</span>',					//Texte en couleur (2)
	'#\\\\(lien|link|l)\[(.+)\]{(.+)}#iU'=>'<a href="$2">$3</a>',							//Lien
	'#\\\\image\[nf\|(.*)\]{(.+)}#iU'=>'<img src="$2" alt="$1" title="$1" class="nonflottant centre"/>',			//Mettre une omage et son texte alternatif
	'#\\\\image\[(.*)\]{(.+)}#iU'=>'<img src="$2" alt="$1" title="$1" />',			//Mettre une image et son texte alternatif
	'#\\\\labelimage\[(.*)\]{(.+)}#iU'=>'<span class="labelimage"><span><img src="$2" alt="$1" title="$1" /><br />$1</span></span>',			//Mettre une image et son texte alternatif
	'#\\\\label\[([a-z]+)\]{(.*)}#isU'=>'<span id="$1">$2</span>',						//Pour faire des r�f�rences dans les longs textes. (signalement)
	'#\\\\ref\[(.+)\]{(.+)}#isU'=>'<a href="#$1" title="Voir $1">$2</a>',			//Pour faire des r�f�rences dans les longs textes. (d�placement)
	);

self::$_SpecialChar=array(
	'&amp&nbsp;; '	=>'&amp;',						//Esperluette.
	'  '							=>' ',								//Double espace
	' &nbsp;'				=>'&nbsp;',						//Espace plus espace ins�cable : donne une espace ins�cable.
	'. . .'						=>'...',								//Points de suspension en un unique caract�re.
	' .'							=>'.',								//Le point absorbe l'espace le pr�c�dant.
	'. �'						=>'.�',								//Le guillemet absorbe l'espace du point.
	'. &rdquo;'			=>'.&rdquo;',					//Idem
	'� ,'						=>'�,',								//Idem
	'&rdquo; ,'			=>'&rdquo;,',					//Idem
	'&rdquo; .'			=>'&rdquo;.',					//Idem
	'&rdquo;&nbsp;.'	=>'&rdquo;.',					//Idem
	'\\shy&nbsp;; '					=>'&shy;',						//Tiret de c�sure optionnelle
	'\\nbsp&nbsp;; '					=>'&nbsp;',						//Tiret de c�sure optionnelle
);

self::$SpecialChar=array(
	' &nbsp;'		=>'&nbsp;',						//Espace plus espace ins�cable : donne une espace ins�cable.
	'? &nbsp;!'	=>'&#8253;',					//Point exclarrogatif ?!
	'! &nbsp;?'	=>'&#8253;',					//Point exclarrogatif !?
	'?&nbsp;!'	=>'&#8253;',						//Point exclarrogatif ?!
	'!&nbsp;?'	=>'&#8253;',						//Point exclarrogatif !?
	'...'				=>'&hellip;',					//Points de suspension en un unique caract�re.

	' .'				=>'.',									//Le point absorbe l'espace le pr�c�dant.
	'� ,'			=>'�,',									//Idem

	' �C'				=>'&nbsp;&#8451;',		//Degr� Celsius
	'�C'				=>'&nbsp;&#8451;',		//Degr� Celsius
	' �F'				=>'&nbsp;&#8457;',		//Degr� Fahrenheit
	'�F'				=>'&nbsp;&#8457;',		//Degr� Fahrenheit
	'n�'				=>'n<sup>o</sup>',
	'oe'				=>'&oelig;',						//Ligature du oe minuscule : ?
	'OE'				=>'&OElig;',					//Ligature du OE majuscule : ?
	'Oe'				=>'&OElig;',
	'ae'				=>'&aelig;',						//Ligature du ae minuscule : �
	'AE'				=>'&AElig;',						//Ligature du AE majuscule : �
	'Ae'				=>'&AElig;',

	'---'				=> '&mdash;',					//Tiret quadratin (pour les dialogues)
	'--'					=>'&ndash;',					//Tiret demi quadratin (pour les incises)
	' - '				=>' &ndash; ',					//Tiret demi quadratin non signal� (pour les incises)
	' -,'				=>' &ndash;,',					//Tiret demi quadratin non signal� (pour les incises)
	' -;'				=>' &ndash;;',					//Tiret demi quadratin non signal� (pour les incises)
);

self::$Ponctuation=array(
	'#&#'										=>'&amp;',//Esperluette HTML. � faire en premier, car des & apparaitront en cacact�res sp�ciaux apr�s.
	//Enlever les d�lires litt�raires tels que les r�p�titions de ! ou de ...
	"#!{3,}#"								=>'!!',// !!!!!!!!!!! => !!
	"#\.{4,}#"								=>'...',// ......... => ...

	//Caract�res doubles et ponctuation forte  : (espace fine avant, espace forte apr�s)
	//Concerne : ". ; : ? !"
	"#\s?([;:!?])\s?#isU"					=>'&nbsp;$1 ',
	'#\! &nbsp;\!#'							=>'!!',//Double point d'exclamation

	//Caract�res finaux, de segmentation : . , ...
	"#\s?([\.,])\s?#isU"					=>'$1 ',
	"#([0-9]) ,([0-9])#"					=>'$1,$2',//Nombres �crits avec des virgules.
	'#\. \. \.#'										=>'...',//La regexp n'est pas parfaite : il faut la corriger dans le cas des points de suspension.
	"#\. (-?[a-z]\.) #isU"				=>'.$1',//Et des abr�viations ! (penser au - dans le cas de J.-C.)
	"#\. ([-a-z]\.) #sUi"				=>'.$1',//Et des abr�viations ! (� r�p�ter)
	"#([a-z]\.[a-z]\.) ([a-z])#sUi"				=>'$1$2',//Rep�rer le dernier si non suivi d'un point
	"#([0-9])(\.|,) ([0-9])#sU"				=>'$1$2$3',//Et des chiffres !

	//Guillemets internes : les guillemets � l'int�rieur de guillemet. Ils sont signal�s dans le texte par des "' texte '".
	'#\s?"\'(.+)\'"\s?#isU'				=>' &ldquo;$1&rdquo; ',

	//Guillemets : remplacer les guillemets typographiques par des guillemets fran�ais..
	'#(\s)?"(.+)"(\s)?#isU'				=>'$1�&nbsp;$2&nbsp;�$3',

	//Et les guillemets manuels doivent �tre modifi�s aussi.
	'#� (.+) �#Us'							=>'�&nbsp;$1&nbsp;�',
	'#�([^\s&].+[^;])�#Us'							=>'�&nbsp;$1&nbsp;�',

	//Tenter de rattraper les imbrications de guillemets mal faites
	'#�([a-z0-9].+)�#iU'					=>' &ldquo;$1&rdquo; ',

	//Entames de dialogues
	'#\n- #isU'								=>"\n&mdash; ",

	//Unit�s : elles doivent �tre pr�c�d�es d'une espace fine ins�cable.
	"#([0-9., ][0-9]) ?([%a-z$&]+)(\s|$)#i"		=>'$1&nbsp;$2$3',

	//Nombre d�cimaux
	"#([0-9])\.([0-9])#i"					=>'$1,$2',

	//Parenth�ses et ponctuations
	"#(\?|\!|\.) \)#"						=>'$1)',
);
?>
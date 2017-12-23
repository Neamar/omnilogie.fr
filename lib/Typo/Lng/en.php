<?php
	unset(self::$SpecialChar['oe']);//Pas de ligature du oe
	unset(self::$SpecialChar['OE']);
	unset(self::$SpecialChar['Oe']);
	unset(self::$SpecialChar['ae']);//Pas de ligature du ae
	unset(self::$SpecialChar['AE']);
	unset(self::$SpecialChar['Ae']);
	self::$SpecialChar[' - ']='&mdash;';//Tiret full cardatin sans espaces ins�cables
	self::$SpecialChar[' -,']='&mdash;,';
	self::$SpecialChar[' -;']='&mdash;;';


//La ponctuation
	self::$Ponctuation["#\s?([;:!?])\s?#isU"]='$1 ';// Le point vigule

	self::$Ponctuation['#\s?"\'(.+)\'"\s?#isU']=' �$1� ';	//Guillemets internes : les guillemets � l'int�rieur de guillemet. Ils sont signal�s dans le texte par des "' texte '".
	self::$Ponctuation['#(\s)?"(.+)"(\s)?#isU']='$1&ldquo;$2&rdquo;$3';//Guillemets : remplacer les guillemets typographiques par des guillemets anglais.
	self::$Ponctuation['#� (.+) �#Us']='�&nbsp;$1&nbsp;�';//Et les guillemets manuels doivent �tre modifi�s aussi.
	unset(self::$Ponctuation['#�([a-z0-9].+)�#iU']);
	self::$Ponctuation['#\&rdquo;([a-z0-9].+)\&ldquo;#iU']=' �&nbsp;$1&nbsp;� ';//Tenter de rattraper les imbrications de guillemets mal faites

	self::$Ponctuation['#\n- #isU']="\n&mdash;";//Entames de dialogues
?>
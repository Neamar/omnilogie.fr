<?php
/**
* Layout :
* - Date
* - �v�nements
*/

?>
<h3><span>Frise chronologique</span></h3>
<p>Le temps passe, les articles paraissent... au fil du calendrier, la petite Histoire s'inscrit ici. Tous les �venements trait�s sur le site s'affichent ici dans une frise historique qui m�lange joyeusement anecdotes et m�mentos historiques, mettant en comparaison guerres et inventions sur tous les continents.</p>
<style type="text/css">
#Timeline
{
	border-left:1px solid gray;
	background-repeat:repeat-y;
	margin-left:40px;
	padding-left:80px;
}

.event h5
{
	color:#333333;
	margin:0;
	padding:0;
	position:relative;
	font-family:Arial,Helvetica,sans-serif
}

#Timeline .date
{
	left:-105px;
	position:absolute;
	border:1px solid #b9b9b9;
	background:#f5f5f5;
	width:50px;
	font-size:1.4em;
	text-align:center;
	font-family:monospace;
}

.court
{
	margin-bottom:30px;
}
.event
{
	margin-top:35px;
}

#Timeline .petitTexte
{
	font-size:.8em;
}
</style>

<div id="Timeline">
<?php

//Certains footnotes sont coup�s, on va donc faire taire le Typographe pour �viter qu'il n'hurle.
Typo::addOption(FIX_MISMATCHED_DELIMITERS);
Typo::addOption(RAISE_NO_ERROR);
foreach($C['Dates'] as $Date=>$Events)
{

	if($Date/100==intval($Date/100) && $Date > 0)
	{//La date peut �tre repr�sent�e par un si�cle.
		$ChiffreRomain=$C['Siecles'][abs($Date)/100];
		if($Date<0)
			$ChiffreRomain = '-' . $ChiffreRomain;
		$DateHTML='<span class="century">' . $ChiffreRomain . '</span><sup>e</sup>';
		$Find=array('\century{' . $ChiffreRomain . '}','\c{' . $ChiffreRomain . '}',$Date);
		$Replace='\c{' . $ChiffreRomain . '}';
	}
	else
		$DateHTML=$Find=$Replace=$Date;

	$DateHTML='<span class="date" id="date-' . $Date . '">' . $DateHTML . '</span> ';//Le HTML qui sera utilis� pour repr�senter la date � sa premi�re apparition.
	foreach($Events as $Event)
	{
		$Event[0]=str_replace($Find,'\color[red]{' . $Replace . '}',$Event[0]);//Mettre en rouge l'occurence du mot.
		$Lien=Anchor::omni($Event[1]);//omnilogismLink($Event[1],$Date);

		Typo::setTexte(str_replace('\li ','\item ',$Event[0]));
		echo '<div class="event">
		<h5>' . $DateHTML  . $Lien . '</h5>
		<div class="petitTexte">' . ParseMath(Typo::Parse()) . '</div>
</div>';
		$DateHTML='';//�viter de r�afficher la date si plusieurs �venements la m�me ann�e / m�me si�cle.
	}
// 	echo '<hr class="court" />';
}
?>
</div>
<?php
/**
* Layout :
* - Disclamer
* - Form
*/

//Afficher la catégorie
?>
<h3><span>Recevoir l'article du jour par mail</span></h3>
<p>Ce formulaire permet de recevoir tous les jours par mail l'article qui vient de paraître.<br />
Vous pourrez bien entendu vous désinscrire à tout moment d'un simple clic...</p>

<form style="border:1px solid #ccc;padding:3px;text-align:center;" action="http://feedburner.google.com/fb/a/mailverify" method="post" target="popupwindow" onsubmit="window.open('http://feedburner.google.com/fb/a/mailverify?uri=Omnilogie', 'popupwindow', 'scrollbars=yes,width=550,height=520');return true">
	<p><label for="email">Entrez votre adresse mail :</label><input type="text" style="width:140px" name="email" id="email"/><br />
	<input type="hidden" value="Omnilogie" name="uri"/>
	<input type="hidden" name="loc" value="fr_FR"/>
	<input type="submit" value="M'inscrire" /></p>
</form>
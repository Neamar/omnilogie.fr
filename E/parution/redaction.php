<?php
/**
* Fichier d'évènement
* Event::PARUTION
*
* Envoie un mail pour rappeler d'écrire un omnilogisme
*/

//Récupérer la liste des personnes à mailer.
$Mails = SQL::query('SELECT ID, Mail, Auteur, Hash
FROM OMNI_Auteurs
WHERE DAYOFMONTH(NOW())=(ID % 31)
AND DernierMail<>"2020-01-01"');

SQL::update('OMNI_Auteurs',-1,array('_DernierMail'=>'NOW()'),'OR (DAYOFMONTH(NOW())=(ID % 31) AND DernierMail<>"2020-01-01")','1000');

while($Mail = mysql_fetch_assoc($Mails))
{
	External::mail($Mail['Mail'],'Rédaction d\'omnilogisme',
<<<MAIL
<p>Bonjour {$Mail['Auteur']} !<br />
Omnilogie a besoin de rédacteurs pour avancer : son rythme de parution régulier et quotidien impose de lui insuffler en permanence du sang frais et des idées neuves. C'est pourquoi nous nous permettons d'envoyer de temps en temps des mails à nos membres, une piqûre de rappel pour ne pas oublier le site. Si ce principe vous déplait, ne marquez pas ce message comme spam : cliquez simplement sur le lien de désinscription pour ne plus entendre parler de nous par mail.</p>

<p>D'un autre côté, vous pouvez aussi garder cet email dans un coin de votre boite mail, pour l'exploiter quand vous en aurez le temps et l'inspiration ; après tout c'est son but non ?</p>

<p>Lien de rédaction direct : <a href="http://omnilogie.fr/membres/Redaction?membre={$Mail['Hash']}">http://omnilogie.fr/membres/Redaction?membre={$Mail['Hash']}</a> <br />
Pas d'idées ? Nous avons une  <a href="http://omnilogie.fr/membres/Propositions?membre={$Mail['Hash']}">liste de propositions</a> d'articles à rédiger, il y en a pour tous les goûts !</p>

<p>Merci d'avance pour votre contribution à cette encyclopédie quotidienne !</p>

<hr />
<small><a href="http://omnilogie.fr/membres/StopMail?membre={$Mail['Hash']}">Ne plus recevoir aucun mail d'Omnilogie</a> (désactive aussi les newsletters exceptionnelles)</small>
MAIL
);
}
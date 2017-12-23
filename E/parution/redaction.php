<?php
/**
* Fichier d'�v�nement
* Event::PARUTION
*
* Envoie un mail pour rappeler d'�crire un omnilogisme
*/

//R�cup�rer la liste des personnes � mailer.
$Mails = SQL::query('SELECT ID, Mail, Auteur, Hash
FROM OMNI_Auteurs
WHERE DAYOFMONTH(NOW())=(ID % 31)
AND DernierMail<>"2020-01-01"');

SQL::update('OMNI_Auteurs',-1,array('_DernierMail'=>'NOW()'),'OR (DAYOFMONTH(NOW())=(ID % 31) AND DernierMail<>"2020-01-01")','1000');

while($Mail = mysql_fetch_assoc($Mails))
{
	External::mail($Mail['Mail'],'R�daction d\'omnilogisme',
<<<MAIL
<p>Bonjour {$Mail['Auteur']} !<br />
Omnilogie a besoin de r�dacteurs pour avancer : son rythme de parution r�gulier et quotidien impose de lui insuffler en permanence du sang frais et des id�es neuves. C'est pourquoi nous nous permettons d'envoyer de temps en temps des mails � nos membres, une piq�re de rappel pour ne pas oublier le site. Si ce principe vous d�plait, ne marquez pas ce message comme spam : cliquez simplement sur le lien de d�sinscription pour ne plus entendre parler de nous par mail.</p>

<p>D'un autre c�t�, vous pouvez aussi garder cet email dans un coin de votre boite mail, pour l'exploiter quand vous en aurez le temps et l'inspiration ; apr�s tout c'est son but non ?</p>

<p>Lien de r�daction direct : <a href="http://omnilogie.fr/membres/Redaction?membre={$Mail['Hash']}">http://omnilogie.fr/membres/Redaction?membre={$Mail['Hash']}</a> <br />
Pas d'id�es ? Nous avons une  <a href="http://omnilogie.fr/membres/Propositions?membre={$Mail['Hash']}">liste de propositions</a> d'articles � r�diger, il y en a pour tous les go�ts !</p>

<p>Merci d'avance pour votre contribution � cette encyclop�die quotidienne !</p>

<hr />
<small><a href="http://omnilogie.fr/membres/StopMail?membre={$Mail['Hash']}">Ne plus recevoir aucun mail d'Omnilogie</a> (d�sactive aussi les newsletters exceptionnelles)</small>
MAIL
);
}
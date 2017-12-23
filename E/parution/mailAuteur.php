<?php
/**
* Fichier d'�v�nement
* Event::PARUTION
*
* Envoie un mail � l'auteur pour l'informer que son article est paru
*/

//Charger les donn�es de l'auteur du jour
$Auteur = SQL::singleQuery('SELECT Auteur, Mail, Hash FROM OMNI_Auteurs WHERE ID=(SELECT Auteur FROM OMNI_Omnilogismes WHERE ID=' . $Article->ID . ')');

//Lui envoyer un mail :
External::mail($Auteur['Mail'],'Omnilogiste du jour, bonjour !',
"<h2>Omnilogiste du jour, bonjour !</h2>
<p>" . $Auteur['Auteur'] . ", bonjour !<br />
L'�quipe d'administration est heureuse de vous informer que vous avez �t� s�lectionn� pour �tre l'omnilogiste du jour.</p>
<p>Vous pourrez retrouver votre article en page d'accueil sur <a href='http://omnilogie.fr'>omnilogie.fr</a> pour la journ�e, ou en permanence sur " . str_replace('/O/',URL . '/O/',Anchor::omni($Article)) . ".</p>

<p>En esp�rant vous compter bient�t de nouveau dans nos rangs,<br />
Les administrateurs.</p>

<hr />
<p><a href='http://omnilogie.fr/membres/Redaction?membre=" . $Auteur['Hash'] . "'>�crire un nouvel article</a></p>");
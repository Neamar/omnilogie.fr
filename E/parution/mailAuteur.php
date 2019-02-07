<?php
/**
* Fichier d'évènement
* Event::PARUTION
*
* Envoie un mail à l'auteur pour l'informer que son article est paru
*/

//Charger les données de l'auteur du jour
$Auteur = SQL::singleQuery('SELECT Auteur, Mail, Hash FROM OMNI_Auteurs WHERE ID=(SELECT Auteur FROM OMNI_Omnilogismes WHERE ID=' . $Article->ID . ')');

//Lui envoyer un mail :
External::mail($Auteur['Mail'],'Omnilogiste du jour, bonjour !',
"<h2>Omnilogiste du jour, bonjour !</h2>
<p>" . $Auteur['Auteur'] . ", bonjour !<br />
L'équipe d'administration est heureuse de vous informer que vous avez été sélectionné pour être l'omnilogiste du jour.</p>
<p>Vous pourrez retrouver votre article en page d'accueil sur <a href='http://omnilogie.fr'>omnilogie.fr</a> pour la journée, ou en permanence sur " . str_replace('/O/',URL . '/O/',Anchor::omni($Article)) . ".</p>

<p>En espérant vous compter bientôt de nouveau dans nos rangs,<br />
Les administrateurs.</p>

<hr />
<p><a href='http://omnilogie.fr/membres/Redaction?membre=" . $Auteur['Hash'] . "'>Écrire un nouvel article</a></p>");
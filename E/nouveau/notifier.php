<?php
/**
* Fichier d'évènement
* Event::NOUVEAU
*
* Envoie une notification aux admins.
* Les admins peuvent configurer la façon de recevoir cette alerte : rien, mail, sms, notification GCalendar.
*/

External::notify('« ' . $Article->Titre . ' » par ' . AUTHOR,'Par ' . AUTHOR . ' (ID ' . AUTHOR_ID . ')' . ' connecté sur ' . $_SERVER["REMOTE_ADDR"] . ', le ' . time());
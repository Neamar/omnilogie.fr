<?php
/**
* Fichier d'�v�nement
* Event::NOUVEAU
*
* Envoie une notification aux admins.
* Les admins peuvent configurer la fa�on de recevoir cette alerte : rien, mail, sms, notification GCalendar.
*/

External::notify('� ' . $Article->Titre . '�� par ' . AUTHOR,'Par ' . AUTHOR . ' (ID ' . AUTHOR_ID . ')' . ' connect� sur ' . $_SERVER["REMOTE_ADDR"] . ', le ' . time());
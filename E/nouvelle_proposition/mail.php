<?php
/**
* Fichier d'évènement
* Event::NOUVELLE_PROPOSITION
*
* Envoie une notification aux admins responsable des propositions.
*/

External::mail('domi.lc@free.fr','Nouvelle proposition','<p>Nouvelle proposition sur le site par ' . AUTHOR . '.<br />
Consulter <a href="' . URL . '/membres/Propositions">la page des propostions</a> pour plus de détails</p>');